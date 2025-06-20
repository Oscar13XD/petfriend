<?php
session_start();
require_once __DIR__ . '/../db/config.php';

// Verificar que el usuario esté logueado
if (!isset($_SESSION['ID_USUARIO'])) {
  header("Location: login.php");
  exit();
}

$id_usuario = $_SESSION['ID_USUARIO'];
$mensaje_exito = false;

// Filtro de estado actual
$estado = isset($_GET['estado']) ? $_GET['estado'] : 'EN CURSO';

// Eliminar publicaciones completadas automáticamente
$pdo->query("DELETE FROM publicaciones WHERE estado = 'COMPLETADA'");

// Obtener publicaciones filtradas por estado
$stmt = $pdo->prepare("SELECT * FROM publicaciones WHERE usuario_id = :uid AND estado = :estado ORDER BY fecha DESC");
$stmt->execute(['uid' => $id_usuario, 'estado' => $estado]);
$publicaciones = $stmt->fetchAll();

$mensaje_exito = isset($_GET['deleted']) && $_GET['deleted'] == 1;
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Estado de Publicaciones</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../css/inicio.css">
</head>
<div class="d-flex">
  <!-- Sidebar -->
 <div id="sidebar" class="text-white p-3">
   <h4 id="titulo">Pet Friend</h4>
    <ul id="barra"class="nav flex-column mb-4"> 
      <li class="nav-item"><a class="nav-link text-white" href="inicio.php">Inicio</a></li>
        <li class="nav-item">
        <a class="nav-link text-white" href="perfil.php">Perfil</a></li>
      <li class="nav-item">
       <a class="nav-link text-white" data-bs-toggle="collapse" href="#submenuAdopciones" role="button"
   aria-expanded="<?= $adopcionActiva ? 'true' : 'false' ?>" aria-controls="submenuAdopciones">

        <?php
  $paginaActual = basename($_SERVER['PHP_SELF']);
  $adopcionActiva = in_array($paginaActual, ['publicar.php', 'estado_publicaciones.php']);
?>
<div class="collapse ps-3 <?= $adopcionActiva ? 'show' : '' ?>" id="submenuAdopciones">
  <a class="nav-link text-white <?= $paginaActual == 'publicar.php' ? 'fw-bold' : '' ?>" href="publicar.php">Publicar</a>
  <a class="nav-link text-white <?= $paginaActual == 'estado_publicaciones.php' ? 'fw-bold' : '' ?>" href="estado_publicaciones.php">Estado</a></div></li>
      <li class="nav-item"><a class="nav-link text-white" href="configuracion.php">Configuración</a></li>
      <li class="nav-item"><a class="nav-link text-white" href="acerca_terminos.php" >Términos</a></li>
      <a class="nav-link text-white" href="logout.php">Cerrar sesión</a>

    </ul>
  </div>

  <!-- Contenido principal -->
  <div id="main-content" class="flex-grow-1">
    <button class="btn btn-sm btn-secondary m-3" onclick="toggleSidebar()">☰ Menú</button>
<body class="bg-light">
<div class="container py-4">
  <h2 class="mb-4 text-center">📌 Publicaciones - Estado: <?= htmlspecialchars($estado) ?></h2>

  <?php if ($mensaje_exito): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      Publicación eliminada correctamente.
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
    </div>
  <?php endif; ?>

  <div class="d-flex justify-content-center gap-2 mb-4">
    <a href="?estado=EN%20CURSO" class="btn btn-success">🟢 En Curso</a>
    <a href="?estado=CANCELADA" class="btn btn-danger">🔴 Canceladas</a>
    <a href="?estado=COMPLETADA" class="btn btn-secondary">✅ Completadas</a>
  </div>

  <?php if (empty($publicaciones)): ?>
    <div class="alert alert-info">No hay publicaciones con el estado seleccionado.</div>
  <?php else: ?>
    <div class="row">
      <?php foreach ($publicaciones as $pub): ?>
        <div class="col-md-6 col-lg-4 mb-4">
          <div class="card shadow-sm">
            <?php if (!empty($pub['imagen'])): ?>
              <img src="../uploads/<?= htmlspecialchars($pub['imagen']) ?>" class="card-img-top" alt="Imagen">
            <?php endif; ?>
            <div class="card-body">
              <h5 class="card-title"><?= htmlspecialchars($pub['titulo']) ?></h5>
              <p class="card-text"><?= htmlspecialchars($pub['contenido']) ?></p>
              <form method="POST" action="actualizar_estado.php" class="mt-3 d-flex gap-2">
                <input type="hidden" name="id" value="<?= $pub['id'] ?>">
                <select name="estado" class="form-select form-select-sm">
                  <option <?= $pub['estado'] == 'EN CURSO' ? 'selected' : '' ?>>EN CURSO</option>
                  <option <?= $pub['estado'] == 'CANCELADA' ? 'selected' : '' ?>>CANCELADA</option>
                  <option <?= $pub['estado'] == 'COMPLETADA' ? 'selected' : '' ?>>COMPLETADA</option>
                </select>
                <button type="submit" class="btn btn-outline-primary btn-sm">Actualizar</button>
              </form>
              <a href="estado_publicaciones.php?delete=<?= $pub['id'] ?>&estado=<?= urlencode($estado) ?>" class="btn btn-outline-danger btn-sm mt-2" onclick="return confirm('¿Eliminar esta publicación?')">🗑 Eliminar</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>
<script>
  function toggleSidebar() {
    const sidebar = document.getElementById("sidebar");
    sidebar.classList.toggle("collapsed");
  }

  function mostrarSeccion(id, event) {
    event.preventDefault();
    document.querySelectorAll('.seccion').forEach(sec => sec.classList.remove('activa'));
    document.getElementById(id).classList.add('activa');
  }
</script>
</body>
</html>

<?php
// Lógica para eliminar publicación desde este archivo (si se usa GET)
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
  $deleteId = (int)$_GET['delete'];
  $stmtImg = $pdo->prepare("SELECT imagen FROM publicaciones WHERE id = :id AND usuario_id = :uid");
  $stmtImg->execute(['id' => $deleteId, 'uid' => $id_usuario]);
  $img = $stmtImg->fetchColumn();
  if ($img && file_exists(__DIR__ . '/../uploads/' . $img)) {
    unlink(__DIR__ . '/../uploads/' . $img);
  }
  $pdo->prepare("DELETE FROM publicaciones WHERE id = :id AND usuario_id = :uid")
      ->execute(['id' => $deleteId, 'uid' => $id_usuario]);
  header("Location: estado_publicaciones.php?estado=" . urlencode($estado) . "&deleted=1");
  exit();
}
?>



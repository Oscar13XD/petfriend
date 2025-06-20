<?php
session_start();
require_once __DIR__ . '/../db/config.php';

$id_usuario = $_SESSION['ID_USUARIO'] ?? null;
if (!$id_usuario) {
  header("Location: login.php");
  exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $titulo = trim($_POST['titulo']);
  $contenido = trim($_POST['contenido']);
  $nombreArchivo = null;

  if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === 0) {
    $nombreArchivo = uniqid() . '_' . basename($_FILES['imagen']['name']);
    move_uploaded_file($_FILES['imagen']['tmp_name'], __DIR__ . '/../uploads/' . $nombreArchivo);
  }

  $stmt = $pdo->prepare("INSERT INTO publicaciones (usuario_id, titulo, contenido, imagen, fecha) VALUES (:uid, :titulo, :contenido, :imagen, NOW())");
  $stmt->execute([
    'uid' => $id_usuario,
    'titulo' => $titulo,
    'contenido' => $contenido,
    'imagen' => $nombreArchivo
  ]);

  $mensaje_exito = '¡Publicación realizada con éxito!';
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Publicar Adopción</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../css/inicio.css">
<style>
  .publicacion-imagen {
    width: 100%;
    max-width: 600px;
    height: auto;
    object-fit: cover;
    border-radius: 10px;
    margin-top: 10px;
  }
</style>
</head>
<body>
<div class="d-flex">
  <!-- Sidebar -->
 <div id="sidebar" class="text-white p-3">
   <h4 id="titulo">Pet Friend</h4>
    <ul id="barra"class="nav flex-column mb-4"> 
      <li class="nav-item"><a class="nav-link text-white" href="inicio.php">Inicio</a></li >
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

  <div class="container py-4">
    <h2 class="mb-4">Nueva Publicación de Adopción</h2>
    <?php if (isset($mensaje_exito)): ?>
      <div class="alert alert-success"> <?= $mensaje_exito ?> </div>
    <?php endif; ?>
    <form method="POST" enctype="multipart/form-data">
      <div class="mb-3">
        <label for="titulo" class="form-label">Título</label>
        <input type="text" name="titulo" id="titulo" class="form-control" required>
      </div>
      <div class="mb-3">
        <label for="contenido" class="form-label">Descripción</label>
        <textarea name="contenido" id="contenido" class="form-control" rows="5" required></textarea>
      </div>
      <div class="mb-3">
        <label for="imagen" class="form-label">Imagen de la mascota</label>
        <input type="file" name="imagen" id="imagen" class="form-control" accept="image/*">
      </div>
      <button type="submit" class="btn btn-primary">Publicar</button>
      <a href="Inicio.php" class="btn btn-secondary">Cancelar</a>
    </form>
  </div>
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





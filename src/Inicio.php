<?php
session_start();
require_once __DIR__ . '/../db/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comentario']) && isset($_POST['publicacion_id'])) {
  $comentario = trim($_POST['comentario']);
  $publicacion_id = (int)$_POST['publicacion_id'];
  $stmt = $pdo->prepare("INSERT INTO comentarios (usuario_id, publicacion_id, contenido, fecha) VALUES (:uid, :pid, :contenido, NOW())");
  $stmt->execute([
    'uid' => $_SESSION['ID_USUARIO'],
    'pid' => $publicacion_id,
    'contenido' => $comentario
  ]);
  header("Location: inicio.php");
  exit();
}

require_once __DIR__ . '/../db/config.php';

$id_usuario = $_SESSION['ID_USUARIO'];

// Obtener todas las publicaciones con datos de usuario
$stmt = $pdo->query("SELECT p.*, u.NOMBRES, u.APELLIDOS FROM publicaciones p JOIN usuarios u ON p.usuario_id = u.ID_USUARIO ORDER BY p.fecha DESC");
$publicaciones = $stmt->fetchAll();

// Obtener comentarios y me gusta por publicacion
$comentariosPorPub = [];
$likesPorPub = [];
$stmtComentarios = $pdo->prepare("SELECT c.*, u.NOMBRES FROM comentarios c JOIN usuarios u ON c.usuario_id = u.ID_USUARIO WHERE c.publicacion_id = :pid ORDER BY c.fecha ASC");
$stmtLikes = $pdo->prepare("SELECT COUNT(*) FROM reacciones WHERE publicacion_id = :pid");

foreach ($publicaciones as $pub) {
  $stmtComentarios->execute(['pid' => $pub['id']]);
  $comentariosPorPub[$pub['id']] = $stmtComentarios->fetchAll();

  $stmtLikes->execute(['pid' => $pub['id']]);
  $likesPorPub[$pub['id']] = $stmtLikes->fetchColumn();
}

// Guardar me gusta
if (isset($_GET['like']) && is_numeric($_GET['like'])) {
  $like_id = (int)$_GET['like'];
  $stmtCheck = $pdo->prepare("SELECT * FROM reacciones WHERE publicacion_id = :pid AND usuario_id = :uid");
  $stmtCheck->execute(['pid' => $like_id, 'uid' => $id_usuario]);
  if ($stmtCheck->rowCount() === 0) {
    $pdo->prepare("INSERT INTO reacciones (publicacion_id, usuario_id, fecha) VALUES (:pid, :uid, NOW())")
        ->execute(['pid' => $like_id, 'uid' => $id_usuario]);
  }
  header("Location: inicio.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inicio - Pet Friend</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../css/inicio.css">
  <style>
    .post img {
      width: 100%;
      max-width: 600px;
      height: auto;
      object-fit: cover;
      border-radius: 8px;
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
      <li class="nav-item"><a class="nav-link text-white" href="#" onclick="mostrarSeccion('inicio', event)">Inicio</a></li>
        <li class="nav-item">
        <a class="nav-link text-white" href="perfil.php">Perfil</a></li>
      <li class="nav-item">
        <a class="nav-link text-white" data-bs-toggle="collapse" href="#submenuAdopciones" role="button" aria-expanded="false" aria-controls="submenuAdopciones">Adopciones</a>
        <div class="collapse ps-3" id="submenuAdopciones">
          <a class="nav-link text-white" href="publicar.php" >Publicar</a>
          <a class="nav-link text-white" href="#" onclick="mostrarSeccion('estado', event)">Estado</a>
        </div>
      </li>
      <li class="nav-item"><a class="nav-link text-white" href="#" onclick="mostrarSeccion('configuracion', event)">Configuración</a></li>
      <li class="nav-item"><a class="nav-link text-white" href="#" onclick="mostrarSeccion('privacidad', event)">Privacidad</a></li>
      <li class="nav-item"><a class="nav-link text-white" href="#" onclick="mostrarSeccion('terminos', event)">Términos</a></li>
      <a class="nav-link text-white" href="logout.php">Cerrar sesión</a>

    </ul>
  </div>

  <!-- Contenido principal -->
  <div id="main-content" class="flex-grow-1">
    <button class="btn btn-sm btn-secondary m-3" onclick="toggleSidebar()">☰ Menú</button>
  <div id="main-content" class="flex-grow-1">
    <div class="container py-4">
      <h3 class="mb-4">Publicaciones recientes</h3>
      <?php foreach ($publicaciones as $pub): ?>
        <div class="post mb-4">
          <div class="d-flex justify-content-between">
            <h5><?= htmlspecialchars($pub['titulo']) ?> <small class="text-muted">- <?= htmlspecialchars($pub['NOMBRES']) . ' ' . htmlspecialchars($pub['APELLIDOS']) ?></small></h5>
          </div>
          <p><?= nl2br(htmlspecialchars($pub['contenido'])) ?></p>
          <?php if (!empty($pub['imagen'])): ?>
            <img src="../uploads/<?= htmlspecialchars($pub['imagen']) ?>" alt="Imagen publicación">
          <?php endif; ?>
          <div class="reactions mt-2">
            <a href="?like=<?= $pub['id'] ?>" class="btn btn-outline-primary btn-sm">👍 Me gusta (<?= $likesPorPub[$pub['id']] ?? 0 ?>)</a>
          </div>
          <div class="mt-3">
  <strong>Comentarios:</strong>
  <div class="mt-2">
    <?php if (!empty($comentariosPorPub[$pub['id']])): ?>
      <?php foreach ($comentariosPorPub[$pub['id']] as $comentario): ?>
        <p><strong><?= htmlspecialchars($comentario['NOMBRES']) ?>:</strong> <?= htmlspecialchars($comentario['contenido']) ?></p>
      <?php endforeach; ?>
    <?php else: ?>
      <p><em>No hay comentarios aún.</em></p>
    <?php endif; ?>
    <form method="POST" class="mt-2">
      <div class="input-group">
        <input type="hidden" name="publicacion_id" value="<?= $pub['id'] ?>">
        <input type="text" name="comentario" class="form-control" placeholder="Escribe un comentario..." required>
        <button type="submit" class="btn btn-primary">Enviar</button>
      </div>
    </form>
  </div>
</div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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






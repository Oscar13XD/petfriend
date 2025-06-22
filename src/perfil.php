<?php
session_start();
require_once __DIR__ . '/../db/config.php';

if (!isset($_SESSION['ID_USUARIO'])) {
  header("Location: login.php");
  exit();
}

$id_usuario = $_SESSION['ID_USUARIO'];
$usuario_id = isset($_GET['id']) ? (int)$_GET['id'] : $id_usuario;

// Manejar foto de perfil
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['nueva_foto'])) {
  $rutaDestino = __DIR__ . '/../uploads/perfil/' . $id_usuario . '.jpg';
  move_uploaded_file($_FILES['nueva_foto']['tmp_name'], $rutaDestino);
  header("Location: perfil.php");
  exit();
}

// Editar biografía
if (isset($_POST['biografia'])) {
  $bio = trim($_POST['biografia']);
  $stmtBio = $pdo->prepare("UPDATE usuarios SET biografia = :bio WHERE ID_USUARIO = :id");
  $stmtBio->execute(['bio' => $bio, 'id' => $id_usuario]);
  header("Location: perfil.php");
  exit();
}

// Eliminar relacionados con completadas
$pdo->prepare("DELETE FROM comentarios WHERE publicacion_id IN (SELECT id FROM publicaciones WHERE usuario_id = :id AND estado = 'COMPLETADA')")->execute(['id' => $id_usuario]);
$pdo->prepare("DELETE FROM reacciones WHERE publicacion_id IN (SELECT id FROM publicaciones WHERE usuario_id = :id AND estado = 'COMPLETADA')")->execute(['id' => $id_usuario]);
$pdo->prepare("DELETE FROM publicaciones WHERE usuario_id = :id AND estado = 'COMPLETADA'")->execute(['id' => $id_usuario]);

// Datos del perfil
$stmtUser = $pdo->prepare("SELECT * FROM usuarios WHERE ID_USUARIO = :id");
$stmtUser->execute(['id' => $usuario_id]);
$usuario = $stmtUser->fetch();

if (!$usuario) {
  echo "<div class='alert alert-danger m-3'>Usuario no encontrado.</div>";
  exit();
}

$fotoPerfil = file_exists(__DIR__ . '/../uploads/perfil/' . $usuario['ID_USUARIO'] . '.jpg')
  ? '../uploads/perfil/' . $usuario['ID_USUARIO'] . '.jpg?' . time()
  : '../img/default.jpg';

// Publicaciones (solo propias)
$stmtPub = $pdo->prepare("SELECT * FROM publicaciones WHERE usuario_id = :id AND estado != 'COMPLETADA' ORDER BY fecha DESC");
$stmtPub->execute(['id' => $usuario_id]);
$publicaciones = $stmtPub->fetchAll();

// Comentarios por publicación
$comentariosPorPub = [];
$stmtComentarios = $pdo->prepare("SELECT c.*, u.NOMBRES FROM comentarios c JOIN usuarios u ON c.usuario_id = u.ID_USUARIO WHERE c.publicacion_id = :pid ORDER BY c.fecha ASC");
foreach ($publicaciones as $pub) {
  $stmtComentarios->execute(['pid' => $pub['id']]);
  $comentariosPorPub[$pub['id']] = $stmtComentarios->fetchAll();
}

// Likes por publicación
$likesPorPub = [];
$stmtLikes = $pdo->prepare("SELECT COUNT(*) FROM reacciones WHERE publicacion_id = :pid");
foreach ($publicaciones as $pub) {
  $stmtLikes->execute(['pid' => $pub['id']]);
  $likesPorPub[$pub['id']] = $stmtLikes->fetchColumn();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Mi Perfil - Pet Friend</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../css/inicio.css">
</head>
<body>
<div class="d-flex">
  <!-- Sidebar -->
 <div id="sidebar" class="text-white p-3">
   <h4 id="titulo">Pet Friend</h4>
    <ul id="barra"class="nav flex-column mb-4"> 
      <li class="nav-item"><a class="nav-link text-white" href="Inicio.php">Inicio</a></li>
        <li class="nav-item">
        <a class="nav-link text-white" href="perfil.php">Perfil</a></li>
      <li class="nav-item">
        <a class="nav-link text-white" data-bs-toggle="collapse" href="#submenuAdopciones" role="button" aria-expanded="false" aria-controls="submenuAdopciones">Adopciones</a>
        <div class="collapse ps-3" id="submenuAdopciones">
          <a class="nav-link text-white" href="publicar.php" >Publicar</a>
          <a class="nav-link text-white" href="estado_publicaciones.php">Estado</a></div></li>
          <li class="nav-item"><a class="nav-link text-white" href="bandeja_mensajes.php">Mensajes</a></li>

      <li class="nav-item"><a class="nav-link text-white" href="configuracion.php">Configuración</a></li>
      <li class="nav-item"><a class="nav-link text-white" href="acerca_terminos.php" >Términos</a></li>
      <a class="nav-link text-white" href="logout.php">Cerrar sesión</a>
    </ul>
  </div>

  <!-- Contenido principal -->
  <div id="main-content" class="flex-grow-1">
    <button class="btn btn-sm btn-secondary m-3" onclick="toggleSidebar()">☰ Menú</button>
    <div class="container py-4">
      <div class="card mb-4 shadow-sm">
        <div class="card-body d-flex align-items-center">
          <img src="<?= $fotoPerfil ?>" alt="Foto de perfil" class="rounded-circle me-4" style="width: 100px; height: 100px; object-fit: cover;">
          <div>
            <h4 class="mb-1"><?= htmlspecialchars($usuario['NOMBRES'] . ' ' . $usuario['APELLIDOS']) ?></h4>
            <p class="mb-1 text-muted">Correo: <?= htmlspecialchars($usuario['CORREO']) ?></p>
            <p class="mb-1 text-muted">Edad: <?= htmlspecialchars($usuario['EDAD']) ?> años</p>
            <p class="mb-1 text-muted">Ciudad: <?= htmlspecialchars($usuario['CIUDAD']) ?></p>
            <p class="mb-1 text-muted">Identificación: <?= htmlspecialchars($usuario['IDENTIFICACION']) ?></p>
            <?php if ($usuario['ID_USUARIO'] == $id_usuario): ?>
              <form method="POST" enctype="multipart/form-data" class="mt-2">
                <input type="file" name="nueva_foto" class="form-control form-control-sm mb-2" accept="image/*">
                <button type="submit" class="btn btn-sm btn-outline-primary">Actualizar foto</button>
              </form>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <!-- Biografía -->
      <?php if ($usuario['ID_USUARIO'] == $id_usuario): ?>
        <div class="bio-box my-4">
          <h5>Biografía</h5>
          <form method="POST">
            <textarea name="biografia" rows="3" class="form-control mb-2"><?= htmlspecialchars($usuario['biografia'] ?? '') ?></textarea>
            <button type="submit" class="btn btn-sm btn-success">Guardar biografía</button>
          </form>
        </div>
      <?php endif; ?>

      <!-- Enviar mensaje -->
      <?php if ($usuario['ID_USUARIO'] != $id_usuario): ?>
        <div class="card mb-4 shadow-sm">
          <div class="card-body">
            <h5 class="card-title">✉️ Enviar mensaje a <?= htmlspecialchars($usuario['NOMBRES']) ?></h5>
            <form action="enviar_mensaje.php" method="POST">
              <input type="hidden" name="receptor_id" value="<?= $usuario['ID_USUARIO'] ?>">
              <div class="mb-2">
                <textarea name="contenido" class="form-control" rows="3" placeholder="Escribe tu mensaje aquí..." required></textarea>
              </div>
              <button type="submit" class="btn btn-primary">Enviar mensaje</button>
            </form>
          </div>
        </div>
      <?php endif; ?>

      <!-- Publicaciones -->
      <?php if (!empty($publicaciones)): ?>
        <?php foreach ($publicaciones as $pub): ?>
          <div class="card mb-4">
            <div class="card-body">
              <h5 class="card-title"><?= htmlspecialchars($pub['titulo']) ?></h5>
              <p class="card-text"><?= htmlspecialchars($pub['contenido']) ?></p>
              <?php if (!empty($pub['imagen'])): ?>
                <img src="../uploads/<?= htmlspecialchars($pub['imagen']) ?>" class="img-fluid mt-2" alt="Imagen">
              <?php endif; ?>
              <form method="POST" action="actualizar_estado.php" class="mt-3 d-flex gap-2">
                <input type="hidden" name="id" value="<?= $pub['id'] ?>">
                <select name="estado" class="form-select form-select-sm">
                  <option <?= $pub['estado'] == 'EN CURSO' ? 'selected' : '' ?>>EN CURSO</option>
                  <option <?= $pub['estado'] == 'CANCELADA' ? 'selected' : '' ?>>CANCELADA</option>
                  <option <?= $pub['estado'] == 'COMPLETADA' ? 'selected' : '' ?>>COMPLETADA</option>
                </select>
                <button type="submit" class="btn btn-outline-primary btn-sm">Actualizar</button>
              </form>
              <div class="reactions mt-2">
                <span class="badge bg-primary">👍 <?= $likesPorPub[$pub['id']] ?? 0 ?></span>
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
                  <form method="POST">
                    <div class="input-group">
                      <input type="hidden" name="publicacion_id" value="<?= $pub['id'] ?>">
                      <input type="text" name="comentario" class="form-control" placeholder="Escribe un comentario..." required>
                      <button type="submit" class="btn btn-primary">Enviar</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="alert alert-info">No tienes publicaciones activas.</div>
      <?php endif; ?>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function toggleSidebar() {
  document.getElementById("sidebar").classList.toggle("collapsed");
}
</script>
</body>
</html>














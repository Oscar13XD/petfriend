<?php
session_start();
require_once __DIR__ . '/../db/config.php';

$id_usuario = $_SESSION['ID_USUARIO'];

// Subida de nueva foto de perfil
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['nueva_foto'])) {
  $rutaDestino = __DIR__ . '/../uploads/perfil/' . $id_usuario . '.jpg';
  move_uploaded_file($_FILES['nueva_foto']['tmp_name'], $rutaDestino);
  header("Location: perfil.php");
  exit();
}

// Guardar nuevo comentario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comentario']) && isset($_POST['publicacion_id'])) {
  $comentario = trim($_POST['comentario']);
  $publicacion_id = $_POST['publicacion_id'];
  $stmtCom = $pdo->prepare("INSERT INTO comentarios (usuario_id, publicacion_id, contenido, fecha) VALUES (:uid, :pid, :contenido, NOW())");
  $stmtCom->execute([
    'uid' => $id_usuario,
    'pid' => $publicacion_id,
    'contenido' => $comentario
  ]);
  header("Location: perfil.php");
  exit();
}

// Eliminar publicación
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
  header("Location: perfil.php");
  exit();
}

// Obtener datos del usuario
$stmtUser = $pdo->prepare("SELECT * FROM usuarios WHERE ID_USUARIO = :id");
$stmtUser->execute(['id' => $id_usuario]);
$usuario = $stmtUser->fetch();

// Obtener publicaciones del usuario
$stmtPub = $pdo->prepare("SELECT * FROM publicaciones WHERE usuario_id = :id ORDER BY fecha DESC");
$stmtPub->execute(['id' => $id_usuario]);
$publicaciones = $stmtPub->fetchAll();

// Obtener comentarios por publicación
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
  header("Location: perfil.php");
  exit();
}

foreach ($publicaciones as $pub) {
  $stmtComentarios->execute(['pid' => $pub['id']]);
  $comentariosPorPub[$pub['id']] = $stmtComentarios->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mi Perfil - Pet Friend</title>
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
      <div class="profile-header d-flex align-items-center">
        <img src="../uploads/perfil/<?= $usuario['ID_USUARIO'] ?>.jpg" alt="Foto de perfil" class="profile-img me-3">
        <div>
          <h3><?= $usuario['NOMBRES'] . ' ' . $usuario['APELLIDOS'] ?></h3>
          <p class="text-muted"><?= $usuario['CIUDAD'] ?> | <?= $usuario['EDAD'] ?> años</p>
          <form method="POST" enctype="multipart/form-data" class="mt-2">
            <label for="nueva_foto" class="form-label">Cambiar foto de perfil</label>
            <input type="file" name="nueva_foto" class="form-control" accept="image/*" required>
            <button type="submit" class="btn btn-sm btn-primary mt-2">Subir</button>
          </form>
        </div>
      </div>

      <div class="bio-box">
        <h5>Biografía</h5>
        <p>Amo a los animales y quiero ayudar a encontrarles un hogar lleno de amor. Me especializo en la adopción de mascotas rescatadas.</p>
      </div>

      <?php foreach ($publicaciones as $pub): ?>
        <div class="post">
          <div class="d-flex justify-content-between">
            <h5><?= htmlspecialchars($pub['titulo']) ?></h5>
            <a href="perfil.php?delete=<?= $pub['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de que deseas eliminar esta publicación?');">Eliminar</a>
          </div>
          <p><?= nl2br(htmlspecialchars($pub['contenido'])) ?></p>
          <?php if (!empty($pub['imagen'])): ?>
            <img src="../uploads/<?= htmlspecialchars($pub['imagen']) ?>" alt="Imagen publicación">
          <?php endif; ?>
          <div class="reactions mt-2">
            <a href="?like=<?= $pub['id'] ?>" class="btn btn-outline-primary btn-sm">👍 Me gusta (<?= $likesPorPub[$pub['id']] ?? 0 ?>)</a>
            <button class="btn btn-outline-secondary btn-sm">💬 Comentar</button>
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






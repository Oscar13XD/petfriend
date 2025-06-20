<?php
session_start();
require_once __DIR__ . '/../db/config.php';

$mensajeEnviado = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['comentario']) && isset($_POST['publicacion_id'])) {
    $comentario = trim($_POST['comentario']);
    $publicacion_id = (int)$_POST['publicacion_id'];
    $stmt = $pdo->prepare("INSERT INTO comentarios (usuario_id, publicacion_id, contenido, fecha) VALUES (:uid, :pid, :contenido, NOW())");
    $stmt->execute([
      'uid' => $_SESSION['ID_USUARIO'],
      'pid' => $publicacion_id,
      'contenido' => $comentario
    ]);
    header("Location: bandeja_mensajes.php");
    exit();
  }

  if (isset($_POST['receptor_id'], $_POST['contenido'])) {
    $emisor_id = $_SESSION['ID_USUARIO'];
    $receptor_id = (int) $_POST['receptor_id'];
    $contenido = trim($_POST['contenido']);

    if ($contenido !== '') {
      $stmt = $pdo->prepare("INSERT INTO mensajes (emisor_id, receptor_id, contenido) VALUES (:emisor, :receptor, :contenido)");
      $stmt->execute([
        'emisor' => $emisor_id,
        'receptor' => $receptor_id,
        'contenido' => $contenido
      ]);
      $mensajeEnviado = true;
    }
  }
}

$id_usuario = $_SESSION['ID_USUARIO'];

// Obtener todas las publicaciones con datos de usuario
$stmt = $pdo->query("SELECT p.*, u.NOMBRES, u.APELLIDOS, u.ID_USUARIO as usuario_id FROM publicaciones p JOIN usuarios u ON p.usuario_id = u.ID_USUARIO ORDER BY p.fecha DESC");
$publicaciones = $stmt->fetchAll();

// Obtener comentarios y me gusta por publicación
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
  header("Location: bandeja_mensajes.php");
  exit();
}

// Obtener mensajes recibidos
$stmtMensajes = $pdo->prepare("SELECT m.*, u.NOMBRES, u.APELLIDOS FROM mensajes m JOIN usuarios u ON m.emisor_id = u.ID_USUARIO WHERE m.receptor_id = :id ORDER BY m.fecha DESC");
$stmtMensajes->execute(['id' => $id_usuario]);
$mensajes = $stmtMensajes->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Bandeja de Mensajes</title>
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
    .mensaje {
      background: #fff;
      padding: 10px;
      border: 1px solid #ddd;
      border-radius: 5px;
      margin-bottom: 10px;
    }
  </style>
</head>
<body>
<div class="d-flex">
  <div id="sidebar" class="text-white p-3">
    <h4 id="titulo">Pet Friend</h4>
    <?php
// Mensajes nuevos para ícono en el menú
$stmtNuevos = $pdo->prepare("SELECT COUNT(*) FROM mensajes WHERE receptor_id = :id AND fecha >= (NOW() - INTERVAL 5 MINUTE)");
$stmtNuevos->execute(['id' => $id_usuario]);
$nuevos = $stmtNuevos->fetchColumn();
?>
<ul id="barra" class="nav flex-column mb-4">
      <li class="nav-item"><a class="nav-link text-white" href="inicio.php">Inicio</a></li>
      <li class="nav-item"><a class="nav-link text-white" href="perfil.php">Perfil</a></li>
<li class="nav-item">
  <a class="nav-link text-white d-flex justify-content-between align-items-center <?= basename($_SERVER['PHP_SELF']) == 'bandeja_mensajes.php' ? 'fw-bold' : '' ?>" href="bandeja_mensajes.php">
    Mensajes
    <?php if (!empty($nuevos) && $nuevos > 0): ?>
      <span class="badge bg-danger ms-2">🔔 <?= $nuevos ?></span>
    <?php endif; ?>
  </a>
</li>
      <li class="nav-item">
        <a class="nav-link text-white" data-bs-toggle="collapse" href="#submenuAdopciones" role="button"
           aria-expanded="<?= in_array(basename($_SERVER['PHP_SELF']), ['publicar.php', 'estado_publicaciones.php']) ? 'true' : 'false' ?>"
           aria-controls="submenuAdopciones">Adopciones</a>
        <div class="collapse ps-3 <?= in_array(basename($_SERVER['PHP_SELF']), ['publicar.php', 'estado_publicaciones.php']) ? 'show' : '' ?>" id="submenuAdopciones">
          <a class="nav-link text-white <?= basename($_SERVER['PHP_SELF']) == 'publicar.php' ? 'fw-bold' : '' ?>" href="publicar.php">Publicar</a>
          <a class="nav-link text-white <?= basename($_SERVER['PHP_SELF']) == 'estado_publicaciones.php' ? 'fw-bold' : '' ?>" href="estado_publicaciones.php">Estado</a>
        </div>
      </li>
      <li class="nav-item"><a class="nav-link text-white" href="#" onclick="mostrarSeccion('configuracion', event)">Configuración</a></li>
      <li class="nav-item"><a class="nav-link text-white" href="acerca_terminos.php" >Términos</a></li>
      <li class="nav-item"><a class="nav-link text-white" href="logout.php">Cerrar sesión</a></li>
    </ul>
  </div>

  <div id="main-content" class="flex-grow-1">
    <button class="btn btn-sm btn-secondary m-3" onclick="toggleSidebar()">☰ Menú</button>
    <div class="container py-4">
      <h3 class="mb-4">📥 Mis Mensajes Recibidos</h3>
      <?php if ($mensajeEnviado): ?>
        <div class="alert alert-success">✅ Mensaje enviado correctamente.</div>
      <?php endif; ?>
      <div class="text-end mb-3">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalRedactar">✍️ Redactar mensaje</button>
      </div>
      <?php if (count($mensajes) === 0): ?>
        <p>No tienes mensajes en tu bandeja de entrada.</p>
      <?php else: ?>
        <?php foreach ($mensajes as $msg): ?>
          <div class="mensaje">
            <strong>De:</strong> <?= htmlspecialchars($msg['NOMBRES'] . ' ' . $msg['APELLIDOS']) ?><br>
            <strong>Mensaje:</strong> <?= nl2br(htmlspecialchars($msg['contenido'])) ?><br>
            <small><em>Enviado el <?= $msg['fecha'] ?></em></small>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>
</div>

<!-- Modal Redactar Mensaje -->
<div class="modal fade" id="modalRedactar" tabindex="-1" aria-labelledby="modalRedactarLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="bandeja_mensajes.php" method="POST">
        <div class="modal-header">
          <h5 class="modal-title" id="modalRedactarLabel">Nuevo mensaje</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="receptor_id" class="form-label">Para:</label>
            <select name="receptor_id" class="form-select" required>
              <option value="" selected disabled>Selecciona un usuario</option>
              <?php
              $usuariosStmt = $pdo->prepare("SELECT ID_USUARIO, NOMBRES, APELLIDOS FROM usuarios WHERE ID_USUARIO != ?");
              $usuariosStmt->execute([$id_usuario]);
              while ($usuario = $usuariosStmt->fetch()): ?>
                <option value="<?= $usuario['ID_USUARIO'] ?>">
                  <?= htmlspecialchars($usuario['NOMBRES'] . ' ' . $usuario['APELLIDOS']) ?>
                </option>
              <?php endwhile; ?>
            </select>
          </div>
          <div class="mb-3">
            <label for="contenido" class="form-label">Mensaje:</label>
            <textarea name="contenido" class="form-control" rows="4" placeholder="Escribe tu mensaje..." required></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Enviar</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        </div>
      </form>
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
<!-- Modal Redactar Mensaje -->
<div class="modal fade" id="modalRedactar" tabindex="-1" aria-labelledby="modalRedactarLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="bandeja_mensajes.php" method="POST">
        <div class="modal-header">
          <h5 class="modal-title" id="modalRedactarLabel">Nuevo mensaje</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="receptor_id" class="form-label">Para:</label>
            <select name="receptor_id" class="form-select" required>
              <option value="" selected disabled>Selecciona un usuario</option>
              <?php
              $usuariosStmt = $pdo->prepare("SELECT ID_USUARIO, NOMBRES, APELLIDOS FROM usuarios WHERE ID_USUARIO != ?");
              $usuariosStmt->execute([$id_usuario]);
              while ($usuario = $usuariosStmt->fetch()): ?>
                <option value="<?= $usuario['ID_USUARIO'] ?>">
                  <?= htmlspecialchars($usuario['NOMBRES'] . ' ' . $usuario['APELLIDOS']) ?>
                </option>
              <?php endwhile; ?>
            </select>
          </div>
          <div class="mb-3">
            <label for="contenido" class="form-label">Mensaje:</label>
            <textarea name="contenido" class="form-control" rows="4" placeholder="Escribe tu mensaje..." required></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Enviar</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  // Ocultar automáticamente la notificación después de 5 segundos
  setTimeout(() => {
    const alerta = document.querySelector('.notificacion-nuevos');
    if (alerta) alerta.remove();
  }, 5000);
</script>

</body>
</html>






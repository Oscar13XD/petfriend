<?php
session_start();
require_once __DIR__ . '/../db/config.php';

// Verificar si hay sesión activa
if (!isset($_SESSION['ID_USUARIO'])) {
  header("Location: ../login.php");
  exit();
}

$id_usuario = $_SESSION['ID_USUARIO'];
$mensaje = "";

// Actualizar datos personales
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['actualizar_perfil'])) {
  $stmt = $pdo->prepare("UPDATE usuarios SET NOMBRES = ?, APELLIDOS = ?, CIUDAD = ?, EDAD = ?, CORREO = ? WHERE ID_USUARIO = ?");
  $stmt->execute([
    $_POST['nombre'],
    $_POST['apellidos'],
    $_POST['ciudad'],
    $_POST['edad'],
    $_POST['correo'],
    $id_usuario
  ]);
  $mensaje = "Perfil actualizado correctamente.";
}

// Cambiar contraseña
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['cambiar_password'])) {
  $nueva = $_POST['nueva_password'];
  $confirmar = $_POST['confirmar_password'];

  if ($nueva === $confirmar && strlen($nueva) >= 8) {
    $stmt = $pdo->prepare("UPDATE usuarios SET CONTRASEÑA = ? WHERE ID_USUARIO = ?");
    $stmt->execute([$nueva, $id_usuario]);
    $mensaje = "Contraseña cambiada correctamente.";
  } else {
    $mensaje = "Error: Las contraseñas no coinciden o no cumplen con los requisitos.";
  }
}

// Obtener datos del usuario
$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE ID_USUARIO = ?");
$stmt->execute([$id_usuario]);
$usuario = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Perfil de Usuario</title>
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
  <!-- Main Content -->
  <div class="container-fluid p-5">
    <?php if ($mensaje): ?>
      <div class="alert alert-info"><?= $mensaje ?></div>
    <?php endif; ?>

    <div class="row">
      <!-- Formulario de Perfil -->
      <div class="col-md-7">
        <h4>Datos de Perfil</h4>
        <form method="POST">
          <input type="hidden" name="actualizar_perfil" value="1">
          <div class="mb-3">
            <label class="form-label">Nombres</label>
            <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($usuario['NOMBRES']) ?>">
          </div>
          <div class="mb-3">
            <label class="form-label">Apellidos</label>
            <input type="text" name="apellidos" class="form-control" value="<?= htmlspecialchars($usuario['APELLIDOS']) ?>">
          </div>
          <div class="mb-3">
            <label class="form-label">Ciudad</label>
            <input type="text" name="ciudad" class="form-control" value="<?= htmlspecialchars($usuario['CIUDAD']) ?>">
          </div>
          <div class="mb-3">
            <label class="form-label">Edad</label>
            <input type="number" name="edad" class="form-control" value="<?= htmlspecialchars($usuario['EDAD']) ?>">
          </div>
          <div class="mb-3">
            <label class="form-label">Correo</label>
            <input type="email" name="correo" class="form-control" value="<?= htmlspecialchars($usuario['CORREO']) ?>">
          </div>
          <button type="submit" class="btn btn-primary">Actualizar perfil</button>
        </form>
      </div>

      <!-- Formulario de Contraseña -->
      <div class="col-md-5">
        <h4>Restablecer contraseña</h4>
        <form method="POST">
          <input type="hidden" name="cambiar_password" value="1">
          <div class="mb-3">
            <label class="form-label">Nueva contraseña</label>
            <input type="password" name="nueva_password" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Confirmar contraseña</label>
            <input type="password" name="confirmar_password" class="form-control" required>
          </div>
          <button type="submit" class="btn btn-danger">Cambiar contraseña</button>
          <!-- Right: Reset password -->
           <h1></h1>
           <h4>PARA CAMBIAR TU CONTRASEÑA</h4>
        <ul class="password-rules">
          <li>Minimo 8 caracteres</li>
          <li>Debe tener una mayuscula</li>
          <li>Debe tener minimo 1 número</li>
          <li>Puede tener caracteres especiales</li>
        </ul>
      <div class="col-md-5">
        </form>
      </div>
    </div>
  </div>
</div>
</body>
</html>


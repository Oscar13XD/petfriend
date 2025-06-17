<?php
session_start();
require_once __DIR__ . '/../db/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = $_POST['correo'];
    $contrasena = $_POST['contrasena'];

    $sql = "SELECT * FROM usuarios WHERE CORREO = :correo AND CONTRASEÑA = :contrasena";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['correo' => $correo, 'contrasena' => $contrasena]);
    $usuario = $stmt->fetch();

    if ($usuario) {
        $_SESSION['ID_USUARIO'] = $usuario['ID_USUARIO'];
        $_SESSION['NOMBRE'] = $usuario['NOMBRES'];
        $_SESSION['ROL'] = $usuario['ROL'];

        if ($usuario['ROL'] === 'admin') {
            header("Location: admin_dashboard.php");
        } else {
            header("Location: inicio.php");
        }
        exit();
    } else {
        $error = "Correo o contraseña incorrectos";
    }
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login</title>
  <link rel="stylesheet" href="/petfriend2/css/login.css" />
</head>
<body>
  <div class="logo">
    <img src="../multimedia/imagenes/LOGOTIPO PET FRIEND.png" alt="LOGOTIPO" width="130px">
  </div>

  <div class="login-container">
    <h2>Iniciar sesión</h2>

    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

    <form action="" method="POST">
      <div>
        <label for="correo">Correo:</label>
        <input type="text" id="correo" name="correo" required />
      </div>
      <div>
        <label for="contrasena">Contraseña:</label>
        <input type="password" id="contrasena" name="contrasena" required />
      </div>
      <button type="submit">Ingresar</button>
    </form>

    <div class="d-flex justify-content-between">
      <p>¿No tienes cuenta?</p>
      <a href="index.php?page=register">REGISTRARSE</a>
    </div>
  </div>
</body>
</html>


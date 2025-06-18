<?php
session_start();
require_once __DIR__ . '/../db/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $correo = $_POST['correo'];
    $edad = $_POST['edad'];
    $identificacion = $_POST['documento'];
    $ciudad = $_POST['ciudad'];
    $contrasena = $_POST['contrasena']; // <-- SIN ENCRIPTAR
    $rol = 'usuario';

    try {
        $stmt = $pdo->prepare("INSERT INTO usuarios (NOMBRES, APELLIDOS, CORREO, IDENTIFICACION, EDAD, CIUDAD, CONTRASEÑA, ROL) 
                VALUES (:nombre, :apellido, :correo, :identificacion, :edad, :ciudad, :contrasena, :rol)");

        $stmt->execute([
            'nombre' => $nombre,
            'apellido' => $apellido,
            'correo' => $correo,
            'identificacion' => $identificacion,
            'edad' => $edad,
            'ciudad' => $ciudad,
            'contrasena' => $contrasena, // <-- TEXTO PLANO
            'rol' => $rol
        ]);

        echo '<script>alert("Registro exitoso. Serás redirigido para iniciar sesión."); window.location.href = "index.php?page=login";</script>';
        exit();
    } catch (PDOException $e) {
        die("Error al registrar: " . $e->getMessage());
    }
}

?>



<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registro</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="/petfriend2/css/registro.css">
</head>
<body> 
  <div class="container vh-100">
    <div class="row justify-content-center align-items-center h-100 pt-4">
      <div class="col-12 col-md-8 col-lg-6 col-xl-5">               
        <div class="card-body">
          <div class="card-header text-center">
            <h2>Registro</h2>
          </div>
          <form method="POST">
            <input class="form-control mb-2" type="text" name="nombre" placeholder="Nombre" required>
            <input class="form-control mb-2" type="text" name="apellido" placeholder="Apellido" required>
            <input class="form-control mb-2" type="email" name="correo" placeholder="Correo" required>
            <input class="form-control mb-2" type="number" name="edad" placeholder="Edad" required>
            <input class="form-control mb-2" type="text" name="documento" placeholder="Número Documento" required>
            <input class="form-control mb-2" type="text" name="ciudad" placeholder="Ciudad" required>
            <input class="form-control mb-2" type="password" name="contrasena" placeholder="Contraseña" required>
            <input class="form-control mb-2" type="password" name="confirmar" placeholder="Confirmar Contraseña" required>
            <button class="btn btn-primary w-100" type="submit">REGISTRARSE</button>
          </form>
          <div class="d-flex justify-content-between mt-2">
            <p>¿Ya tienes cuenta?</p>
            <a href="index.php?page=login">Iniciar sesión</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>





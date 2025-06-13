<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="./css/login.css">
</head>
<body>
    <div class="logo">
        <img src="./multimedia/imagenes/LOGOTIPO PET FRIEND.png" alt="LOGOTIPO" width="130px" > 
        <div class="login-container">
            <h2>Iniciar sesión</h2>
            <form action="src/index.html" method="POST">
            <div>
                <label for="username">Usuario:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div>
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Ingresar</button> </div>
            <div class="d-flex justify-content-between">
                <p>¿No tienes cuenta?</p>
                <a href="index.php?page=register">REGISTRARSE</a>
            </div>
         </form>
        </div>
    </div>
</body>
</head>
</html>
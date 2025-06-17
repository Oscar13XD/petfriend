<?php
// src/publicar.php
require_once '../db/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'];
    $contenido = $_POST['contenido'];
    $usuario_id = 1; // Usuario simulado

    // Subir imagen si existe
    $imagen = null;
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $imagen_nombre = basename($_FILES['imagen']['name']);
        $ruta_destino = '../uploads/' . $imagen_nombre;
        move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_destino);
        $imagen = $ruta_destino;
    }

    $sql = "INSERT INTO publicaciones (usuario_id, titulo, contenido, imagen) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$usuario_id, $titulo, $contenido, $imagen]);

    header("Location: Inicio.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Publicar</title>
    <link rel="stylesheet" href="../css/publicaciones.css">
</head>
<body>
    <h1>Crear publicación</h1>
    <form action="publicar.php" method="POST" enctype="multipart/form-data">
        <input type="text" name="titulo" placeholder="Título" required><br>
        <textarea name="contenido" placeholder="¿Qué deseas compartir?" required></textarea><br>
        <input type="file" name="imagen"><br>
        <button type="submit">Publicar</button>
    </form>
</body>
</html>

<?php
session_start();
require_once __DIR__ . '/../db/config.php';

if (!isset($_SESSION['ID_USUARIO'])) {
    header("Location: ../login.php");
    exit();
}

$emisor_id = $_SESSION['ID_USUARIO'];
$receptor_id = $_POST['receptor_id'] ?? null;
$contenido = trim($_POST['contenido'] ?? '');

if ($receptor_id && $contenido) {
    $stmt = $pdo->prepare("INSERT INTO mensajes (emisor_id, receptor_id, contenido) VALUES (:emisor, :receptor, :contenido)");
    $stmt->execute([
        'emisor' => $emisor_id,
        'receptor' => $receptor_id,
        'contenido' => $contenido
    ]);
    header("Location: ../perfil.php?id=$receptor_id&mensaje_enviado=1");
    exit();
} else {
    echo "Error: Datos incompletos.";
}
?>


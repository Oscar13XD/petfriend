<?php
session_start();
require_once __DIR__ . '/../db/config.php';

// Verificar autenticación
if (!isset($_SESSION['ID_USUARIO'])) {
  header("Location: login.php");
  exit();
}

$id_usuario = $_SESSION['ID_USUARIO'];

// Validar datos POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'], $_POST['estado'])) {
  $id_publicacion = (int)$_POST['id'];
  $nuevo_estado = trim($_POST['estado']);

  // Validar estado permitido
  $estados_permitidos = ['EN CURSO', 'CANCELADA', 'COMPLETADA'];
  if (!in_array($nuevo_estado, $estados_permitidos)) {
    die("Estado inválido.");
  }

  // Actualizar estado
  $stmt = $pdo->prepare("UPDATE publicaciones SET estado = :estado WHERE id = :id AND usuario_id = :uid");
  $stmt->execute([
    'estado' => $nuevo_estado,
    'id' => $id_publicacion,
    'uid' => $id_usuario
  ]);
}

// Redirigir de nuevo (mantener el filtro de estado si es posible)
header("Location: estado_publicaciones.php?estado=" . urlencode($nuevo_estado));
exit();
?>

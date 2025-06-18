<?php
session_start();
require_once '../db/config.php';

if (!isset($_SESSION['ID_USUARIO'])) {
  header("Location: ../index.php?page=login");
  exit();
}

$id_usuario = $_SESSION['ID_USUARIO'];

$stmt = $pdo->prepare("
  SELECT m.*, u.NOMBRES AS emisor_nombre 
  FROM mensajes m 
  JOIN usuarios u ON m.emisor_id = u.ID_USUARIO 
  WHERE m.receptor_id = :id 
  ORDER BY m.fecha DESC
");
$stmt->execute(['id' => $id_usuario]);
$mensajes = $stmt->fetchAll();
?>

<h3>📥 Bandeja de Entrada</h3>
<?php foreach ($mensajes as $mensaje): ?>
  <div class="card mb-2">
    <div class="card-body">
      <h6 class="card-subtitle mb-2 text-muted">De: <?= htmlspecialchars($mensaje['emisor_nombre']) ?> | <?= $mensaje['fecha'] ?></h6>
      <p class="card-text"><?= nl2br(htmlspecialchars($mensaje['contenido'])) ?></p>
    </div>
  </div>
<?php endforeach; ?>

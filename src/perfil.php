<?php
session_start();
include 'conexion.php';

$id_usuario = $_SESSION['ID_USUARIO'];

$user_query = "SELECT * FROM usuarios WHERE ID_USUARIO = $id_usuario";
$user_result = mysqli_query($conexion, $user_query);
$usuario = mysqli_fetch_assoc($user_result);

$post_query = "SELECT * FROM publicaciones WHERE usuario_id = $id_usuario ORDER BY fecha DESC";
$post_result = mysqli_query($conexion, $post_query);
?>

<div class="perfil">
  <h2><?= $usuario['NOMBRES'] . " " . $usuario['APELLIDOS'] ?></h2>
  <p><strong>Ciudad:</strong> <?= $usuario['CIUDAD'] ?></p>
  <p><strong>Biografía:</strong> Aquí puedes poner una descripción...</p>
  <img src="uploads/perfil/<?= $usuario['ID_USUARIO'] ?>.jpg" alt="Foto de perfil" class="img-thumbnail" width="150">

  <h3>Mis Publicaciones</h3>
  <?php while ($post = mysqli_fetch_assoc($post_result)) { ?>
    <div class="card mb-3">
      <div class="card-body">
        <h5><?= $post['titulo'] ?></h5>
        <p><?= $post['contenido'] ?></p>
        <?php if (!empty($post['imagen'])) { ?>
          <img src="uploads/<?= $post['imagen'] ?>" class="img-fluid" alt="Imagen publicación">
        <?php } ?>
      </div>
    </div>
  <?php } ?>
</div>


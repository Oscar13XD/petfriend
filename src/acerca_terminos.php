<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Términos y Condiciones - Pet Friend</title>
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
    <h3 class="mb-4">TÉRMINOS Y CONDICIONES</h3>
    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
    <p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
    <p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
    <p>Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
    <p>Al utilizar esta plataforma, usted acepta los términos mencionados anteriormente y se compromete a respetar nuestras condiciones de uso.</p>

    <div class="text-end mt-4">
      <button class="btn btn-primary">ACEPTO</button>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

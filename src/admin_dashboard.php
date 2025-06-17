<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel de Administrador - Pet Friend</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
    }
    .sidebar {
      height: 100vh;
      background-color: #343a40;
      color: white;
    }
    .sidebar a {
      color: white;
      display: block;
      padding: 15px;
      text-decoration: none;
    }
    .sidebar a:hover {
      background-color: #495057;
    }
    .content {
      padding: 30px;
    }
    .card {
      margin-bottom: 20px;
    }
  </style>
</head>
<body>
<div class="container-fluid">
  <div class="row">
    <nav class="col-md-2 d-none d-md-block sidebar">
      <div class="position-sticky">
        <h4 class="text-center my-3">Admin</h4>
        <a href="#usuarios">Usuarios</a>
        <a href="#publicaciones">Publicaciones</a>
        <a href="#adopciones">Adopciones</a>
        <a href="logout.php">Cerrar sesión</a>
      </div>
    </nav>

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 content">
      <h1 class="h2">Panel de Control</h1>
      <hr>

      <section id="usuarios">
        <h3>Usuarios Registrados</h3>
        <!-- Aquí se mostrará la tabla de usuarios -->
        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Ciudad</th>
              </tr>
            </thead>
            <tbody>
              <!-- Ejemplo -->
              <tr>
                <td>1</td>
                <td>Juan Morales</td>
                <td>juan@gmail.com</td>
                <td>Madrid</td>
              </tr>
            </tbody>
          </table>
        </div>
      </section>

      <section id="publicaciones">
        <h3>Últimas Publicaciones</h3>
        <!-- Aquí se mostrarán las publicaciones -->
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Título de publicación</h5>
            <p class="card-text">Contenido de la publicación...</p>
            <small class="text-muted">Publicado por: usuario@example.com</small>
          </div>
        </div>
      </section>

      <section id="adopciones">
        <h3>Solicitudes de Adopción</h3>
        <!-- Aquí se mostrarán las solicitudes -->
        <ul class="list-group">
          <li class="list-group-item">Solicitud #1 - EN CURSO - Madrid</li>
        </ul>
      </section>
    </main>
  </div>
</div>
</body>
</html>


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
            header("Location: src/admin_dashboard.php");
        } else {
            header("Location: src/inicio.php");
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
  <meta charset="UTF-8">
  <title>Pet Friend</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
   <link rel="stylesheet" href="../css/inicio.css">
</head>
<body>
<div class="d-flex">
  <!-- Sidebar -->
 <div id="sidebar" class="text-white p-3">
   <h4 id="titulo">Pet Friend</h4>
    <ul id="barra"class="nav flex-column mb-4"> 
      <li class="nav-item"><a class="nav-link text-white" href="#" onclick="mostrarSeccion('inicio', event)">Inicio</a></li>
        <li class="nav-item">
        <a class="nav-link text-white" href="perfil.php">Perfil</a></li>
      <li class="nav-item">
        <a class="nav-link text-white" data-bs-toggle="collapse" href="#submenuAdopciones" role="button" aria-expanded="false" aria-controls="submenuAdopciones">Adopciones</a>
        <div class="collapse ps-3" id="submenuAdopciones">
          <a class="nav-link text-white" href="#" onclick="mostrarSeccion('publicar', event)">Publicar</a>
          <a class="nav-link text-white" href="#" onclick="mostrarSeccion('estado', event)">Estado</a>
        </div>
      </li>
      <li class="nav-item"><a class="nav-link text-white" href="#" onclick="mostrarSeccion('configuracion', event)">Configuración</a></li>
      <li class="nav-item"><a class="nav-link text-white" href="#" onclick="mostrarSeccion('privacidad', event)">Privacidad</a></li>
      <li class="nav-item"><a class="nav-link text-white" href="#" onclick="mostrarSeccion('terminos', event)">Términos</a></li>
      <a class="nav-link text-white" href="logout.php">Cerrar sesión</a>

    </ul>
  </div>

  <!-- Contenido principal -->
  <div id="main-content" class="flex-grow-1">
    <button class="btn btn-sm btn-secondary m-3" onclick="toggleSidebar()">☰ Menú</button>
    <div class="container mt-3">

      <section id="inicio" class="seccion activa">
        <h2>Inicio</h2>
        <div class="card p-3 mt-3">
          <div class="d-flex align-items-center">
            <img src="https://placekitten.com/80/80" alt="Gatito" class="me-3 rounded-circle">
            <div>
              <strong>Usuario 1</strong>
              <p class="mb-0">Lorem ipsum dolor sit amet.</p>
            </div>
          </div>
        </div>
      </section>

      <section id="perfil" class="seccion">
        <h2>Perfil</h2>
        <p>Bienvenido a tu perfil. Aquí podrás ver y actualizar tu información personal, preferencias y actividad en la plataforma.</p>
      </section>

      <section id="publicar" class="seccion">
        <h2>Publicar Adopción</h2>
        <form>
           <div class="mb-3">
       <label for="nombreMascota" class="form-label">Nombre completo</label>
            <input type="text" class="form-control" id="nombreMascota" placeholder="">
      </div>
          <div class="mb-3">
            <label for="nombreMascota" class="form-label">Nombre de la mascota</label>
            <input type="text" class="form-control" id="nombreMascota" placeholder="Ej. Max">
          </div>
          <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea class="form-control" id="descripcion" rows="3"></textarea>
          </div>
          <div class="mb-3">
            <label for="imagenMascota" class="form-label">Subir imagen</label>
            <input class="form-control" type="file" id="imagenMascota">
          </div>
          <button type="submit" class="btn btn-primary">Publicar</button>
        </form>
      </section>

      <section id="configuracion" class="form-section seccion">
        <h3>Configuración de Usuario</h3>
        <form class="row g-3 mt-3">
          <h5>Información Personal</h5>
          <div class="col-md-6">
            <label for="first_name" class="form-label">Primer nombre</label>
            <input type="text" class="form-control" name="first_name" id="first_name">
          </div>
          <div class="col-md-6">
            <label for="middle_name" class="form-label">Segundo nombre</label>
            <input type="text" class="form-control" name="middle_name" id="middle_name">
          </div>
          <div class="col-md-6">
            <label for="last_name" class="form-label">Apellido</label>
            <input type="text" class="form-control" name="last_name" id="last_name">
          </div>
          <div class="col-md-6">
            <label for="email" class="form-label">Correo</label>
            <input type="email" class="form-control" name="email" id="email">
          </div>
          <div class="col-md-6">
            <label for="city" class="form-label">Ciudad</label>
            <input type="text" class="form-control" name="city" id="city">
          </div>
          <div class="col-md-6">
            <label for="doc_type" class="form-label">Tipo de documento</label>
            <select class="form-select" name="doc_type" id="doc_type">
              <option value="">Seleccione</option>
              <option value="C.C">C.C</option>
              <option value="T.I">T.I</option>
              <option value="C.E">C.E</option>
            </select>
          </div>
          <div class="col-md-6">
            <label for="doc_number" class="form-label">Número de documento</label>
            <input type="number" class="form-control" name="doc_number" id="doc_number">
          </div>
          <div class="col-md-6">
            <label for="celular" class="form-label">Celular</label>
            <input type="tel" class="form-control" name="celular" id="celular">
          </div>
          <div class="col-md-6">
            <label for="fechaNacimiento" class="form-label">Fecha de nacimiento</label>
            <input type="date" class="form-control" id="fechaNacimiento" name="fechaNacimiento">
          </div>
          <hr class="mt-4">
          <h5 class="mt-3">Cambiar Contraseña</h5>
          <div class="col-12">
            <div class="alert alert-info">
              <strong>Requisitos:</strong>
              <ul class="mb-0">
                <li>Mínimo 8 caracteres</li>
                <li>Al menos un número</li>
                <li>Al menos un carácter especial</li>
              </ul>
            </div>
          </div>
          <div class="col-md-6">
            <label for="new_password" class="form-label">Nueva contraseña</label>
            <input type="password" class="form-control" name="new_password" id="new_password">
          </div>
          <div class="col-md-6">
            <label for="confirm_password" class="form-label">Confirmar contraseña</label>
            <input type="password" class="form-control" name="confirm_password" id="confirm_password">
          </div>
          <div class="col-12 d-flex justify-content-end mt-4">
            <button type="submit" class="btn btn-primary me-2">Guardar Cambios</button>
            <button type="reset" class="btn btn-secondary">Cancelar</button>
          </div>
        </form>
      </section>

      <section id="estado" class="seccion">
        <h2>Estado de Publicaciones</h2>
        <div class="card mb-3">
          <div class="card-body">
            <h5 class="card-title">Publicación: Max</h5>
            <p class="card-text">Estado: <span class="badge bg-success">Publicado</span></p>
            <button class="btn btn-outline-primary btn-sm">Editar</button>
            <button class="btn btn-outline-danger btn-sm">Eliminar</button>
            <button class="btn btn-outline-secondary btn-sm">Compartir</button>
          </div>
        </div>
        <div class="card mb-3">
          <div class="card-body">
            <h5 class="card-title">Publicación: Luna</h5>
            <p class="card-text">Estado: <span class="badge bg-danger">Rechazada</span></p>
            <button class="btn btn-outline-primary btn-sm">Editar</button>
            <button class="btn btn-outline-danger btn-sm">Eliminar</button>
          </div>
        </div>
      </section>

      <section id="privacidad" class="seccion">
        <h2>Privacidad</h2>
        <p>Controla quién puede ver tu información y actividad en la plataforma. Ajusta tus configuraciones de privacidad según tus preferencias.</p>
      </section>

      <section id="terminos" class="seccion">
        <h2>Términos y Condiciones</h2>
        <p>Lee detenidamente nuestros términos y condiciones de uso antes de utilizar la plataforma. Al utilizarla, aceptas nuestras políticas.</p>
      </section>

    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  function toggleSidebar() {
    const sidebar = document.getElementById("sidebar");
    sidebar.classList.toggle("collapsed");
  }

  function mostrarSeccion(id, event) {
    event.preventDefault();
    document.querySelectorAll('.seccion').forEach(sec => sec.classList.remove('activa'));
    document.getElementById(id).classList.add('activa');
  }
</script>

</body>
</html>




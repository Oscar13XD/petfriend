<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Pet Friend</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="..\css\inicio.css">
  
</head>
<body>

<div class="d-flex">
  <!-- Sidebar -->
  <div id="sidebar" class="bg-dark text-white p-3">
    <h4>Pet Friend</h4>
    <ul class="nav flex-column mb-4">
      <li class="nav-item"><a class="nav-link text-white" href="#" onclick="mostrarSeccion('inicio', event)">Inicio</a></li>
      <li class="nav-item"><a class="nav-link text-white" href="#" onclick="mostrarSeccion('perfil', event)">Perfil</a></li>
      <li class="nav-item"><a class="nav-link text-white" href="#" onclick="mostrarSeccion('configuracion', event)">Configuración</a></li>
      <li class="nav-item"><a class="nav-link text-white" href="#" onclick="mostrarSeccion('adopciones', event)">Adopciones</a></li>
      <li class="nav-item"><a class="nav-link text-white" href="publiciones.html" onclick="mostrarSeccion('publicar', event)">Publicar</a></li>
      <li class="nav-item"><a class="nav-link text-white" href="#" onclick="mostrarSeccion('enlace', event)">Enlace</a></li>
      <li class="nav-item"><a class="nav-link text-white" href="#" onclick="mostrarSeccion('privacidad', event)">Privacidad</a></li>
      <li class="nav-item"><a class="nav-link text-white" href="#" onclick="mostrarSeccion('terminos', event)">Términos</a></li>
    </ul>
    <input type="text" class="form-control" placeholder="Buscar...">
  </div>

  <!-- Contenido principal -->
  <div id="main-content" class="flex-grow-1">
    <!-- Botón de ocultar menú -->
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

      <section id="perfil" class="form-section seccion">
        <h2>Perfil</h2>
        <p>Información del perfil del usuario, imagen de perfil, nombre de usuario, biografía, etc.</p>
      </section>

      <!-- Reemplazo de la sección CONFIGURACIÓN con tu versión completa -->
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

      <section id="adopciones" class="form-section seccion">
        <h2>Adopciones</h2>
        <p>Listado de animales disponibles para adopción con fotos, nombres y enlaces.</p>
      </section>

      <section id="publicar" class="form-section seccion">
        <h2>Publicar</h2>
        <form>
          <div class="mb-3">
            <label for="titulo" class="form-label">Título del anuncio:</label>
            <input type="text" class="form-control" id="titulo" placeholder="Ej. Gato en adopción">
          </div>
          <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción:</label>
            <textarea class="form-control" id="descripcion" rows="3"></textarea>
          </div>
          <div class="mb-3">
            <label for="imagen" class="form-label">Subir imagen:</label>
            <input type="file" class="form-control" id="imagen">
          </div>
          <button type="submit" class="btn btn-success">Publicar</button>
        </form>
      </section>

      <section id="enlace" class="form-section seccion">
        <h2>Enlace</h2>
        <p>Sección para vincular otras plataformas o compartir en redes sociales.</p>
      </section>

      <section id="privacidad" class="form-section seccion">
        <h2>Privacidad</h2>
        <p>Política de privacidad simulada: recopilamos tu nombre y correo para personalizar tu experiencia.</p>
      </section>

      <section id="terminos" class="form-section seccion">
        <h2>Términos y Condiciones</h2>
        <p>Uso simulado de la plataforma, reglas de publicación y condiciones generales.</p>
      </section>

    </div>
  </div>
</div>

<!-- Scripts -->
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


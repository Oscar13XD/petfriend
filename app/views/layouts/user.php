<?php
function isActive($route, $currentUrl)
{
    return $currentUrl === $route ? 'active' : '';
}
?>

<link rel="stylesheet" href="/petfriend/public/css/inicio.css">

<div class="d-flex">
    <!-- Sidebar -->
    <div id="sidebar" class="text-white p-3">
        <h4 id="titulo">Pet Friend</h4>
        <ul id="barra" class="nav flex-column mb-4">
            <li class="nav-item"><a class="nav-link text-white <?= isActive('user', $currentUrl) ?>" href="/petfriend/public/user">Inicio</a></li>
            <li class="nav-item">
                <a class="nav-link text-white <?= isActive('user/profile', $currentUrl) ?>" href="/petfriend/public/user/profile">Perfil</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" data-bs-toggle="collapse" href="#submenuAdopciones" role="button" aria-expanded="false" aria-controls="submenuAdopciones">Adopciones</a>
                <div class="collapse ps-3" id="submenuAdopciones">
                    <a class="nav-link text-white" href="publicar.php">Publicar</a>
                    <a class="nav-link text-white" href="estado_publicaciones.php">Estado</a>
                </div>
            </li>
            <li class="nav-item"><a class="nav-link text-white" href="bandeja_mensajes.php">Mensajes</a></li>

            <li class="nav-item"><a class="nav-link text-white" href="configuracion.php">Configuración</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="acerca_terminos.php">Términos</a></li>
            <a class="nav-link text-white" href="/petfriend/public/auth/logout">Cerrar sesión</a>
        </ul>
    </div>

    <!-- Contenido principal -->
    <div id="main-content" class="flex-grow-1">
        <button class="btn btn-sm btn-secondary m-3" onclick="toggleSidebar()">☰ Menú</button>
        <div id="main-content" class="flex-grow-1">
            <div class="container py-4">
                <?= $viewContent ?>
            </div>
        </div>
    </div>
</div>

<script>
    const toggleSidebar = () => {
        const sidebar = document.getElementById("sidebar");
        sidebar.classList.toggle("collapsed");
    }

    const mostrarSeccion = (id, event) => {
        event.preventDefault();
        document.querySelectorAll('.seccion').forEach(sec => sec.classList.remove('activa'));
        document.getElementById(id).classList.add('activa');
    }
</script>
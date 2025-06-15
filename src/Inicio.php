<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio - Mini Facebook</title>
    <link rel="stylesheet" href="../css/inicio1.css">
</head>
<body>
    <div class="sidebar">
        <h2>Pet Friend</h2>
        <a href="#" class="active" onclick="mostrarSeccion('inicio')">Inicio</a>
        <a href="#" onclick="mostrarSeccion('perfil')">Perfil</a>
        <a href="#" onclick="mostrarSeccion('configuracion')">Configuración</a>
        <a href="#" onclick="mostrarSeccion('adopciones')">Adopciones</a>
        <a href="#" onclick="mostrarSeccion('publicar')">Publicar</a>
        <a href="#" onclick="mostrarSeccion('enlace')">Enlace</a>
        <a href="#" onclick="mostrarSeccion('privacidad')">Política de privacidad</a>
        <a href="#" onclick="mostrarSeccion('terminos')">Términos y condiciones</a>
        <div class="search-box">
            <input type="text" placeholder="Buscar...">
        </div>
    </div>
    <div class="content">
        <div id="inicio" class="seccion activa">
            <div class="post">
                <img src="https://placekitten.com/100/100" alt="Gatito">
                <div class="post-content">
                    <strong>Nombre del usuario</strong>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin nec ultrices justo. Integer blandit.</p>
                    <div class="post-buttons">
                        <button class="btn-dislike">No me gusta</button>
                        <button class="btn-like">Me gusta</button>
                    </div>
                </div>
            </div>
            <div class="post">
                <img src="https://place-puppy.com/100x100" alt="Perrito">
                <div class="post-content">
                    <strong>Nombre del usuario</strong>
                    <p>Aliquam erat volutpat. Maecenas ut nisi nec sapien porta efficitur. Vestibulum ante ipsum primis.</p>
                    <div class="post-buttons">
                        <button class="btn-dislike">No me gusta</button>
                        <button class="btn-like">Me gusta</button>
                    </div>
                </div>
            </div>
        </div>

        <div id="perfil" class="seccion">
            <div class="section">
                <div class="title">Publicaciones</div>
                <div class="post-box">
                    <textarea rows="3" placeholder="¿Qué estás pensando?"></textarea>
                </div>
            </div>

            <div class="section">
                <div class="title">Información</div>
                <div class="info-item">Vive en: <strong>Facatativá</strong></div>
                <div class="info-item">De: <strong>Facatativá</strong></div>
            </div>
        </div>
    </div>

    <script>
        function mostrarSeccion(id) {
            const secciones = document.querySelectorAll('.seccion');
            secciones.forEach(sec => sec.classList.remove('activa'));
            document.getElementById(id).classList.add('activa');

            document.querySelectorAll('.sidebar a').forEach(a => a.classList.remove('active'));
            event.target.classList.add('active');
        }
    </script>
</body>
</html>

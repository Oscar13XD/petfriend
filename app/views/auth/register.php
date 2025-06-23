<link rel="stylesheet" href="/petfriend/public/css/registro.css">

<div class="container vh-100">
    <div class="row justify-content-center align-items-center h-100 pt-4">
        <div class="col-12 col-md-8 col-lg-6 col-xl-5">
            <div class="card-body">
                <div class="card-header text-center">
                    <h2>Registro</h2>
                </div>
                <form id="registerForm">
                    <input class="form-control mb-2" type="text" name="nombre" placeholder="Nombre" required>
                    <input class="form-control mb-2" type="text" name="apellido" placeholder="Apellido" required>
                    <input class="form-control mb-2" type="email" name="correo" placeholder="Correo" required>
                    <input class="form-control mb-2" type="number" name="edad" placeholder="Edad" required>
                    <input class="form-control mb-2" type="text" name="documento" placeholder="Número Documento" required>
                    <input class="form-control mb-2" type="text" name="ciudad" placeholder="Ciudad" required>
                    <input class="form-control mb-2" type="password" name="contrasena" placeholder="Contraseña" required>
                    <input id="confirmar" class="form-control mb-2" type="password" placeholder="Confirmar Contraseña" required>
                    <button class="btn btn-primary w-100" type="submit">REGISTRARSE</button>
                </form>
                <div class="d-flex justify-content-between mt-2">
                    <p>¿Ya tienes cuenta?</p>
                    <a href="/petfriend/public/auth/login">Iniciar sesión</a>
                </div>
                <div id="mensaje" class="mt-2"></div>
            </div>
        </div>
    </div>
</div>

<script>
    $('#registerForm').on('submit', function(e) {
        e.preventDefault();

        const $mensaje = $('#mensaje');
        const pass = $('input[name="contrasena"]').val();
        const confirm = $('#confirmar').val();

        if (pass !== confirm) {
            $mensaje.text('Las contraseñas no coinciden.').addClass('text-danger');
            return;
        }

        $mensaje.text('Cargando...').addClass('text-black');

        $.ajax({
            url: '/petfriend/public/auth/createUser',
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(res) {
                $mensaje.text(res.message).addClass(res.success ? 'text-success' : 'text-danger');
                if (res.success) {
                    $('#registerForm')[0].reset();
                    setTimeout(() => {
                        window.location.href = '/petfriend/public/auth/login';
                    }, 2000);
                }
            },
            error: function() {
                $mensaje.text('Error en el servidor.').addClass('text-danger');
            }
        });
    });
</script>
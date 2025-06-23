<link rel="stylesheet" href="/petfriend/public/css/login.css">

<div class="logo">
    <img src="/petfriend/public/img/LOGOTIPO PET FRIEND.png" alt="LOGOTIPO" width="130px">
</div>

<div class="login-container">
    <h2>Iniciar sesión</h2>

    <form id="loginForm">
        <div>
            <label for="correo">Correo:</label>
            <input type="text" id="correo" name="correo" required />
        </div>
        <div>
            <label for="contrasena">Contraseña:</label>
            <input type="password" id="contrasena" name="contrasena" required />
        </div>
        <button type="submit">Ingresar</button>
    </form>

    <div class="d-flex justify-content-between">
        <p>¿No tienes cuenta?</p>
        <a href="/petfriend/public/auth/register">REGISTRARSE</a>
    </div>

    <div id="mensaje" class="mt-2"></div>

    <script>
        $('#loginForm').on('submit', function(e) {
            e.preventDefault();

            const $mensaje = $('#mensaje');
            $mensaje.text('Validando...').addClass('text-black');

            $.ajax({
                url: '/petfriend/public/auth/loginUser',
                method: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(res) {
                    $mensaje.text(res.message).addClass(res.success ? 'text-success' : 'text-danger');
                    if (res.success) {
                        setTimeout(() => {
                            window.location.href = '/petfriend/public/home';
                        }, 2000);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error AJAX:', error);
                    console.log('Status:', status);
                    console.log('ResponseText:', xhr.responseText)
                    $mensaje.text('Error al procesar solicitud.').addClass('text-danger');
                }
            });
        });
    </script>
</div>
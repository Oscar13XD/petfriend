
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
  <link rel="stylesheet" href="/petfriend2/css/registro.css">
</head>
<body> 
    <div class="container vh-100">
        <div class="row justify-content-center align-items-center h-100 pt-4">
            <div class="col-12 col-md-8 col-lg-6 col-xl-5">               
                    <div class="card-body">
                        <div class="card-header text-center">
                        Registro
                    </div>
                        <form class="row g-2" id="registrar">
                            <div class="col-12">
                                <label for="nombre" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="nombre" name="nombre">
                            </div>
                            <div class="col-12">
                                <label for="apellido" class="form-label">Apellido</label>
                                <input type="text" class="form-control" id="apellido">
                            </div>
                            <div class="col-12">
                                <label for="correo" class="form-label">Correo</label>
                                <input type="email" class="form-control" id="correo">
                            </div>
                            <div class="col-12">
                                <label for="fechaNacimiento" class="form-label">Fecha de nacimiento</label>
                                <input type="date" class="form-control" id="fechaNacimiento">
                            </div>
                            <div class="col-12">
                                <label for="documento" class="form-label"># Documento</label>
                                <input type="number" class="form-control" id="documento">
                            </div>
                            <div class="col-12">
                                <label for="ciudad" class="form-label">Ciudad</label>
                                <input type="text" class="form-control" id="ciudad">
                            </div>
                            <div class="col-12">
                                <label for="celular" class="form-label"># celular</label>
                                <input type="number" class="form-control" id="celular">
                            </div>
                            <div class="col-12">
                                <label for="password" class="form-label">Contraseña</label>
                                <input type="password" class="form-control" id="password">
                            </div>
                            <div class="col-12">
                                <label for="passwordC" class="form-label">Confirmar Contraseña</label>
                                <input type="password" class="form-control" id="passwordC">
                            </div>
                            <div class="col-12">
                                <button class="btn btn-primary mt-2 w-100">REGISTRARSE</button>
                            </div>
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <p>¿Ya tienes cuenta?</p>
                                    <a href="index.php?page=login">Iniciar sesion</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
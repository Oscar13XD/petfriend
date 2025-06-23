<?php
class UserController extends Controller
{
    public function index()
    {
        $this->validateSession('usuario');

        $this->view('user/dashboard', [
            'NOMBRE' => $_SESSION['NOMBRE'],
            'title' => 'Inicio - Pet Friend'
        ], 'layouts/user');
    }

    public function profile()
    {
        $this->validateSession('usuario');

        $userModel = $this->model('User');
        $postModel = $this->model('Post');
        $user = $userModel->getById($_SESSION['ID_USUARIO']);
        $posts = $postModel->getByUser($_SESSION['ID_USUARIO'], null, "COMPLETADA");


        $this->view('user/profile', [
            'usuario' => $user,
            'publicaciones' => $posts,
            'title' => 'Mi perfil - Pet Friend'
        ], 'layouts/user');
    }

    public function updatePhoto()
    {
        $this->validateSession('usuario');

        $id = $_SESSION['ID_USUARIO'];

        if (!isset($_FILES['nueva_foto']) || $_FILES['nueva_foto']['error'] !== UPLOAD_ERR_OK) {
            http_response_code(400);
            echo "Archivo inválido";
            return;
        }

        $archivo = $_FILES['nueva_foto'];
        $ext = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
        $nombreArchivo = $id . '.' . $ext;
        $rutaDestino = "../public/uploads/perfiles/" . $nombreArchivo;

        if (!move_uploaded_file($archivo['tmp_name'], $rutaDestino)) {
            http_response_code(500);
            echo "Error al mover el archivo.";
            return;
        }

        $this->model('User')->updateFoto($id, $nombreArchivo);
        echo "ok:" . $nombreArchivo;
    }
}

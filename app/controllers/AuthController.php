<?php
class AuthController extends Controller
{
    public function login()
    {
        $this->redirectIfLoggedIn();

        $this->view('auth/login', ['title' => 'Iniciar sesión']);
    }

    public function register()
    {
        $this->redirectIfLoggedIn();

        $this->view('auth/register', ['title' => 'Registrarse']);
    }

    public function createUser()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nombre'     => $_POST['nombre'] ?? '',
                'apellido'   => $_POST['apellido'] ?? '',
                'correo'     => $_POST['correo'] ?? '',
                'edad'       => $_POST['edad'] ?? '',
                'documento'  => $_POST['documento'] ?? '',
                'ciudad'     => $_POST['ciudad'] ?? '',
                'contrasena' => $_POST['contrasena'] ?? '',
            ];

            // Asignar rol por lógica de backend
            $data['rol'] = 'usuario';

            // Validación básica
            foreach ($data as $campo => $valor) {
                if (empty($valor)) {
                    echo json_encode(['success' => false, 'message' => "Campo '$campo' es obligatorio"]);
                    return;
                }
            }

            // Hashear la contraseña
            $data['contrasena'] = password_hash($data['contrasena'], PASSWORD_DEFAULT);

            // Guardar con el modelo
            $userModel = $this->model('User');
            $resultado = $userModel->create($data);

            if ($resultado) {
                echo json_encode(['success' => true, 'message' => 'Registro exitoso. Serás redirigido para iniciar sesión.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al registrar usuario']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
        }
    }

    public function loginUser()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $correo = $_POST['correo'] ?? '';
            $contrasena = $_POST['contrasena'] ?? '';

            if (empty($correo) || empty($contrasena)) {
                echo json_encode(['success' => false, 'message' => 'Correo y contraseña requeridos']);
                return;
            }

            $userModel = $this->model('User');
            $usuario = $userModel->findByCorreo($correo);

            if (!$usuario || !password_verify($contrasena, $usuario['CONTRASEÑA'])) {
                echo json_encode(['success' => false, 'message' => 'Correo o contraseña incorrectos']);
                return;
            }

            // Iniciar sesión
            session_start();
            $_SESSION['ID_USUARIO'] = $usuario['ID_USUARIO'];
            $_SESSION['NOMBRE'] = $usuario['NOMBRES'];
            $_SESSION['ROL'] = $usuario['ROL'];

            echo json_encode(['success' => true, 'message' => 'Inicio de sesión exitoso']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
        }
    }

    public function logout()
    {
        session_start();
        session_destroy();
        header('Location: /petfriend/public/auth/login');
        exit;
    }
}

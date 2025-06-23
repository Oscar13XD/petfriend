<?php
class Controller
{
    public function model($model)
    {
        require_once "../app/models/$model.php";
        return new $model;
    }

    public function view($view, $data = [], $layout = null)
    {
        // Extraer variables para uso dentro de las vistas
        extract($data);

        // URL actual
        $currentUrl = $_GET['url'] ?? 'home';

        // Capturar el contenido de la vista
        ob_start();
        require_once "../app/views/$view.php";
        $viewContent = ob_get_clean();

        if ($layout) {
            ob_start();
            require_once "../app/views/{$layout}.php";
            $content = ob_get_clean();
        } else {
            $content = $viewContent;
        }

        // Incluir el layout principal
        require_once "../app/views/layouts/main.php";
    }

    protected function validateSession($requiredRole = null)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['ID_USUARIO'])) {
            header('Location: /petfriend/public/auth/login');
            exit;
        }

        if ($requiredRole && $_SESSION['ROL'] !== $requiredRole) {
            header('Location: /petfriend/public/auth/login');
            exit;
        }
    }

    protected function redirectIfLoggedIn()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION['ROL'])) {
            if ($_SESSION['ROL'] === 'usuario') {
                header('Location: /petfriend/public/user');
                exit;
            } elseif ($_SESSION['ROL'] === 'admin') {
                header('Location: /petfriend/public/admin');
                exit;
            }
        }
    }
}

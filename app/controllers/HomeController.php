<?php
class HomeController extends Controller
{
    public function index()
    {
        session_start();

        if (!isset($_SESSION['ID_USUARIO'])) {
            header('Location: /petfriend/public/auth/login');
            exit;
        }


        if ($_SESSION['ROL'] === 'admin') {
            header('Location: /petfriend/public/admin/dashboard');
        } else {
            header('Location: /petfriend/public/user');
        }

        exit;
    }
}

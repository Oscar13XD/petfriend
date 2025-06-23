<?php
class AdminController extends Controller
{
    public function dashboard()
    {
        session_start();

        if ($_SESSION['ROL'] !== 'admin') {
            header('Location: /petfriend/public/auth/login');
            exit;
        }

        $this->view('admin/dashboard', [
            'NOMBRE' => $_SESSION['NOMBRE']
        ]);
    }

    public function index()
    {
        session_start();

        if ($_SESSION['ROL'] !== 'admin') {
            header('Location: /petfriend/public/auth/login');
            exit;
        }

        $this->view('admin/dashboard', [
            'NOMBRE' => $_SESSION['NOMBRE']
        ]);
    }
}

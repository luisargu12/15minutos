<?php
namespace App\Core;

class Auth
{
    public static function requerirSesion(): void
    {
        if (empty($_SESSION['id_usuario'])) {
            header('Location: /15minutos/admin/login');
            exit;
        }
    }

    public static function estaAutenticado(): bool
    {
        return !empty($_SESSION['id_usuario']);
    }

    public static function cerrarSesion(): void
    {
        session_destroy();
    }
}

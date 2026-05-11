<?php
namespace App\Controllers;

use App\Models\Usuario;

class AuthController
{
    /**
     * POST api/auth/login
     * Recibe: { usuario, password }
     * Devuelve: { exito, mensaje }
     */
    public function login(): array
    {
        $datos = json_decode(file_get_contents('php://input'), true) ?? [];

        $usuarioInput  = trim($datos['usuario']  ?? '');
        $passwordInput = trim($datos['password'] ?? '');

        if (empty($usuarioInput) || empty($passwordInput)) {
            return ['exito' => false, 'mensaje' => 'Usuario y contraseña son requeridos.'];
        }

        $registro = Usuario::buscarPorUsuario($usuarioInput);

        if (!$registro) {
            return ['exito' => false, 'mensaje' => 'Usuario o contraseña incorrectos.'];
        }

        if ((int) $registro['status'] !== 1) {
            return ['exito' => false, 'mensaje' => 'Tu cuenta está inactiva. Contacta al administrador.'];
        }

        if (!password_verify($passwordInput, $registro['password'])) {
            return ['exito' => false, 'mensaje' => 'Usuario o contraseña incorrectos.'];
        }

        // Iniciar sesión
        $_SESSION['id_usuario']   = $registro['id_usuario'];
        $_SESSION['nombre']       = $registro['nombre'];
        $_SESSION['usuario']      = $registro['usuario'];
        $_SESSION['tipo_usuario'] = $registro['tipo_usuario'];

        return ['exito' => true, 'mensaje' => 'Bienvenido, ' . $registro['nombre'] . '.'];
    }

    /**
     * POST api/auth/logout
     */
    public function logout(): array
    {
        session_destroy();
        return ['exito' => true];
    }
}

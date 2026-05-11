<?php
namespace App\Controllers;

use App\Models\Usuario;

class UsuarioController
{
    /**
     * POST api/usuarios/guardar
     * Recibe: { nombre, usuario, password, tipo_usuario }
     * Devuelve: { exito, mensaje, id? }
     */
    public function guardar(): array
    {
        $datos = json_decode(file_get_contents('php://input'), true) ?? [];

        $nombre      = trim($datos['nombre']      ?? '');
        $usuario     = trim($datos['usuario']     ?? '');
        $password    = trim($datos['password']    ?? '');
        $tipoUsuario = (int) ($datos['tipo_usuario'] ?? 2);

        if (empty($nombre) || empty($usuario) || empty($password)) {
            return ['exito' => false, 'mensaje' => 'Nombre, usuario y contraseña son requeridos.'];
        }

        if (strlen($password) < 6) {
            return ['exito' => false, 'mensaje' => 'La contraseña debe tener al menos 6 caracteres.'];
        }

        // Verificar que el usuario no exista ya
        $existente = Usuario::buscarPorUsuario($usuario);
        if ($existente) {
            return ['exito' => false, 'mensaje' => "El usuario '$usuario' ya existe."];
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);
        $id   = Usuario::crear($nombre, $usuario, $hash, $tipoUsuario);

        return ['exito' => true, 'mensaje' => 'Usuario creado correctamente.', 'id' => $id];
    }

    /**
     * GET api/usuarios/listar
     */
    public function listar(): array
    {
        $usuarios = Usuario::listar();
        return ['exito' => true, 'datos' => $usuarios];
    }
}

<?php
namespace App\Models;

use App\Config\Database;
use PDO;

class Usuario
{
    /**
     * Busca un usuario por su nombre de usuario.
     */
    public static function buscarPorUsuario(string $usuario): array|false
    {
        $pdo  = Database::conectar();
        $stmt = $pdo->prepare(
            "SELECT id_usuario, nombre, usuario, password, tipo_usuario, status
             FROM tab_usuarios
             WHERE usuario = :usuario
             LIMIT 1"
        );
        $stmt->execute([':usuario' => $usuario]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Crea un nuevo usuario.
     * La contraseña debe llegar ya hasheada con password_hash().
     */
    public static function crear(string $nombre, string $usuario, string $passwordHash, int $tipoUsuario = 2): int
    {
        $pdo  = Database::conectar();
        $stmt = $pdo->prepare(
            "INSERT INTO tab_usuarios (nombre, usuario, password, tipo_usuario, status)
             VALUES (:nombre, :usuario, :password, :tipo_usuario, 1)"
        );
        $stmt->execute([
            ':nombre'       => $nombre,
            ':usuario'      => $usuario,
            ':password'     => $passwordHash,
            ':tipo_usuario' => $tipoUsuario,
        ]);
        return (int) $pdo->lastInsertId();
    }

    /**
     * Lista todos los usuarios activos.
     */
    public static function listar(): array
    {
        $pdo  = Database::conectar();
        $stmt = $pdo->query(
            "SELECT id_usuario, nombre, usuario, tipo_usuario, status
             FROM tab_usuarios
             ORDER BY nombre ASC"
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

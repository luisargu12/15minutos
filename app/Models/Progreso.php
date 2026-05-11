<?php
namespace App\Models;

use App\Config\Database;
use PDO;

class Progreso
{
    /** ¿El usuario ya completó su ejercicio hoy? */
    public static function hechoHoy(int $idUsuario): bool
    {
        $pdo  = Database::conectar();
        $stmt = $pdo->prepare(
            "SELECT id_progreso FROM tab_progreso
             WHERE id_usuario = :id AND fecha = CURDATE() AND completado = 1
             LIMIT 1"
        );
        $stmt->execute([':id' => $idUsuario]);
        return (bool) $stmt->fetch();
    }

    /** Días completados en un mes/año específico (array de enteros, ej. [1,3,7,15]) */
    public static function diasDelMes(int $idUsuario, int $year, int $month): array
    {
        $pdo  = Database::conectar();
        $stmt = $pdo->prepare(
            "SELECT DAY(fecha) AS dia FROM tab_progreso
             WHERE id_usuario = :id
               AND YEAR(fecha)  = :y
               AND MONTH(fecha) = :m
               AND completado   = 1"
        );
        $stmt->execute([':id' => $idUsuario, ':y' => $year, ':m' => $month]);
        return array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'dia');
    }

    /** Total de ejercicios completados en el mes actual */
    public static function completadosMesActual(int $idUsuario): int
    {
        $pdo  = Database::conectar();
        $stmt = $pdo->prepare(
            "SELECT COUNT(*) FROM tab_progreso
             WHERE id_usuario = :id
               AND YEAR(fecha)  = YEAR(CURDATE())
               AND MONTH(fecha) = MONTH(CURDATE())
               AND completado   = 1"
        );
        $stmt->execute([':id' => $idUsuario]);
        return (int) $stmt->fetchColumn();
    }

    /** Total histórico de ejercicios completados */
    public static function totalCompletados(int $idUsuario): int
    {
        $pdo  = Database::conectar();
        $stmt = $pdo->prepare(
            "SELECT COUNT(*) FROM tab_progreso WHERE id_usuario = :id AND completado = 1"
        );
        $stmt->execute([':id' => $idUsuario]);
        return (int) $stmt->fetchColumn();
    }

    /** Racha actual de días consecutivos */
    public static function rachaActual(int $idUsuario): int
    {
        $pdo  = Database::conectar();
        $stmt = $pdo->prepare(
            "SELECT fecha FROM tab_progreso
             WHERE id_usuario = :id AND completado = 1
             ORDER BY fecha DESC"
        );
        $stmt->execute([':id' => $idUsuario]);
        $fechas = array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'fecha');

        if (empty($fechas)) return 0;

        $racha    = 0;
        $esperado = new \DateTime();
        $esperado->setTime(0, 0, 0);

        foreach ($fechas as $f) {
            $fecha = new \DateTime($f);
            $fecha->setTime(0, 0, 0);
            if ($esperado->diff($fecha)->days === 0) {
                $racha++;
                $esperado->modify('-1 day');
            } else {
                break;
            }
        }
        return $racha;
    }

    /** Registra ejercicio completado hoy (inserta o actualiza) */
    public static function marcar(int $idUsuario, int $idRutina): bool
    {
        $pdo = Database::conectar();

        // ¿Ya existe fila para hoy?
        $check = $pdo->prepare(
            "SELECT id_progreso FROM tab_progreso
             WHERE id_usuario = :id AND fecha = CURDATE() LIMIT 1"
        );
        $check->execute([':id' => $idUsuario]);
        $existing = $check->fetch(PDO::FETCH_ASSOC);

        if ($existing) {
            $stmt = $pdo->prepare(
                "UPDATE tab_progreso SET completado = 1
                 WHERE id_progreso = :idp"
            );
            return $stmt->execute([':idp' => $existing['id_progreso']]);
        }

        $stmt = $pdo->prepare(
            "INSERT INTO tab_progreso (id_usuario, id_rutina, fecha, completado)
             VALUES (:idu, :idr, CURDATE(), 1)"
        );
        return $stmt->execute([':idu' => $idUsuario, ':idr' => $idRutina]);
    }
}

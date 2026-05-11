<?php
namespace App\Controllers;

use App\Config\Database;
use App\Core\Auth;
use PDO;

class RutinaController
{
    // ══════════════════════════════════════════
    //  RUTINAS
    // ══════════════════════════════════════════

    public function listar(): array
    {
        Auth::requerirSesion();
        $pdo  = Database::conectar();
        $stmt = $pdo->query(
            "SELECT r.*,
                    COUNT(re.id) AS num_ejercicios
             FROM tab_rutinas r
             LEFT JOIN tab_rutina_ejercicios re ON re.id_rutina = r.id_rutina
             GROUP BY r.id_rutina
             ORDER BY r.activo DESC, r.id_rutina DESC"
        );
        return ['exito' => true, 'datos' => $stmt->fetchAll(PDO::FETCH_ASSOC)];
    }

    public function guardarRutina(): array
    {
        Auth::requerirSesion();
        $d = json_decode(file_get_contents('php://input'), true) ?? [];

        $nombre   = trim($d['nombre']             ?? '');
        $desc     = trim($d['descripcion']        ?? '');
        $duracion = (int) ($d['duracion_total_seg'] ?? 900);
        $id       = (int) ($d['id_rutina']          ?? 0);

        if (empty($nombre)) {
            return ['exito' => false, 'mensaje' => 'El nombre es requerido.'];
        }

        $pdo = Database::conectar();

        if ($id > 0) {
            $stmt = $pdo->prepare(
                "UPDATE tab_rutinas SET nombre=:n, descripcion=:d, duracion_total_seg=:dur
                 WHERE id_rutina=:id"
            );
            $stmt->execute([':n' => $nombre, ':d' => $desc, ':dur' => $duracion, ':id' => $id]);
            return ['exito' => true, 'mensaje' => 'Rutina actualizada.', 'id' => $id];
        }

        $stmt = $pdo->prepare(
            "INSERT INTO tab_rutinas (nombre, descripcion, duracion_total_seg, activo)
             VALUES (:n, :d, :dur, 0)"
        );
        $stmt->execute([':n' => $nombre, ':d' => $desc, ':dur' => $duracion]);
        return ['exito' => true, 'mensaje' => 'Rutina creada correctamente.', 'id' => (int) $pdo->lastInsertId()];
    }

    public function activarRutina(): array
    {
        Auth::requerirSesion();
        $d  = json_decode(file_get_contents('php://input'), true) ?? [];
        $id = (int) ($d['id_rutina'] ?? 0);

        if (!$id) return ['exito' => false, 'mensaje' => 'ID inválido.'];

        $pdo = Database::conectar();
        $pdo->exec("UPDATE tab_rutinas SET activo = 0");
        $stmt = $pdo->prepare("UPDATE tab_rutinas SET activo = 1 WHERE id_rutina = :id");
        $stmt->execute([':id' => $id]);

        return ['exito' => true, 'mensaje' => '✅ Rutina activada. Es la rutina del día.'];
    }

    public function eliminarRutina(): array
    {
        Auth::requerirSesion();
        $d  = json_decode(file_get_contents('php://input'), true) ?? [];
        $id = (int) ($d['id_rutina'] ?? 0);

        if (!$id) return ['exito' => false, 'mensaje' => 'ID inválido.'];

        $pdo  = Database::conectar();
        $stmt = $pdo->prepare("DELETE FROM tab_rutinas WHERE id_rutina = :id");
        $stmt->execute([':id' => $id]);

        return ['exito' => true, 'mensaje' => 'Rutina eliminada.'];
    }

    // ══════════════════════════════════════════
    //  EJERCICIOS DE UNA RUTINA (pivote)
    // ══════════════════════════════════════════

    public function detalleRutina(): array
    {
        Auth::requerirSesion();
        $id = (int) ($_GET['id'] ?? 0);
        if (!$id) return ['exito' => false, 'mensaje' => 'ID inválido.'];

        $pdo  = Database::conectar();
        $stmt = $pdo->prepare(
            "SELECT re.id, re.orden, re.duracion_seg, re.descanso_seg,
                    e.id_ejercicio, e.nombre, e.lottie_file, e.tipo
             FROM tab_rutina_ejercicios re
             JOIN tab_ejercicios e ON e.id_ejercicio = re.id_ejercicio
             WHERE re.id_rutina = :id
             ORDER BY re.orden ASC"
        );
        $stmt->execute([':id' => $id]);
        return ['exito' => true, 'datos' => $stmt->fetchAll(PDO::FETCH_ASSOC)];
    }

    public function agregarEjercicio(): array
    {
        Auth::requerirSesion();
        $d = json_decode(file_get_contents('php://input'), true) ?? [];

        $idRutina    = (int) ($d['id_rutina']    ?? 0);
        $idEjercicio = (int) ($d['id_ejercicio'] ?? 0);
        $duracion    = (int) ($d['duracion_seg']  ?? 45);
        $descanso    = (int) ($d['descanso_seg']  ?? 15);

        if (!$idRutina || !$idEjercicio) {
            return ['exito' => false, 'mensaje' => 'Datos incompletos.'];
        }

        $pdo  = Database::conectar();
        $stmt = $pdo->prepare(
            "SELECT COALESCE(MAX(orden), 0) + 1 FROM tab_rutina_ejercicios WHERE id_rutina = :id"
        );
        $stmt->execute([':id' => $idRutina]);
        $orden = (int) $stmt->fetchColumn();

        $stmt = $pdo->prepare(
            "INSERT INTO tab_rutina_ejercicios (id_rutina, id_ejercicio, orden, duracion_seg, descanso_seg)
             VALUES (:idr, :ide, :o, :d, :ds)"
        );
        $stmt->execute([':idr' => $idRutina, ':ide' => $idEjercicio, ':o' => $orden, ':d' => $duracion, ':ds' => $descanso]);

        return ['exito' => true, 'mensaje' => 'Ejercicio agregado a la rutina.'];
    }

    public function quitarEjercicio(): array
    {
        Auth::requerirSesion();
        $d  = json_decode(file_get_contents('php://input'), true) ?? [];
        $id = (int) ($d['id'] ?? 0); // id de tab_rutina_ejercicios

        if (!$id) return ['exito' => false, 'mensaje' => 'ID inválido.'];

        $pdo  = Database::conectar();
        $stmt = $pdo->prepare("DELETE FROM tab_rutina_ejercicios WHERE id = :id");
        $stmt->execute([':id' => $id]);

        return ['exito' => true, 'mensaje' => 'Ejercicio quitado de la rutina.'];
    }

    // ══════════════════════════════════════════
    //  CATÁLOGO DE EJERCICIOS
    // ══════════════════════════════════════════

    public function listarEjercicios(): array
    {
        Auth::requerirSesion();
        $pdo  = Database::conectar();
        $stmt = $pdo->query("SELECT * FROM tab_ejercicios ORDER BY nombre ASC");
        return ['exito' => true, 'datos' => $stmt->fetchAll(PDO::FETCH_ASSOC)];
    }

    public function guardarEjercicio(): array
    {
        Auth::requerirSesion();
        $d = json_decode(file_get_contents('php://input'), true) ?? [];

        $nombre      = trim($d['nombre']       ?? '');
        $desc        = trim($d['descripcion']  ?? '');
        $lottie      = trim($d['lottie_file']  ?? '');
        $tipo        = $d['tipo']              ?? 'cardio';
        $idEjercicio = (int) ($d['id_ejercicio'] ?? 0);

        if (empty($nombre) || empty($lottie)) {
            return ['exito' => false, 'mensaje' => 'Nombre y archivo Lottie son requeridos.'];
        }

        $tiposValidos = ['cardio', 'fuerza', 'flexibilidad', 'equilibrio'];
        if (!in_array($tipo, $tiposValidos)) $tipo = 'cardio';

        $pdo = Database::conectar();

        if ($idEjercicio > 0) {
            $stmt = $pdo->prepare(
                "UPDATE tab_ejercicios SET nombre=:n, descripcion=:d, lottie_file=:l, tipo=:t
                 WHERE id_ejercicio=:id"
            );
            $stmt->execute([':n' => $nombre, ':d' => $desc, ':l' => $lottie, ':t' => $tipo, ':id' => $idEjercicio]);
            return ['exito' => true, 'mensaje' => 'Ejercicio actualizado.'];
        }

        $stmt = $pdo->prepare(
            "INSERT INTO tab_ejercicios (nombre, descripcion, lottie_file, tipo, activo)
             VALUES (:n, :d, :l, :t, 1)"
        );
        $stmt->execute([':n' => $nombre, ':d' => $desc, ':l' => $lottie, ':t' => $tipo]);
        return ['exito' => true, 'mensaje' => 'Ejercicio creado.', 'id' => (int) $pdo->lastInsertId()];
    }
}

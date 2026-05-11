<?php
namespace App\Models;

use App\Config\Database;
use PDO;

class Rutina
{
    /**
     * Devuelve la primera rutina activa con su lista de ejercicios.
     * La estructura resultante:
     * [
     *   'id_rutina'          => 1,
     *   'nombre'             => 'Rutina Día 1',
     *   'duracion_total_seg' => 900,
     *   'ejercicios' => [
     *       ['orden'=>1,'nombre'=>'Jumping Jacks','lottie_file'=>'test.json',
     *        'duracion_seg'=>45,'descanso_seg'=>30,'tipo'=>'cardio'],
     *       ...
     *   ]
     * ]
     */
    public static function activa(): ?array
    {
        $pdo = Database::conectar();

        // 1. Obtener rutina activa
        $stmt = $pdo->query(
            "SELECT id_rutina, nombre, descripcion, duracion_total_seg
             FROM tab_rutinas
             WHERE activo = 1
             ORDER BY id_rutina ASC
             LIMIT 1"
        );
        $rutina = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$rutina) return null;

        // 2. Obtener ejercicios de esa rutina
        $stmt2 = $pdo->prepare(
            "SELECT re.orden, re.duracion_seg, re.descanso_seg,
                    e.nombre, e.lottie_file, e.tipo, e.descripcion AS desc_ejercicio
             FROM tab_rutina_ejercicios re
             JOIN tab_ejercicios e ON e.id_ejercicio = re.id_ejercicio
             WHERE re.id_rutina = :id
             ORDER BY re.orden ASC"
        );
        $stmt2->execute([':id' => $rutina['id_rutina']]);
        $rutina['ejercicios'] = $stmt2->fetchAll(PDO::FETCH_ASSOC);

        return $rutina;
    }
}

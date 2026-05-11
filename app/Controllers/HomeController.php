<?php
namespace App\Controllers;

use App\Models\Progreso;
use App\Models\Rutina;
use App\Core\Auth;

class HomeController
{
    /**
     * GET /home  — Vista pública del ejercicio del día.
     * Requiere sesión activa.
     */
    public function index(): void
    {
        Auth::requerirSesion();

        $idUsuario = (int) $_SESSION['id_usuario'];
        $hoy       = new \DateTime();
        $year      = (int) $hoy->format('Y');
        $month     = (int) $hoy->format('n');

        // ── Datos de BD ────────────────────────────────────
        $rutina      = Rutina::activa();
        $hechoHoy    = Progreso::hechoHoy($idUsuario);
        $diasMes     = Progreso::diasDelMes($idUsuario, $year, $month);
        $totalMes    = Progreso::completadosMesActual($idUsuario);
        $racha       = Progreso::rachaActual($idUsuario);
        $totalGlobal = Progreso::totalCompletados($idUsuario);

        // Primer ejercicio de la rutina (para mostrar en la tarjeta principal)
        $ejercicio = $rutina['ejercicios'][0] ?? null;

        require VIEW_PATH . 'home.php';
    }

    /**
     * POST /api/progreso/marcar
     * Marca el ejercicio de hoy como completado.
     * Devuelve JSON.
     */
    public function marcar(): array
    {
        Auth::requerirSesion();

        $idUsuario = (int) $_SESSION['id_usuario'];

        if (Progreso::hechoHoy($idUsuario)) {
            return ['exito' => false, 'mensaje' => 'Ya completaste tu ejercicio de hoy. ¡Regresa mañana!'];
        }

        $rutina = Rutina::activa();
        if (!$rutina) {
            return ['exito' => false, 'mensaje' => 'No hay rutina activa en este momento.'];
        }

        $ok = Progreso::marcar($idUsuario, (int) $rutina['id_rutina']);

        return [
            'exito'   => $ok,
            'mensaje' => $ok ? '¡Ejercicio completado! 🎉' : 'Ocurrió un error al guardar.',
        ];
    }
}

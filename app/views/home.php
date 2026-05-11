<?php
/**
 * app/views/home.php
 * Variables esperadas del HomeController:
 *   $rutina, $ejercicio, $hechoHoy, $diasMes,
 *   $totalMes, $racha, $totalGlobal
 */

// Fallback en caso de que no haya rutina activa
$nombreEjercicio  = $ejercicio['nombre']      ?? 'Sin ejercicio';
$lottieFile       = $ejercicio['lottie_file'] ?? 'test.json';
$idRutinaActiva   = $rutina['id_rutina']      ?? 0;
$nombreRutina     = $rutina['nombre']         ?? 'Sin rutina activa';

$diasMesJSON      = json_encode(array_values($diasMes));
$nombreUsuario    = htmlspecialchars($_SESSION['nombre'] ?? 'Atleta');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="15 Minutos al día — Tu rutina de ejercicio diaria">
    <title>15 Minutos · Ejercicio del Día</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary:    #6C63FF;
            --primary-dk: #4B44CC;
            --success:    #22C55E;
            --warn:       #F59E0B;
            --bg:         #0F0F1A;
            --surface:    #1A1A2E;
            --text:       #E2E8F0;
            --muted:      #94A3B8;
            --radius:     18px;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100dvh;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: fixed;
            top: -40%; left: -20%;
            width: 600px; height: 600px;
            background: radial-gradient(circle, rgba(108,99,255,.22) 0%, transparent 70%);
            animation: blob 8s ease-in-out infinite alternate;
            pointer-events: none; z-index: 0;
        }
        body::after {
            content: '';
            position: fixed;
            bottom: -30%; right: -20%;
            width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(34,197,94,.13) 0%, transparent 70%);
            animation: blob 10s ease-in-out infinite alternate-reverse;
            pointer-events: none; z-index: 0;
        }
        @keyframes blob {
            from { transform: translate(0,0) scale(1); }
            to   { transform: translate(40px,30px) scale(1.15); }
        }

        .page-wrap {
            position: relative; z-index: 1;
            max-width: 480px; margin: 0 auto;
            padding: 24px 16px 80px;
        }

        /* ── HEADER ── */
        .app-header {
            display: flex; align-items: center;
            justify-content: space-between; margin-bottom: 28px;
        }
        .app-logo { display: flex; align-items: center; gap: 10px; }
        .app-logo-icon {
            width: 42px; height: 42px;
            background: linear-gradient(135deg, var(--primary), #A78BFA);
            border-radius: 12px; display: flex; align-items: center;
            justify-content: center; font-size: 1.2rem; color: #fff;
            box-shadow: 0 0 20px rgba(108,99,255,.4);
        }
        .app-logo-text h1 { font-size: 1.1rem; font-weight: 800; line-height: 1; }
        .app-logo-text span { font-size: .72rem; color: var(--muted); font-weight: 500; }

        .streak-badge {
            background: linear-gradient(135deg, var(--warn), #F97316);
            color: #fff; border-radius: 20px; padding: 6px 14px;
            font-size: .78rem; font-weight: 700;
            display: flex; align-items: center; gap: 5px;
            box-shadow: 0 4px 15px rgba(245,158,11,.3);
        }

        /* ── GREETING ── */
        .greeting   { color: var(--muted); font-size: .9rem; font-weight: 500; margin-bottom: 4px; }
        .day-title  {
            font-size: 1.7rem; font-weight: 900; line-height: 1.2; margin-bottom: 20px;
            background: linear-gradient(90deg,#fff 0%,#A78BFA 100%);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        }

        /* ── MOTIVACIONAL ── */
        .motivation-bar {
            background: linear-gradient(135deg,rgba(108,99,255,.18),rgba(167,139,250,.08));
            border: 1px solid rgba(108,99,255,.3);
            border-radius: 14px; padding: 13px 16px;
            margin-bottom: 20px; display: flex; align-items: center; gap: 12px;
        }
        .motivation-bar .icon { font-size: 1.4rem; }
        .motivation-bar p { font-size: .84rem; color: var(--text); margin: 0; font-weight: 500; }
        .motivation-bar strong { color: #A78BFA; }

        /* ── LOTTIE CARD ── */
        .lottie-card {
            background: var(--surface);
            border: 1px solid rgba(255,255,255,.08);
            border-radius: var(--radius); padding: 20px;
            text-align: center; margin-bottom: 20px;
            position: relative; overflow: hidden;
        }
        .lottie-card::before {
            content: ''; position: absolute;
            top: 0; left: 0; right: 0; height: 2px;
            background: linear-gradient(90deg, var(--primary), #A78BFA, var(--success));
        }
        .exercise-name  { font-size: .78rem; color: var(--muted); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 2px; }
        .exercise-title { font-size: 1.2rem; font-weight: 700; margin-bottom: 0; }

        /* ── BOTÓN PRINCIPAL ── */
        .btn-exercise {
            width: 100%; padding: 18px; border-radius: var(--radius);
            font-size: 1.05rem; font-weight: 800; border: none;
            cursor: pointer; transition: all .25s ease;
            display: flex; align-items: center; justify-content: center;
            gap: 12px; margin-bottom: 16px; letter-spacing: .3px;
        }
        .btn-exercise.active {
            background: linear-gradient(135deg, var(--primary), #A78BFA);
            color: #fff; box-shadow: 0 8px 32px rgba(108,99,255,.5);
            animation: pulse-btn 2.5s ease-in-out infinite;
        }
        .btn-exercise.active:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 40px rgba(108,99,255,.7);
        }
        .btn-exercise.done {
            background: rgba(34,197,94,.15);
            border: 1.5px solid rgba(34,197,94,.35);
            color: var(--success); cursor: default;
        }
        .btn-exercise:disabled { opacity: .7; cursor: not-allowed; }
        @keyframes pulse-btn {
            0%,100% { box-shadow: 0 8px 32px rgba(108,99,255,.5); }
            50%      { box-shadow: 0 8px 48px rgba(108,99,255,.8); }
        }

        /* ── STATS ── */
        .stats-row { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 12px; margin-bottom: 24px; }
        .stat-card {
            background: var(--surface);
            border: 1px solid rgba(255,255,255,.07);
            border-radius: 14px; padding: 14px 10px; text-align: center;
        }
        .stat-value { font-size: 1.6rem; font-weight: 800; line-height: 1; }
        .stat-label { font-size: .66rem; color: var(--muted); margin-top: 4px; font-weight: 500; text-transform: uppercase; letter-spacing: .5px; }
        .stat-card:nth-child(1) .stat-value { color: var(--primary); }
        .stat-card:nth-child(2) .stat-value { color: var(--warn); }
        .stat-card:nth-child(3) .stat-value { color: var(--success); }

        /* ── CALENDARIO ── */
        .calendar-card {
            background: var(--surface);
            border: 1px solid rgba(255,255,255,.08);
            border-radius: var(--radius); padding: 20px;
        }
        .calendar-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; }
        .calendar-header h2 { font-size: 1rem; font-weight: 700; text-transform: capitalize; }
        .calendar-nav button {
            background: rgba(255,255,255,.07); border: none; color: var(--text);
            border-radius: 8px; width: 30px; height: 30px; cursor: pointer;
            font-size: .8rem; transition: background .2s;
        }
        .calendar-nav button:hover { background: rgba(255,255,255,.15); }

        .days-grid { display: grid; grid-template-columns: repeat(7,1fr); gap: 4px; }
        .day-name  { text-align: center; font-size: .6rem; color: var(--muted); font-weight: 600; text-transform: uppercase; padding: 4px 0 8px; letter-spacing: .5px; }

        .day-cell {
            aspect-ratio: 1; display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            border-radius: 10px; font-size: .78rem; font-weight: 600; transition: all .2s;
        }
        .day-cell.empty { pointer-events: none; }
        .day-cell.today {
            background: var(--primary); color: #fff;
            box-shadow: 0 4px 12px rgba(108,99,255,.4);
        }
        .day-cell.done-day { background: rgba(34,197,94,.15); }
        .day-cell.done-day .day-num { font-size: .62rem; color: var(--success); }
        .day-cell .dumbbell { font-size: .9rem; line-height: 1; }

        /* ── LOGOUT LINK ── */
        .bottom-bar {
            text-align: center; margin-top: 32px;
        }
        .bottom-bar a { color: var(--muted); font-size: .78rem; text-decoration: none; }
        .bottom-bar a:hover { color: var(--text); }

        /* ── TOAST ── */
        #toast {
            position: fixed; bottom: 24px; left: 50%; transform: translateX(-50%);
            background: var(--surface); border: 1px solid rgba(255,255,255,.12);
            color: var(--text); padding: 12px 24px; border-radius: 30px;
            font-size: .88rem; font-weight: 600; box-shadow: 0 8px 32px rgba(0,0,0,.4);
            opacity: 0; transition: opacity .3s; pointer-events: none; z-index: 999;
            white-space: nowrap;
        }
        #toast.show { opacity: 1; }
    </style>
</head>
<body>

<div class="page-wrap">

    <!-- HEADER -->
    <header class="app-header">
        <div class="app-logo">
            <div class="app-logo-icon"><i class="fa fa-clock"></i></div>
            <div class="app-logo-text">
                <h1>15 Minutos</h1>
                <span>Hola, <?= $nombreUsuario ?></span>
            </div>
        </div>
        <div class="streak-badge" id="badge-racha">
            🔥 <span><?= $racha ?></span> días
        </div>
    </header>

    <!-- SALUDO -->
    <p class="greeting" id="greeting-text"></p>
    <h2 class="day-title" id="day-title">
        <?= $hechoHoy ? '¡Lo lograste<br>hoy!' : '¡Es hora de<br>moverte hoy!' ?>
    </h2>

    <!-- MOTIVACIONAL -->
    <div class="motivation-bar" id="motivation-bar">
        <?php if ($hechoHoy): ?>
            <span class="icon">🎉</span>
            <p><strong>¡Excelente!</strong> Ya completaste tu ejercicio de hoy. ¡Regresa mañana!</p>
        <?php else: ?>
            <span class="icon">💪</span>
            <p>Solo <strong>15 minutos</strong> pueden cambiar tu día. ¡Tú puedes!</p>
        <?php endif; ?>
    </div>

    <!-- LOTTIE / EJERCICIO -->
    <div class="lottie-card">
        <p class="exercise-name"><?= htmlspecialchars($nombreRutina) ?></p>
        <p class="exercise-title mb-3"><?= htmlspecialchars($nombreEjercicio) ?></p>
        <lottie-player
            src="/15minutos/public/assets/animations/<?= htmlspecialchars($lottieFile) ?>"
            background="transparent"
            speed="1"
            style="width:100%;height:240px;"
            loop autoplay>
        </lottie-player>
    </div>

    <!-- BOTÓN PRINCIPAL -->
    <button
        class="btn-exercise <?= $hechoHoy ? 'done' : 'active' ?>"
        id="btn-exercise"
        <?= $hechoHoy ? 'disabled' : '' ?>
        onclick="marcarEjercicio()">
        <?php if ($hechoHoy): ?>
            <i class="fa fa-check-circle"></i>
            <span>¡Ejercicio completado hoy!</span>
        <?php else: ?>
            <i class="fa fa-play-circle"></i>
            <span>¡Realizar ejercicio del día!</span>
        <?php endif; ?>
    </button>

    <!-- STATS -->
    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-value" id="stat-mes"><?= $totalMes ?></div>
            <div class="stat-label">Este mes</div>
        </div>
        <div class="stat-card">
            <div class="stat-value" id="stat-racha"><?= $racha ?></div>
            <div class="stat-label">Racha</div>
        </div>
        <div class="stat-card">
            <div class="stat-value" id="stat-total"><?= $totalGlobal ?></div>
            <div class="stat-label">Total</div>
        </div>
    </div>

    <!-- CALENDARIO -->
    <div class="calendar-card">
        <div class="calendar-header">
            <div class="calendar-nav"><button onclick="cambiarMes(-1)">‹</button></div>
            <h2 id="cal-title"></h2>
            <div class="calendar-nav"><button onclick="cambiarMes(1)">›</button></div>
        </div>
        <div class="days-grid">
            <div class="day-name">Lu</div><div class="day-name">Ma</div>
            <div class="day-name">Mi</div><div class="day-name">Ju</div>
            <div class="day-name">Vi</div><div class="day-name">Sá</div>
            <div class="day-name">Do</div>
        </div>
        <div class="days-grid" id="calendar-body"></div>
    </div>

    <!-- LOGOUT -->
    <div class="bottom-bar">
        <a href="/15minutos/admin/logout"><i class="fa fa-sign-out-alt me-1"></i>Cerrar sesión</a>
    </div>

</div><!-- /page-wrap -->

<div id="toast"></div>

<script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
<script>
// ── Datos inyectados desde PHP ──────────────────────────────────
const HECHO_HOY       = <?= $hechoHoy ? 'true' : 'false' ?>;
const DIAS_MES_ACTUAL = <?= $diasMesJSON ?>; // array de días (ej. [1,3,7])

const MESES = ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
               'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];

let viewDate = new Date();

// ── Init ────────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    actualizarSaludo();
    renderCalendario(viewDate);
});

// ── Saludo dinámico ─────────────────────────────────────────────
function actualizarSaludo() {
    const h = new Date().getHours();
    document.getElementById('greeting-text').textContent =
        h < 12 ? 'Buenos días ☀️' : h < 19 ? 'Buenas tardes 🌤️' : 'Buenas noches 🌙';
}

// ── Marcar ejercicio ────────────────────────────────────────────
async function marcarEjercicio() {
    const btn = document.getElementById('btn-exercise');
    if (btn.disabled) return;

    btn.disabled = true;
    btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i><span>Guardando...</span>';

    try {
        const res  = await fetch('/15minutos/api/progreso/marcar', { method: 'POST' });
        const data = await res.json();

        if (data.exito) {
            // Actualizar UI al estado "completado"
            btn.className = 'btn-exercise done';
            btn.innerHTML = '<i class="fa fa-check-circle"></i><span>¡Ejercicio completado hoy!</span>';

            document.getElementById('day-title').innerHTML = '¡Lo lograste<br>hoy!';
            document.getElementById('motivation-bar').innerHTML =
                '<span class="icon">🎉</span><p><strong>¡Excelente!</strong> Ya completaste tu ejercicio de hoy. ¡Regresa mañana!</p>';

            // Agregar hoy al array de días hechos y refrescar calendario
            const hoy = new Date();
            if (!DIAS_MES_ACTUAL.includes(hoy.getDate())) {
                DIAS_MES_ACTUAL.push(hoy.getDate());
            }
            renderCalendario(viewDate);

            // Incrementar stats
            const mesEl   = document.getElementById('stat-mes');
            const rachaEl = document.getElementById('stat-racha');
            const totEl   = document.getElementById('stat-total');
            mesEl.textContent   = parseInt(mesEl.textContent)   + 1;
            rachaEl.textContent = parseInt(rachaEl.textContent) + 1;
            totEl.textContent   = parseInt(totEl.textContent)   + 1;

            mostrarToast(data.mensaje);
        } else {
            btn.disabled = false;
            btn.className = 'btn-exercise active';
            btn.innerHTML = '<i class="fa fa-play-circle"></i><span>¡Realizar ejercicio del día!</span>';
            mostrarToast('⚠️ ' + data.mensaje);
        }
    } catch (e) {
        btn.disabled = false;
        btn.className = 'btn-exercise active';
        btn.innerHTML = '<i class="fa fa-play-circle"></i><span>¡Realizar ejercicio del día!</span>';
        mostrarToast('❌ Error de conexión. Intenta de nuevo.');
    }
}

// ── Calendario ──────────────────────────────────────────────────
function cambiarMes(delta) {
    viewDate = new Date(viewDate.getFullYear(), viewDate.getMonth() + delta, 1);
    renderCalendario(viewDate);
}

function renderCalendario(date) {
    const year  = date.getFullYear();
    const month = date.getMonth();
    const hoy   = new Date();

    document.getElementById('cal-title').textContent = `${MESES[month]} ${year}`;

    // Para meses distintos al actual, no tenemos datos en DIAS_MES_ACTUAL
    const esActual   = year === hoy.getFullYear() && month === hoy.getMonth();
    const diasHechos = esActual ? DIAS_MES_ACTUAL : [];

    const totalDias = new Date(year, month + 1, 0).getDate();
    let diaSemana   = new Date(year, month, 1).getDay();
    diaSemana = diaSemana === 0 ? 6 : diaSemana - 1; // Lunes=0

    let html = '';
    for (let i = 0; i < diaSemana; i++) html += '<div class="day-cell empty"></div>';

    for (let d = 1; d <= totalDias; d++) {
        const esHoy   = d === hoy.getDate() && esActual;
        const esHecho = diasHechos.includes(d);

        if (esHoy) {
            html += `<div class="day-cell today"><span>${d}</span></div>`;
        } else if (esHecho) {
            html += `<div class="day-cell done-day"><span class="dumbbell">🏋️</span><span class="day-num">${d}</span></div>`;
        } else {
            html += `<div class="day-cell"><span style="color:var(--muted)">${d}</span></div>`;
        }
    }
    document.getElementById('calendar-body').innerHTML = html;
}

// ── Toast ────────────────────────────────────────────────────────
function mostrarToast(msg) {
    const t = document.getElementById('toast');
    t.textContent = msg;
    t.classList.add('show');
    setTimeout(() => t.classList.remove('show'), 3200);
}
</script>
</body>
</html>

<?php
/**
 * routes/api.php
 *
 * Endpoints API — devuelven JSON.
 */

// ── Auth ──────────────────────────────────────────────────────
$router->api('POST', 'api/auth/login',
    fn() => (new App\Controllers\AuthController())->login()
);

$router->api('POST', 'api/auth/logout',
    fn() => (new App\Controllers\AuthController())->logout()
);

// ── Usuarios ──────────────────────────────────────────────────
$router->api('POST', 'api/usuarios/guardar',
    fn() => (new App\Controllers\UsuarioController())->guardar()
);

$router->api('GET', 'api/usuarios/listar',
    fn() => (new App\Controllers\UsuarioController())->listar()
);

// ── Progreso ──────────────────────────────────────────────────
$router->api('POST', 'api/progreso/marcar',
    fn() => (new App\Controllers\HomeController())->marcar()
);

// ── Rutinas ───────────────────────────────────────────────────
$router->api('GET',  'api/rutinas/listar',
    fn() => (new App\Controllers\RutinaController())->listar()
);

$router->api('POST', 'api/rutinas/guardar',
    fn() => (new App\Controllers\RutinaController())->guardarRutina()
);

$router->api('POST', 'api/rutinas/activar',
    fn() => (new App\Controllers\RutinaController())->activarRutina()
);

$router->api('POST', 'api/rutinas/eliminar',
    fn() => (new App\Controllers\RutinaController())->eliminarRutina()
);

$router->api('GET',  'api/rutinas/detalle',
    fn() => (new App\Controllers\RutinaController())->detalleRutina()
);

$router->api('POST', 'api/rutinas/ejercicio/agregar',
    fn() => (new App\Controllers\RutinaController())->agregarEjercicio()
);

$router->api('POST', 'api/rutinas/ejercicio/quitar',
    fn() => (new App\Controllers\RutinaController())->quitarEjercicio()
);

// ── Ejercicios (catálogo) ─────────────────────────────────────
$router->api('GET',  'api/ejercicios/listar',
    fn() => (new App\Controllers\RutinaController())->listarEjercicios()
);

$router->api('POST', 'api/ejercicios/guardar',
    fn() => (new App\Controllers\RutinaController())->guardarEjercicio()
);

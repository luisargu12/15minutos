<?php
/**
 * routes/web.php
 *
 * Registro de todas las rutas de Vista (devuelven HTML).
 * El $router ya está instanciado en index.php.
 *
 * Sintaxis: $router->web('ruta', function() { require VIEW_PATH . 'vista.php'; });
 */

use App\Core\Auth;
use App\Controllers\HomeController;
use App\Controllers\RutinaController;

define('VIEW_PATH', __DIR__ . '/../app/views/');

// --- RUTA PÚBLICA DE PRUEBA (Lottie) ---
$router->web('test', function () {
    require VIEW_PATH . 'test_lottie.php';
});

// --- RAÍZ → HOME ---
$router->web('', function () {
    if (!App\Core\Auth::estaAutenticado()) {
        header('Location: /15minutos/admin/login');
        exit;
    }
    header('Location: /15minutos/home');
    exit;
});

// --- HOME (ejercicio del día) ---
$router->web('home', function () {
    (new HomeController())->index();
});

$router->web('inicio', function () {
    (new HomeController())->index();
});

// --- LOGIN ---
$router->web('admin/login', function () {
    if (Auth::estaAutenticado()) {
        header('Location: /15minutos/admin/dashboard');
        exit;
    }
    require VIEW_PATH . 'admin/login.php';
});

// --- LOGOUT ---
$router->web('admin/logout', function () {
    Auth::cerrarSesion();
    header('Location: /15minutos/admin/login');
    exit;
});

// --- DASHBOARD (protegida) ---
$router->web('admin/dashboard', function () {
    Auth::requerirSesion();
    require VIEW_PATH . 'admin/dashboard.php';
});

// --- USUARIOS (protegida) ---
$router->web('admin/usuarios', function () {
    Auth::requerirSesion();
    require VIEW_PATH . 'admin/usuarios.php';
});

// --- RUTINAS (protegida) ---
$router->web('admin/rutinas', function () {
    Auth::requerirSesion();
    require VIEW_PATH . 'admin/rutinas.php';
});

// --- EJERCICIOS (protegida) ---
$router->web('admin/ejercicios', function () {
    Auth::requerirSesion();
    require VIEW_PATH . 'admin/ejercicios.php';
});

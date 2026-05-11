<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\Router;
use Dotenv\Dotenv;

// Cargar variables de entorno
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->safeLoad();

session_start();

// Configuración de errores para desarrollo
if (($_ENV['APP_DEBUG'] ?? 'false') === 'true') {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

$router = new Router();

// Cargar rutas
require_once __DIR__ . '/../routes/api.php';
require_once __DIR__ . '/../routes/web.php';

// Despachar la petición
$router->despachar();

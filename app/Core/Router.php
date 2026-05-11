<?php
namespace App\Core;

class Router
{
    private array $apiRoutes = [];
    private array $webRoutes = [];

    public function api(string $metodo, string $ruta, callable $handler): void
    {
        $this->apiRoutes[strtoupper($metodo) . ':' . $ruta] = $handler;
    }

    public function web(string $ruta, callable $handler): void
    {
        $this->webRoutes[$ruta] = $handler;
    }

    public function despachar(): void
    {
        $ruta   = $_GET['ruta'] ?? 'home';
        $metodo = strtoupper($_SERVER['REQUEST_METHOD']);

        // Rutas de API
        if (str_starts_with($ruta, 'api/')) {
            header('Content-Type: application/json');

            $handler = $this->apiRoutes["$metodo:$ruta"]
                ?? $this->apiRoutes["ANY:$ruta"]
                ?? null;

            if ($handler) {
                echo json_encode($handler());
            } else {
                http_response_code(404);
                echo json_encode(['exito' => false, 'mensaje' => 'Endpoint no encontrado']);
            }
            return;
        }

        // Rutas de Web (Vistas HTML)
        $handler = $this->webRoutes[$ruta] ?? null;

        if ($handler) {
            $handler();
        } else {
            http_response_code(404);
            require __DIR__ . '/../../app/views/errors/404.php';
        }
    }
}

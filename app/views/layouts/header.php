<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitulo ?? '15 Minutos' ?></title>

    <!-- Bootstrap 5.3 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts (Inter) -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Estilos personalizados -->
    <link rel="stylesheet" href="/15minutos/css/style.css?v=<?= time() ?>">
    
    <style>
        :root {
            --primary-color: #4e73df;
            --secondary-color: #858796;
        }
        body { 
            font-family: 'Inter', sans-serif; 
            background-color: #f8f9fa;
            color: #333;
        }
        .card { border-radius: 12px; }
        .btn-primary { background-color: var(--primary-color); border: none; }
        .btn-primary:hover { background-color: #2e59d9; }
    </style>
</head>
<body>
<?php
// Función para marcar links activos
$uri = $_GET['ruta'] ?? 'home';
function isActive($current, $target) {
    return strpos($current, $target) !== false ? 'active' : '';
}
?>

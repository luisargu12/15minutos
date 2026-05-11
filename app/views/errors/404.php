<?php
$pageTitulo = '404 - No encontrado';
require __DIR__ . '/../layouts/header.php';
?>

<div class="container text-center vh-100 d-flex flex-column justify-content-center">
    <h1 class="display-1 fw-bold text-muted">404</h1>
    <h2 class="mb-4">Página no encontrada</h2>
    <p class="lead mb-5">Lo sentimos, la página que buscas no existe o ha sido movida.</p>
    <div>
        <a href="/15minutos/admin/login" class="btn btn-primary px-4 shadow-sm">
            <i class="fa fa-home me-2"></i> Volver al Inicio
        </a>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>

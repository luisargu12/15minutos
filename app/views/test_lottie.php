<?php
$pageTitulo = 'Prueba de Animaciones | 15 Minutos';
require __DIR__ . '/layouts/header.php';
?>

<div class="container py-5 text-center">
    <div class="mb-5">
        <h1 class="fw-bold">Prueba de Lottie Animations</h1>
        <p class="text-muted">Esta es una vista pública para verificar que tus archivos JSON se carguen correctamente.
        </p>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-0 p-4">
                <!-- El reproductor de Lottie -->
                <lottie-player src="/15minutos/public/assets/animations/test.json" background="transparent" speed="1"
                    style="width: 100%; height: 300px;" loop autoplay>
                </lottie-player>

                <div class="mt-4">
                    <div class="alert alert-info small">
                        <i class="fa fa-info-circle me-2"></i>
                        Asegúrate de subir tu archivo con el nombre <strong>test.json</strong> a la carpeta: <br>
                        <code>public/assets/animations/</code>
                    </div>
                    <a href="/15minutos/admin/login" class="btn btn-outline-primary btn-sm">
                        Ir al Login
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Lottie Player Script -->
<script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>

<?php require __DIR__ . '/layouts/footer.php'; ?>
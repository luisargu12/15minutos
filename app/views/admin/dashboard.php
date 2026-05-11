<?php
$pageTitulo = 'Panel de Control | 15 Minutos';
require __DIR__ . '/../layouts/header.php';
?>

<div class="container-fluid p-0">
    <div class="d-flex">
        <?php require __DIR__ . '/../layouts/sidebar.php'; ?>

        <div class="w-100 px-4">
            <!-- Título de Sección -->
            <div class="mb-4 d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="fw-bold text-dark mb-1">Dashboard</h2>
                    <p class="text-muted">Resumen general del sistema</p>
                </div>
                <div id="fecha-actual" class="badge bg-white text-primary border p-2 fw-normal"></div>
            </div>

            <!-- Tarjetas de Estadísticas -->
            <div class="row g-4 mb-5">
                <div class="col-md-4">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body d-flex align-items-center">
                            <div class="rounded-3 bg-primary bg-opacity-10 p-3 me-3">
                                <i class="fa fa-users text-primary fs-3"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block fw-bold text-uppercase" style="font-size: 0.7rem;">Usuarios</small>
                                <h3 id="total-usuarios" class="mb-0 fw-bold">—</h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body d-flex align-items-center">
                            <div class="rounded-3 bg-success bg-opacity-10 p-3 me-3">
                                <i class="fa fa-user-check text-success fs-3"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block fw-bold text-uppercase" style="font-size: 0.7rem;">Sesión</small>
                                <h6 class="mb-0 fw-bold"><?= htmlspecialchars($_SESSION['usuario'] ?? 'Admin') ?></h6>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body d-flex align-items-center">
                            <div class="rounded-3 bg-info bg-opacity-10 p-3 me-3">
                                <i class="fa fa-calendar-alt text-info fs-3"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block fw-bold text-uppercase" style="font-size: 0.7rem;">Estado</small>
                                <h6 class="mb-0 fw-bold text-success">Sistema Activo</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Accesos Rápidos -->
            <div class="card border-0 shadow-sm overflow-hidden">
                <div class="card-header bg-white py-3 border-0">
                    <h6 class="mb-0 fw-bold text-dark">Acciones Rápidas</h6>
                </div>
                <div class="card-body p-4 bg-light">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <a href="/15minutos/admin/usuarios" class="btn btn-white border shadow-sm w-100 text-start py-3 px-4 h-100 hover-shadow transition">
                                <i class="fa fa-user-cog text-primary mb-2 d-block fs-4"></i>
                                <span class="d-block fw-bold small">Gestionar Usuarios</span>
                                <span class="text-muted x-small">Crear y editar cuentas</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Fecha actual
    document.getElementById('fecha-actual').textContent = new Date().toLocaleDateString('es-MX', { 
        weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' 
    });

    // Cargar total de usuarios (simulado o vía API)
    axios.get('/15minutos/api/usuarios/listar')
        .then(res => {
            if (res.data.exito) {
                document.getElementById('total-usuarios').textContent = res.data.datos.length;
            }
        })
        .catch(() => {
            document.getElementById('total-usuarios').textContent = '1'; // Valor por defecto
        });
</script>

<style>
    .hover-shadow:hover { box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important; transform: translateY(-2px); }
    .transition { transition: all .2s ease-in-out; }
    .x-small { font-size: 0.75rem; }
</style>

<?php require __DIR__ . '/../layouts/footer.php'; ?>

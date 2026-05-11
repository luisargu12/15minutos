<!-- Sidebar Lateral -->
<div class="offcanvas offcanvas-start bg-dark text-white" tabindex="-1" id="sidebarMenu" aria-labelledby="sidebarMenuLabel" style="width: 280px;">
    <div class="offcanvas-header border-bottom border-secondary">
        <h5 class="offcanvas-title fw-bold" id="sidebarMenuLabel">
            <i class="fa fa-clock me-2"></i> 15 Minutos
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body p-0">
        <ul class="nav flex-column mt-3">
            <li class="nav-item">
                <a class="nav-link text-white px-4 py-3 <?= isActive($uri, 'dashboard') ?>" href="/15minutos/admin/dashboard">
                    <i class="fa fa-tachometer-alt me-3"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white px-4 py-3 <?= isActive($uri, 'usuarios') ?>" href="/15minutos/admin/usuarios">
                    <i class="fa fa-users me-3"></i> Usuarios
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white px-4 py-3 <?= isActive($uri, 'rutinas') ?>" href="/15minutos/admin/rutinas">
                    <i class="fa fa-dumbbell me-3"></i> Rutinas
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white px-4 py-3 <?= isActive($uri, 'ejercicios') ?>" href="/15minutos/admin/ejercicios">
                    <i class="fa fa-running me-3"></i> Ejercicios
                </a>
            </li>
            <li class="nav-item border-top border-secondary mt-2">
                <a class="nav-link text-danger px-4 py-3" href="/15minutos/admin/logout">
                    <i class="fa fa-sign-out-alt me-3"></i> Cerrar Sesión
                </a>
            </li>
        </ul>
    </div>
</div>

<!-- Botón para abrir el menú (Visible solo si hay sesión) -->
<nav class="navbar navbar-light bg-white shadow-sm px-4 mb-4">
    <button class="btn btn-outline-primary border-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu">
        <i class="fa fa-bars fs-4"></i>
    </button>
    <span class="navbar-brand mb-0 h1 fs-6 text-muted">Panel de Control</span>
</nav>

<main class="container">

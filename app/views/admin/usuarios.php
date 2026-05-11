<?php
$pageTitulo = 'Usuarios | 15 Minutos';
require __DIR__ . '/../layouts/header.php';
?>

<div class="container-fluid p-0">
    <div class="d-flex">
        <?php require __DIR__ . '/../layouts/sidebar.php'; ?>

        <div class="w-100 px-4">
            <!-- Título de Sección -->
            <div class="mb-4 d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="fw-bold text-dark mb-1">Usuarios</h2>
                    <p class="text-muted">Gestión de acceso al sistema</p>
                </div>
                <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modal-usuario">
                    <i class="fa fa-user-plus me-2"></i> Nuevo Usuario
                </button>
            </div>

            <!-- Tabla de Usuarios -->
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-secondary small text-uppercase fw-bold">
                                <tr>
                                    <th class="px-4 py-3">#</th>
                                    <th class="py-3">Nombre</th>
                                    <th class="py-3">Usuario</th>
                                    <th class="py-3 text-center">Tipo</th>
                                    <th class="py-3 text-center">Estatus</th>
                                    <th class="py-3 text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="tbody-usuarios" class="border-top-0">
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-5">
                                        <div class="spinner-border spinner-border-sm text-primary me-2"></div> Cargando registros...
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Gestionar Usuario -->
<div class="modal fade" id="modal-usuario" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="fw-bold">Crear Nuevo Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="form-usuario">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nombre Completo</label>
                        <input type="text" id="inp-nombre" class="form-control" placeholder="Ej. Juan Pérez" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nombre de Usuario</label>
                        <input type="text" id="inp-usuario" class="form-control" placeholder="Ej. jperez" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Contraseña</label>
                        <input type="password" id="inp-password" class="form-control" placeholder="••••••••" required>
                    </div>
                    <div class="mb-0">
                        <label class="form-label small fw-bold">Rol</label>
                        <select id="inp-tipo" class="form-select">
                            <option value="1">Administrador</option>
                            <option value="2" selected>Estándar</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-top-0 pt-0">
                <button type="button" class="btn btn-light text-muted fw-bold small py-2 px-4" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" id="btn-guardar-usuario" class="btn btn-primary fw-bold small py-2 px-4">Guardar Usuario</button>
            </div>
        </div>
    </div>
</div>

<?php
ob_start();
?>
<script>
    const TIPO_LABELS = { 1: 'Administrador', 2: 'Estándar' };

    function renderStatus(status) {
        return status == 1 
            ? '<span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">Activo</span>'
            : '<span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill">Inactivo</span>';
    }

    async function cargarUsuarios() {
        try {
            const res = await axios.get('/15minutos/api/usuarios/listar');
            const tbody = document.getElementById('tbody-usuarios');
            
            if (res.data.datos && res.data.datos.length > 0) {
                tbody.innerHTML = res.data.datos.map((u, i) => `
                    <tr class="transition">
                        <td class="px-4 text-muted small">${i + 1}</td>
                        <td class="fw-bold">${u.nombre}</td>
                        <td><code class="bg-light px-2 py-1 rounded text-primary">${u.usuario}</code></td>
                        <td class="text-center"><small class="fw-bold text-secondary">${TIPO_LABELS[u.tipo_usuario]}</small></td>
                        <td class="text-center">${renderStatus(u.status)}</td>
                        <td class="text-center">
                            <button class="btn btn-light btn-sm rounded-circle"><i class="fa fa-edit text-muted"></i></button>
                        </td>
                    </tr>
                `).join('');
            } else {
                tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted py-5">No hay usuarios registrados</td></tr>';
            }
        } catch (err) {
            document.getElementById('tbody-usuarios').innerHTML = '<tr><td colspan="6" class="text-center text-danger py-5">Error al conectar con el servidor</td></tr>';
        }
    }

    document.getElementById('btn-guardar-usuario').addEventListener('click', async function() {
        const btn = this;
        const form = {
            nombre: document.getElementById('inp-nombre').value,
            usuario: document.getElementById('inp-usuario').value,
            password: document.getElementById('inp-password').value,
            tipo_usuario: parseInt(document.getElementById('inp-tipo').value)
        };

        if (!form.nombre || !form.usuario || !form.password) {
            return Swal.fire('Error', 'Todos los campos son obligatorios', 'warning');
        }

        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Guardando...';

        try {
            const res = await axios.post('/15minutos/api/usuarios/guardar', form);
            if (res.data.exito) {
                Swal.fire('¡Éxito!', res.data.mensaje, 'success');
                bootstrap.Modal.getInstance(document.getElementById('modal-usuario')).hide();
                document.getElementById('form-usuario').reset();
                cargarUsuarios();
            } else {
                Swal.fire('Error', res.data.mensaje, 'error');
            }
        } catch (err) {
            Swal.fire('Error', 'Error de conexión', 'error');
        } finally {
            btn.disabled = false;
            btn.textContent = 'Guardar Usuario';
        }
    });

    cargarUsuarios();
</script>
<?php
$custom_scripts = ob_get_clean();
require __DIR__ . '/../layouts/footer.php';
?>

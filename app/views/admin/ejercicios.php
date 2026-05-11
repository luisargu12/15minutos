<?php
$pageTitulo = 'Ejercicios | 15 Minutos';
require __DIR__ . '/../layouts/header.php';
require __DIR__ . '/../layouts/sidebar.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0"><i class="fa fa-running me-2 text-primary"></i>Catálogo de Ejercicios</h4>
        <small class="text-muted">Archivos JSON de Lottie disponibles en <code>public/assets/animations/</code></small>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalEjercicio" onclick="prepararNuevo()">
        <i class="fa fa-plus me-1"></i> Nuevo Ejercicio
    </button>
</div>

<div id="alerta-ej" class="d-none"></div>

<!-- Lista de ejercicios -->
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Archivo Lottie</th>
                        <th>Tipo</th>
                        <th>Estado</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="tabla-ejercicios">
                    <tr><td colspan="6" class="text-center text-muted py-4">Cargando...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ══ MODAL CREAR / EDITAR ═════════════════════════════════════ -->
<div class="modal fade" id="modalEjercicio" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="modal-ej-titulo">Nuevo Ejercicio</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="ej-id">

                <div class="mb-3">
                    <label class="form-label fw-semibold">Nombre del ejercicio</label>
                    <input type="text" id="ej-nombre" class="form-control" placeholder="Ej: Jumping Jacks">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        Archivo Lottie
                        <span class="text-muted fw-normal">(solo el nombre del .json)</span>
                    </label>
                    <div class="input-group">
                        <input type="text" id="ej-lottie" class="form-control" placeholder="jumping_jacks.json">
                        <span class="input-group-text"><i class="fa fa-film"></i></span>
                    </div>
                    <small class="text-muted">El archivo debe estar en <code>public/assets/animations/</code></small>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Tipo</label>
                    <select id="ej-tipo" class="form-select">
                        <option value="cardio">Cardio</option>
                        <option value="fuerza">Fuerza</option>
                        <option value="flexibilidad">Flexibilidad</option>
                        <option value="equilibrio">Equilibrio</option>
                    </select>
                </div>

                <div class="mb-1">
                    <label class="form-label fw-semibold">Descripción <span class="text-muted fw-normal">(opcional)</span></label>
                    <textarea id="ej-descripcion" class="form-control" rows="2" placeholder="Descripción breve del ejercicio"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button class="btn btn-primary" id="btn-guardar-ej" onclick="guardarEjercicio()">
                    <i class="fa fa-save me-1"></i> Guardar
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', cargarEjercicios);

async function cargarEjercicios() {
    const res  = await fetch('/15minutos/api/ejercicios/listar');
    const data = await res.json();
    const tbody = document.getElementById('tabla-ejercicios');

    const tipos = {
        cardio: 'bg-danger', fuerza: 'bg-primary',
        flexibilidad: 'bg-success', equilibrio: 'bg-warning text-dark'
    };

    if (!data.datos || !data.datos.length) {
        tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted py-4">No hay ejercicios. ¡Crea el primero!</td></tr>';
        return;
    }

    tbody.innerHTML = data.datos.map(e => `
        <tr>
            <td class="text-muted small">${e.id_ejercicio}</td>
            <td>
                <strong>${escHtml(e.nombre)}</strong>
                ${e.descripcion ? `<br><small class="text-muted">${escHtml(e.descripcion)}</small>` : ''}
            </td>
            <td><code class="small">${escHtml(e.lottie_file)}</code></td>
            <td><span class="badge ${tipos[e.tipo] || 'bg-secondary'}">${e.tipo}</span></td>
            <td>
                ${e.activo == 1
                    ? '<span class="badge bg-success-subtle text-success border border-success-subtle">Activo</span>'
                    : '<span class="badge bg-secondary-subtle text-secondary">Inactivo</span>'}
            </td>
            <td>
                <button class="btn btn-sm btn-outline-secondary" onclick="editarEjercicio(${e.id_ejercicio},'${escHtml(e.nombre)}','${escHtml(e.lottie_file)}','${e.tipo}','${escHtml(e.descripcion||'')}')">
                    <i class="fa fa-edit"></i>
                </button>
            </td>
        </tr>
    `).join('');
}

function prepararNuevo() {
    document.getElementById('ej-id').value = '';
    document.getElementById('ej-nombre').value = '';
    document.getElementById('ej-lottie').value = '';
    document.getElementById('ej-tipo').value = 'cardio';
    document.getElementById('ej-descripcion').value = '';
    document.getElementById('modal-ej-titulo').textContent = 'Nuevo Ejercicio';
}

function editarEjercicio(id, nombre, lottie, tipo, desc) {
    document.getElementById('ej-id').value = id;
    document.getElementById('ej-nombre').value = nombre;
    document.getElementById('ej-lottie').value = lottie;
    document.getElementById('ej-tipo').value = tipo;
    document.getElementById('ej-descripcion').value = desc;
    document.getElementById('modal-ej-titulo').textContent = 'Editar Ejercicio';
    new bootstrap.Modal(document.getElementById('modalEjercicio')).show();
}

async function guardarEjercicio() {
    const btn = document.getElementById('btn-guardar-ej');
    btn.disabled = true;

    const payload = {
        id_ejercicio: parseInt(document.getElementById('ej-id').value) || 0,
        nombre:       document.getElementById('ej-nombre').value.trim(),
        lottie_file:  document.getElementById('ej-lottie').value.trim(),
        tipo:         document.getElementById('ej-tipo').value,
        descripcion:  document.getElementById('ej-descripcion').value.trim(),
    };

    const res  = await fetch('/15minutos/api/ejercicios/guardar', { method: 'POST', body: JSON.stringify(payload) });
    const data = await res.json();
    btn.disabled = false;

    mostrarAlerta(data.exito ? 'success' : 'danger', data.mensaje);
    if (data.exito) {
        bootstrap.Modal.getInstance(document.getElementById('modalEjercicio')).hide();
        cargarEjercicios();
    }
}

function mostrarAlerta(tipo, msg) {
    const el = document.getElementById('alerta-ej');
    el.className = `alert alert-${tipo} alert-dismissible fade show`;
    el.innerHTML = `${msg} <button type="button" class="btn-close" data-bs-dismiss="alert"></button>`;
    setTimeout(() => el.classList.add('d-none'), 5000);
}

function escHtml(s) {
    if (!s) return '';
    return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}
</script>

<?php require __DIR__ . '/../layouts/footer.php'; ?>

<?php
$pageTitulo = 'Rutinas | 15 Minutos';
require __DIR__ . '/../layouts/header.php';
require __DIR__ . '/../layouts/sidebar.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0"><i class="fa fa-dumbbell me-2 text-primary"></i>Gestión de Rutinas</h4>
        <small class="text-muted">Crea y administra las sesiones de ejercicio del día</small>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalRutina" onclick="prepararNuevaRutina()">
        <i class="fa fa-plus me-1"></i> Nueva Rutina
    </button>
</div>

<!-- ALERTA de estado -->
<div id="alerta-rutinas" class="d-none"></div>

<!-- ── LISTA DE RUTINAS ─────────────────────────────────────────────── -->
<div id="lista-rutinas" class="row g-3 mb-5"></div>

<!-- ══════════════════════════════════════════════════════════════════
     MODAL: CREAR / EDITAR RUTINA
═══════════════════════════════════════════════════════════════════ -->
<div class="modal fade" id="modalRutina" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="modal-rutina-titulo">Nueva Rutina</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="rut-id">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nombre de la rutina</label>
                    <input type="text" id="rut-nombre" class="form-control" placeholder="Ej: Rutina Día 1 - Cardio">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Descripción <span class="text-muted">(opcional)</span></label>
                    <textarea id="rut-descripcion" class="form-control" rows="2" placeholder="Descripción breve de la sesión"></textarea>
                </div>
                <div class="mb-1">
                    <label class="form-label fw-semibold">Duración total</label>
                    <div class="input-group">
                        <input type="number" id="rut-duracion" class="form-control" value="900" min="60" max="3600">
                        <span class="input-group-text">segundos</span>
                    </div>
                    <small class="text-muted">900 seg = 15 minutos</small>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button class="btn btn-primary" id="btn-guardar-rutina" onclick="guardarRutina()">
                    <i class="fa fa-save me-1"></i> Guardar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ══════════════════════════════════════════════════════════════════
     MODAL: GESTIONAR EJERCICIOS DE UNA RUTINA
═══════════════════════════════════════════════════════════════════ -->
<div class="modal fade" id="modalEjerciciosRutina" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold"><i class="fa fa-list-ol me-2"></i>Ejercicios de: <span id="modal-er-nombre"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Lista de ejercicios actuales en la rutina -->
                <h6 class="fw-semibold mb-2">Ejercicios en esta rutina</h6>
                <div id="lista-er" class="mb-4 table-responsive">
                    <p class="text-muted small">Cargando...</p>
                </div>

                <hr>

                <!-- Agregar ejercicio -->
                <h6 class="fw-semibold mb-3"><i class="fa fa-plus-circle me-1 text-primary"></i>Agregar ejercicio</h6>
                <input type="hidden" id="er-id-rutina">
                <div class="row g-2 align-items-end">
                    <div class="col-12 col-md-5">
                        <label class="form-label small fw-semibold">Ejercicio</label>
                        <select id="er-ejercicio" class="form-select form-select-sm"></select>
                    </div>
                    <div class="col-6 col-md-3">
                        <label class="form-label small fw-semibold">Duración (seg)</label>
                        <input type="number" id="er-duracion" class="form-control form-control-sm" value="45" min="5" max="300">
                    </div>
                    <div class="col-6 col-md-3">
                        <label class="form-label small fw-semibold">Descanso (seg)</label>
                        <input type="number" id="er-descanso" class="form-control form-control-sm" value="15" min="0" max="120">
                    </div>
                    <div class="col-12 col-md-1">
                        <button class="btn btn-primary btn-sm w-100" onclick="agregarEjercicioRutina()">
                            <i class="fa fa-plus"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
// ── Catálogo de ejercicios disponibles (se carga al inicio) ──────
let catalogoEjercicios = [];

// ── Cargar rutinas al iniciar ────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    cargarRutinas();
    cargarCatalogoEjercicios();
});

// ── RUTINAS ──────────────────────────────────────────────────────

async function cargarRutinas() {
    const res  = await fetch('/15minutos/api/rutinas/listar');
    const data = await res.json();
    const cont = document.getElementById('lista-rutinas');

    if (!data.exito || !data.datos.length) {
        cont.innerHTML = `
            <div class="col-12">
                <div class="alert alert-light border text-center py-5">
                    <i class="fa fa-dumbbell fa-3x text-muted mb-3 d-block"></i>
                    <p class="text-muted mb-3">Aún no hay rutinas creadas.</p>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalRutina" onclick="prepararNuevaRutina()">
                        <i class="fa fa-plus me-1"></i> Crear primera rutina
                    </button>
                </div>
            </div>`;
        return;
    }

    cont.innerHTML = data.datos.map(r => `
        <div class="col-12 col-md-6" id="rutina-card-${r.id_rutina}">
            <div class="card border-0 shadow-sm h-100 ${r.activo == 1 ? 'border-start border-success border-3' : ''}">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            ${r.activo == 1 ? '<span class="badge bg-success mb-1"><i class="fa fa-check-circle me-1"></i>Activa</span>' : ''}
                            <h6 class="fw-bold mb-0">${escHtml(r.nombre)}</h6>
                            <small class="text-muted">${r.descripcion ? escHtml(r.descripcion) : 'Sin descripción'}</small>
                        </div>
                        <span class="badge bg-light text-dark">${Math.round(r.duracion_total_seg/60)} min</span>
                    </div>
                    <p class="text-muted small mb-3">
                        <i class="fa fa-list me-1"></i>${r.num_ejercicios} ejercicio(s)
                    </p>
                    <div class="d-flex gap-2 flex-wrap">
                        <button class="btn btn-sm btn-outline-primary" onclick="abrirEjerciciosRutina(${r.id_rutina}, '${escHtml(r.nombre)}')">
                            <i class="fa fa-list-ol me-1"></i>Ejercicios
                        </button>
                        <button class="btn btn-sm btn-outline-secondary" onclick="editarRutina(${r.id_rutina},'${escHtml(r.nombre)}','${escHtml(r.descripcion||'')}',${r.duracion_total_seg})">
                            <i class="fa fa-edit me-1"></i>Editar
                        </button>
                        ${r.activo != 1 ? `<button class="btn btn-sm btn-success" onclick="activarRutina(${r.id_rutina})">
                            <i class="fa fa-check me-1"></i>Activar
                        </button>` : ''}
                        <button class="btn btn-sm btn-outline-danger" onclick="eliminarRutina(${r.id_rutina})">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `).join('');
}

function prepararNuevaRutina() {
    document.getElementById('rut-id').value = '';
    document.getElementById('rut-nombre').value = '';
    document.getElementById('rut-descripcion').value = '';
    document.getElementById('rut-duracion').value = 900;
    document.getElementById('modal-rutina-titulo').textContent = 'Nueva Rutina';
}

function editarRutina(id, nombre, descripcion, duracion) {
    document.getElementById('rut-id').value = id;
    document.getElementById('rut-nombre').value = nombre;
    document.getElementById('rut-descripcion').value = descripcion;
    document.getElementById('rut-duracion').value = duracion;
    document.getElementById('modal-rutina-titulo').textContent = 'Editar Rutina';
    new bootstrap.Modal(document.getElementById('modalRutina')).show();
}

async function guardarRutina() {
    const btn = document.getElementById('btn-guardar-rutina');
    btn.disabled = true;

    const payload = {
        id_rutina:          parseInt(document.getElementById('rut-id').value) || 0,
        nombre:             document.getElementById('rut-nombre').value.trim(),
        descripcion:        document.getElementById('rut-descripcion').value.trim(),
        duracion_total_seg: parseInt(document.getElementById('rut-duracion').value) || 900,
    };

    const res  = await fetch('/15minutos/api/rutinas/guardar', { method: 'POST', body: JSON.stringify(payload) });
    const data = await res.json();
    btn.disabled = false;

    mostrarAlerta(data.exito ? 'success' : 'danger', data.mensaje);
    if (data.exito) {
        bootstrap.Modal.getInstance(document.getElementById('modalRutina')).hide();
        cargarRutinas();
    }
}

async function activarRutina(id) {
    const res  = await fetch('/15minutos/api/rutinas/activar', { method: 'POST', body: JSON.stringify({ id_rutina: id }) });
    const data = await res.json();
    mostrarAlerta(data.exito ? 'success' : 'danger', data.mensaje);
    if (data.exito) cargarRutinas();
}

async function eliminarRutina(id) {
    if (!confirm('¿Eliminar esta rutina? También se eliminarán sus ejercicios asignados.')) return;
    const res  = await fetch('/15minutos/api/rutinas/eliminar', { method: 'POST', body: JSON.stringify({ id_rutina: id }) });
    const data = await res.json();
    mostrarAlerta(data.exito ? 'success' : 'danger', data.mensaje);
    if (data.exito) cargarRutinas();
}

// ── EJERCICIOS DE RUTINA ─────────────────────────────────────────

async function abrirEjerciciosRutina(idRutina, nombre) {
    document.getElementById('er-id-rutina').value = idRutina;
    document.getElementById('modal-er-nombre').textContent = nombre;
    new bootstrap.Modal(document.getElementById('modalEjerciciosRutina')).show();
    cargarEjerciciosRutina(idRutina);
}

async function cargarEjerciciosRutina(idRutina) {
    const res  = await fetch('/15minutos/api/rutinas/detalle?id=' + idRutina);
    const data = await res.json();
    const cont = document.getElementById('lista-er');

    if (!data.datos || !data.datos.length) {
        cont.innerHTML = '<p class="text-muted small">Sin ejercicios asignados aún.</p>';
        return;
    }

    cont.innerHTML = `
        <table class="table table-sm table-hover align-middle">
            <thead class="table-light"><tr>
                <th>#</th><th>Ejercicio</th><th>Tipo</th>
                <th>Duración</th><th>Descanso</th><th></th>
            </tr></thead>
            <tbody>
                ${data.datos.map(e => `
                    <tr>
                        <td>${e.orden}</td>
                        <td><strong>${escHtml(e.nombre)}</strong><br>
                            <small class="text-muted">${escHtml(e.lottie_file)}</small></td>
                        <td><span class="badge bg-secondary">${e.tipo}</span></td>
                        <td>${e.duracion_seg}s</td>
                        <td>${e.descanso_seg}s</td>
                        <td>
                            <button class="btn btn-sm btn-outline-danger" onclick="quitarEjercicio(${e.id})">
                                <i class="fa fa-times"></i>
                            </button>
                        </td>
                    </tr>
                `).join('')}
            </tbody>
        </table>`;
}

async function agregarEjercicioRutina() {
    const payload = {
        id_rutina:    parseInt(document.getElementById('er-id-rutina').value),
        id_ejercicio: parseInt(document.getElementById('er-ejercicio').value),
        duracion_seg: parseInt(document.getElementById('er-duracion').value) || 45,
        descanso_seg: parseInt(document.getElementById('er-descanso').value) || 15,
    };

    const res  = await fetch('/15minutos/api/rutinas/ejercicio/agregar', { method: 'POST', body: JSON.stringify(payload) });
    const data = await res.json();

    if (data.exito) {
        cargarEjerciciosRutina(payload.id_rutina);
        cargarRutinas(); // refrescar conteo
    } else {
        alert(data.mensaje);
    }
}

async function quitarEjercicio(id) {
    if (!confirm('¿Quitar este ejercicio de la rutina?')) return;
    const idRutina = parseInt(document.getElementById('er-id-rutina').value);
    const res  = await fetch('/15minutos/api/rutinas/ejercicio/quitar', { method: 'POST', body: JSON.stringify({ id }) });
    const data = await res.json();
    if (data.exito) {
        cargarEjerciciosRutina(idRutina);
        cargarRutinas();
    }
}

// ── CATÁLOGO DE EJERCICIOS ───────────────────────────────────────

async function cargarCatalogoEjercicios() {
    const res  = await fetch('/15minutos/api/ejercicios/listar');
    const data = await res.json();
    catalogoEjercicios = data.datos || [];

    const sel = document.getElementById('er-ejercicio');
    sel.innerHTML = catalogoEjercicios.map(e =>
        `<option value="${e.id_ejercicio}">${escHtml(e.nombre)} (${e.tipo})</option>`
    ).join('');
}

// ── HELPERS ──────────────────────────────────────────────────────

function mostrarAlerta(tipo, msg) {
    const el = document.getElementById('alerta-rutinas');
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

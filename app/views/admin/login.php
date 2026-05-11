<?php
$pageTitulo = 'Acceso al Sistema | 15 Minutos';
require __DIR__ . '/../layouts/header.php';
?>

<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card shadow border-0" style="max-width: 420px; width: 100%;">
        <div class="card-body p-5">
            <div class="text-center mb-5">
                <div class="bg-primary bg-gradient text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px;">
                    <i class="fa fa-clock fa-2x"></i>
                </div>
                <h3 class="fw-bold text-dark">15 Minutos</h3>
                <p class="text-muted small">Gestión eficiente de procesos</p>
            </div>

            <div id="alerta-login" class="alert d-none py-2 small" role="alert"></div>

            <form id="form-login">
                <div class="mb-3">
                    <label class="form-label small fw-bold text-secondary text-uppercase">Usuario</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="fa fa-user text-muted"></i></span>
                        <input type="text" id="campo-usuario" class="form-control bg-light border-start-0" placeholder="Ingresa tu usuario" required autofocus>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-bold text-secondary text-uppercase">Contraseña</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="fa fa-lock text-muted"></i></span>
                        <input type="password" id="campo-password" class="form-control bg-light border-start-0" placeholder="••••••••" required>
                    </div>
                </div>

                <button type="submit" id="btn-login" class="btn btn-primary w-100 py-2 fw-bold shadow-sm mb-3">
                    Entrar <i class="fa fa-sign-in-alt ms-2"></i>
                </button>
            </form>
            
            <div class="text-center">
                <span class="text-muted x-small">&copy; <?= date('Y') ?> 15 Minutos</span>
            </div>
        </div>
    </div>
</div>

<script>
    const formLogin = document.getElementById('form-login');
    const btnLogin  = document.getElementById('btn-login');
    const alerta    = document.getElementById('alerta-login');

    function mostrarAlerta(mensaje, tipo = 'danger') {
        alerta.textContent = mensaje;
        alerta.className   = `alert alert-${tipo} py-2 small`;
        alerta.classList.remove('d-none');
    }

    formLogin.addEventListener('submit', async function (e) {
        e.preventDefault();

        const usuario  = document.getElementById('campo-usuario').value.trim();
        const password = document.getElementById('campo-password').value.trim();

        if (!usuario || !password) {
            mostrarAlerta('Completa todos los campos.');
            return;
        }

        btnLogin.disabled    = true;
        btnLogin.innerHTML   = '<span class="spinner-border spinner-border-sm me-2"></span>Cargando...';

        try {
            const res = await axios.post('/15minutos/api/auth/login', { usuario, password });

            if (res.data.exito) {
                mostrarAlerta(res.data.mensaje, 'success');
                setTimeout(() => {
                    window.location.href = '/15minutos/admin/dashboard';
                }, 800);
            } else {
                mostrarAlerta(res.data.mensaje);
                btnLogin.disabled  = false;
                btnLogin.innerHTML = 'Entrar <i class="fa fa-sign-in-alt ms-2"></i>';
            }
        } catch (err) {
            mostrarAlerta('Error de conexión con el servidor.');
            btnLogin.disabled  = false;
            btnLogin.innerHTML = 'Entrar <i class="fa fa-sign-in-alt ms-2"></i>';
        }
    });
</script>

<?php require __DIR__ . '/../layouts/footer.php'; ?>

<?php
if (!isset($desdeRouter)) {
    die("Acceso no autorizado");
}

$token = $_GET['token'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <title>Nueva Contraseña - SCOSTOCK</title>
</head>

<body class="bg-light d-flex justify-content-center align-items-center vh-100">

<div class="card shadow p-4" style="width: 380px;">
    <div class="text-center mb-4">
        <i class="bi bi-key text-success" style="font-size: 3rem;"></i>
        <h3 class="mt-2">Nueva Contraseña</h3>
        <p class="text-muted small">Ingresa tu nueva contraseña</p>
    </div>

    <?php if(isset($_GET['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle"></i>
            <strong>Token inválido o expirado</strong><br>
            Por favor solicita un nuevo enlace de recuperación.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <form action="login.php?action=reset_password" method="POST" id="formPassword">
        <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

        <div class="mb-3">
            <label class="form-label">
                <i class="bi bi-lock"></i> Nueva contraseña
            </label>
            <input type="password" 
                   name="password" 
                   id="password"
                   class="form-control" 
                   placeholder="Mínimo 6 caracteres"
                   minlength="6"
                   required>
            <small class="form-text text-muted">Mínimo 6 caracteres</small>
        </div>

        <div class="mb-3">
            <label class="form-label">
                <i class="bi bi-lock-fill"></i> Confirmar contraseña
            </label>
            <input type="password" 
                   name="password_confirm" 
                   id="password_confirm"
                   class="form-control" 
                   placeholder="Repite la contraseña"
                   required>
            <div id="passwordHelp" class="form-text"></div>
        </div>

        <button type="submit" class="btn btn-success w-100 mb-2" id="btnSubmit">
            <i class="bi bi-check-circle"></i> Restablecer contraseña
        </button>

        <a href="login.php?action=form" class="btn btn-link w-100">
            <i class="bi bi-arrow-left"></i> Volver al inicio de sesión
        </a>
    </form>
</div>

<script>
// Validar que las contraseñas coincidan
const password = document.getElementById('password');
const passwordConfirm = document.getElementById('password_confirm');
const passwordHelp = document.getElementById('passwordHelp');
const btnSubmit = document.getElementById('btnSubmit');

function validarPassword() {
    if (password.value !== passwordConfirm.value) {
        passwordHelp.innerHTML = '<span class="text-danger"><i class="bi bi-x-circle"></i> Las contraseñas no coinciden</span>';
        btnSubmit.disabled = true;
    } else if (password.value.length >= 6) {
        passwordHelp.innerHTML = '<span class="text-success"><i class="bi bi-check-circle"></i> Las contraseñas coinciden</span>';
        btnSubmit.disabled = false;
    } else {
        passwordHelp.innerHTML = '';
        btnSubmit.disabled = true;
    }
}

password.addEventListener('input', validarPassword);
passwordConfirm.addEventListener('input', validarPassword);
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
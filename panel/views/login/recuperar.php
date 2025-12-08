<?php
if (!isset($desdeRouter)) {
    die("Acceso no autorizado");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <title>Recuperar Contraseña - SCOSTOCK</title>
</head>

<body class="bg-light d-flex justify-content-center align-items-center vh-100">

<div class="card shadow p-4" style="width: 380px;">
    <div class="text-center mb-4">
        <i class="bi bi-shield-lock text-primary" style="font-size: 3rem;"></i>
        <h3 class="mt-2">Recuperar Contraseña</h3>
        <p class="text-muted small">Ingresa tu correo electrónico</p>
    </div>

    <?php if(isset($_GET['enviado'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i>
            <strong>Correo enviado</strong><br>
            Si el correo existe en nuestro sistema, recibirás un enlace para restablecer tu contraseña.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if(isset($_GET['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle"></i>
            Ocurrió un error. Por favor intenta nuevamente.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <form action="login.php?action=enviar_token" method="POST">
        <div class="mb-3">
            <label class="form-label">
                <i class="bi bi-envelope"></i> Correo electrónico
            </label>
            <input type="email" 
                   name="email" 
                   class="form-control" 
                   placeholder="tu@email.com"
                   required
                   autocomplete="email">
        </div>

        <button type="submit" class="btn btn-primary w-100 mb-2">
            <i class="bi bi-send"></i> Enviar enlace de recuperación
        </button>

        <a href="login.php?action=form" class="btn btn-link w-100">
            <i class="bi bi-arrow-left"></i> Volver al inicio de sesión
        </a>
    </form>

    <div class="mt-3 text-center">
        <small class="text-muted">
            <i class="bi bi-info-circle"></i> 
            Recibirás un correo con instrucciones para crear una nueva contraseña
        </small>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
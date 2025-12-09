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
    <title>Login SCOSTOCK</title>

    <style>
        .logo-scostock {
            width: 72px;
            height: 72px;
            border-radius: 50%;      /* <-- esto la hace circular */
            object-fit: contain;     /* que se vea completa dentro del círculo */
            background-color: #f8f9fa; /* opcional: fondito claro detrás */
            padding: 6px;            /* opcional: margen interno */
        }
    </style>
</head>

<body class="bg-light d-flex justify-content-center align-items-center vh-100">

<div class="card shadow p-4" style="width: 380px;">
    <div class="text-center mb-3">
        <!--i class="bi bi-box-seam text-primary" style="font-size: 3rem;"></i-->
        <img src="../images/img/logo_scostock.png"
             alt="ScoStock logo"
             class="mb-2 logo-scostock">             
        <h3 class="mt-2">SCOSTOCK</h3>
        <p class="text-muted small">Sistema de Control de Stock</p>
    </div>

    <?php if(isset($_GET['error'])): ?>
        <?php if($_GET['error'] === 'token_invalido'): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bi bi-exclamation-triangle"></i>
                El enlace de recuperación es inválido o ha expirado. 
                <a href="login.php?action=recuperar">Solicita uno nuevo</a>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php else: ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bi bi-x-circle"></i>
                Correo o contraseña incorrectos
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <?php if(isset($_GET['reset'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle"></i>
            <strong>¡Contraseña actualizada!</strong><br>
            Ya puedes iniciar sesión con tu nueva contraseña.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <form action="login.php?action=login" method="POST">
        <div class="mb-3">
            <label class="form-label">
                <i class="bi bi-envelope"></i> Correo electrónico
            </label>
            <input type="email" name="email" class="form-control" placeholder="tu@email.com" required>
        </div>

        <div class="mb-3">
            <label class="form-label">
                <i class="bi bi-lock"></i> Contraseña
            </label>
            <input type="password" name="password" class="form-control" placeholder="••••••" required>
        </div>

        <button type="submit" class="btn btn-primary w-100 mb-2">
            <i class="bi bi-box-arrow-in-right"></i> Iniciar sesión
        </button>

        <a href="login.php?action=recuperar" class="btn btn-link w-100">
            <i class="bi bi-question-circle"></i> ¿Olvidaste tu contraseña?
        </a>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
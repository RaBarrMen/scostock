<?php
if (!isset($desdeRouter)) {
    die("Acceso no autorizado");
}

$rolesRequeridos = $_GET['roles'] ?? 'No especificado';
$usuarioNombre = $_SESSION['nombre'] ?? 'Usuario';
$rolesUsuario = isset($_SESSION['roles']) ? implode(', ', $_SESSION['roles']) : 'Ninguno';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <title>Acceso Denegado - SCOSTOCK</title>
</head>

<body class="bg-light d-flex justify-content-center align-items-center vh-100">

<div class="card shadow p-4" style="width: 450px;">
    <div class="text-center mb-4">
        <i class="bi bi-shield-x text-danger" style="font-size: 4rem;"></i>
        <h3 class="mt-3 text-danger">Acceso Denegado</h3>
        <p class="text-muted">No tienes permisos para acceder a esta sección</p>
    </div>

    <div class="alert alert-danger" role="alert">
        <h6 class="alert-heading">
            <i class="bi bi-exclamation-triangle"></i> Permisos Insuficientes
        </h6>
        <hr>
        <p class="mb-2">
            <strong>Roles requeridos:</strong><br>
            <span class="badge bg-primary"><?= htmlspecialchars($rolesRequeridos) ?></span>
        </p>
        <p class="mb-0">
            <strong>Tus roles actuales:</strong><br>
            <?php if (!empty($rolesUsuario) && $rolesUsuario !== 'Ninguno'): ?>
                <?php foreach (explode(', ', $rolesUsuario) as $rol): ?>
                    <span class="badge bg-secondary"><?= htmlspecialchars($rol) ?></span>
                <?php endforeach; ?>
            <?php else: ?>
                <span class="text-muted">Sin roles asignados</span>
            <?php endif; ?>
        </p>
    </div>

    <div class="card bg-light border-0 mb-3">
        <div class="card-body">
            <h6 class="card-title">
                <i class="bi bi-person-circle"></i> Usuario actual
            </h6>
            <p class="card-text mb-0">
                <strong><?= htmlspecialchars($usuarioNombre) ?></strong><br>
                <small class="text-muted"><?= htmlspecialchars($_SESSION['email'] ?? '') ?></small>
            </p>
        </div>
    </div>

    <div class="d-grid gap-2">
        <a href="login.php?action=login" class="btn btn-primary">
            <i class="bi bi-house"></i> Ir al Inicio
        </a>
        <a href="login.php?action=logout" class="btn btn-outline-danger">
            <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
        </a>
    </div>

    <div class="mt-3 text-center">
        <small class="text-muted">
            <i class="bi bi-info-circle"></i> 
            Si necesitas acceso, contacta al administrador del sistema
        </small>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
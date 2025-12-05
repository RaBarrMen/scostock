<?php
if (!isset($desdeRouter)) {
    die("Acceso no autorizado");
}

include_once __DIR__ . "/../alert.php";

// Verificar que existan los datos
if (!isset($data) || empty($data)) {
    echo '<div class="alert alert-danger">Error: No se encontraron los datos del privilegio.</div>';
    echo '<a href="privilegio.php?action=listar" class="btn btn-secondary">Volver</a>';
    exit;
}
?>

<div class="container mt-4">
    <h1>Editar Privilegio</h1>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="privilegio.php?action=edited">
                
                <!-- Campo oculto con el ID -->
                <input type="hidden" name="id_privilegio" value="<?= htmlspecialchars($data['id_privilegio']) ?>">

                <div class="mb-3">
                    <label class="form-label">Nombre del Privilegio *</label>
                    <input type="text" 
                           name="privilegio" 
                           class="form-control" 
                           value="<?= htmlspecialchars($data['privilegio'] ?? '') ?>"
                           maxlength="100"
                           required>
                    <small class="text-muted">Formato recomendado: <strong>Módulo Acción</strong></small>
                </div>

                <div class="alert alert-danger">
                    <strong><i class="bi bi-exclamation-octagon"></i> ¡PRECAUCIÓN!</strong>
                    <ul class="mb-0">
                        <li>Cambiar el nombre de un privilegio puede romper el sistema de permisos</li>
                        <li>Asegúrate de que el nuevo nombre coincida con el usado en el código PHP</li>
                        <li>Si cambias el nombre, actualiza todas las referencias en <code>$sistema->checarPrivilegio()</code></li>
                    </ul>
                </div>

                <div class="alert alert-info">
                    <strong><i class="bi bi-info-circle"></i> Información:</strong>
                    Este privilegio está siendo utilizado por los roles del sistema. El cambio afectará a todos los usuarios con roles que tienen este privilegio asignado.
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-save"></i> Actualizar
                    </button>
                    <a href="privilegio.php?action=listar" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
if (!isset($desdeRouter)) {
    die("Acceso no autorizado");
}

include_once __DIR__ . "/../alert.php";

// Verificar que existan los datos
if (!isset($data) || empty($data)) {
    echo '<div class="alert alert-danger">Error: No se encontraron los datos del rol.</div>';
    echo '<a href="rol.php?action=listar" class="btn btn-secondary">Volver</a>';
    exit;
}
?>

<div class="container mt-4">
    <h1>Editar Rol</h1>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="rol.php?action=edited">
                
                <!-- Campo oculto con el ID -->
                <input type="hidden" name="id_rol" value="<?= htmlspecialchars($data['id_rol']) ?>">

                <div class="mb-3">
                    <label class="form-label">Nombre del Rol *</label>
                    <input type="text" 
                           name="nombre" 
                           class="form-control" 
                           value="<?= htmlspecialchars($data['nombre'] ?? '') ?>"
                           maxlength="50"
                           required>
                </div>

                <div class="alert alert-warning">
                    <strong><i class="bi bi-exclamation-triangle"></i> Nota importante:</strong>
                    <ul class="mb-0">
                        <li>Cambiar el nombre del rol puede afectar las validaciones del sistema</li>
                        <li>Los roles <strong>ADMIN</strong>, <strong>OPERADOR</strong> y <strong>PROPIETARIO</strong> están definidos en el código</li>
                        <li>Si cambias estos nombres, actualiza también el código PHP</li>
                    </ul>
                </div>

                <div class="alert alert-info">
                    <strong><i class="bi bi-info-circle"></i> Gestión de privilegios:</strong>
                    Para modificar los privilegios de este rol, ve a <a href="rol_privilegio.php?id_rol=<?= $data['id_rol'] ?>">Gestionar Privilegios</a>.
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-save"></i> Actualizar
                    </button>
                    <a href="rol.php?action=listar" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

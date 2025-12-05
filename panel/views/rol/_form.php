<?php
if (!isset($desdeRouter)) {
    die("Acceso no autorizado");
}

include_once __DIR__ . "/../alert.php";
?>

<div class="container mt-4">
    <h1>Nuevo Rol</h1>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="rol.php?action=save">
                
                <div class="mb-3">
                    <label class="form-label">Nombre del Rol *</label>
                    <input type="text" 
                           name="nombre" 
                           class="form-control" 
                           maxlength="50"
                           placeholder="Ej: Gerente, Supervisor, Vendedor"
                           required>
                    <small class="text-muted">El nombre debe ser descriptivo y único</small>
                </div>

                <div class="alert alert-info">
                    <strong><i class="bi bi-lightbulb"></i> Sugerencias de nombres:</strong>
                    <ul class="mb-0">
                        <li><strong>ADMIN</strong> - Acceso total al sistema</li>
                        <li><strong>OPERADOR</strong> - Gestión de productos e inventario</li>
                        <li><strong>PROPIETARIO</strong> - Solo lectura y reportes</li>
                        <li><strong>VENDEDOR</strong> - Registro de ventas</li>
                    </ul>
                </div>

                <div class="alert alert-warning">
                    <strong><i class="bi bi-exclamation-triangle"></i> Recuerda:</strong>
                    Después de crear el rol, debes asignarle privilegios en <a href="rol_privilegio.php">Gestionar Privilegios</a>.
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-save"></i> Guardar
                    </button>
                    <a href="rol.php?action=listar" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

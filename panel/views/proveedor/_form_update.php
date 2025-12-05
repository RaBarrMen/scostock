<?php
if (!isset($desdeRouter)) {
    die("Acceso no autorizado");
}

include_once __DIR__ . "/../alert.php";

// Verificar que existan los datos
if (!isset($data) || empty($data)) {
    echo '<div class="alert alert-danger">Error: No se encontraron los datos del proveedor.</div>';
    echo '<a href="proveedor.php?action=listar" class="btn btn-secondary">Volver</a>';
    exit;
}
?>

<div class="container mt-4">
    <h1>Editar Proveedor</h1>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="proveedor.php?action=edited">
                
                <!-- Campo oculto con el ID -->
                <input type="hidden" name="id_proveedor" value="<?= htmlspecialchars($data['id_proveedor']) ?>">

                <div class="mb-3">
                    <label class="form-label">Nombre *</label>
                    <input type="text" 
                           name="nombre" 
                           class="form-control" 
                           value="<?= htmlspecialchars($data['nombre'] ?? '') ?>"
                           maxlength="100"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Teléfono *</label>
                    <input type="tel" 
                           name="telefono" 
                           class="form-control" 
                           value="<?= htmlspecialchars($data['telefono'] ?? '') ?>"
                           maxlength="20"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email *</label>
                    <input type="email" 
                           name="email" 
                           class="form-control" 
                           value="<?= htmlspecialchars($data['email'] ?? '') ?>"
                           maxlength="100"
                           required>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-save"></i> Actualizar
                    </button>
                    <a href="proveedor.php?action=listar" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

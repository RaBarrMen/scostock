<?php
if (!isset($desdeRouter)) {
    die("Acceso no autorizado");
}

include_once __DIR__ . "/../alert.php";
?>

<div class="container mt-4">
    <h1>Nuevo Proveedor</h1>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="proveedor.php?action=save">
                
                <div class="mb-3">
                    <label class="form-label">Nombre *</label>
                    <input type="text" 
                           name="nombre" 
                           class="form-control" 
                           maxlength="100"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Teléfono *</label>
                    <input type="tel" 
                           name="telefono" 
                           class="form-control" 
                           maxlength="20"
                           placeholder="Ej: 442-123-4567"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email *</label>
                    <input type="email" 
                           name="email" 
                           class="form-control" 
                           maxlength="100"
                           placeholder="ejemplo@correo.com"
                           required>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-save"></i> Guardar
                    </button>
                    <a href="proveedor.php?action=listar" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

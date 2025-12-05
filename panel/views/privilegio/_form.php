<?php
if (!isset($desdeRouter)) {
    die("Acceso no autorizado");
}

include_once __DIR__ . "/../alert.php";
?>

<div class="container mt-4">
    <h1>Nuevo Privilegio</h1>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="privilegio.php?action=save">
                
                <div class="mb-3">
                    <label class="form-label">Nombre del Privilegio *</label>
                    <input type="text" 
                           name="privilegio" 
                           class="form-control" 
                           maxlength="100"
                           placeholder="Ej: Categoria Listar, Producto Nuevo"
                           required>
                    <small class="text-muted">Usa el formato: <strong>Módulo Acción</strong></small>
                </div>

                <div class="alert alert-info">
                    <strong><i class="bi bi-lightbulb"></i> Ejemplos de privilegios:</strong>
                    <div class="row mt-2">
                        <div class="col-md-6">
                            <ul class="small mb-0">
                                <li><code>Categoria Listar</code></li>
                                <li><code>Categoria Nuevo</code></li>
                                <li><code>Categoria Actualizar</code></li>
                                <li><code>Categoria Eliminar</code></li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="small mb-0">
                                <li><code>Producto Listar</code></li>
                                <li><code>Usuario Gestionar</code></li>
                                <li><code>Reporte Ver</code></li>
                                <li><code>Inventario Exportar</code></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="alert alert-warning">
                    <strong><i class="bi bi-exclamation-triangle"></i> Importante:</strong>
                    <ul class="mb-0">
                        <li>El nombre del privilegio debe coincidir exactamente con el usado en el código</li>
                        <li>Después de crear, asigna este privilegio a roles en <a href="rol_privilegio.php">Asignar a Roles</a></li>
                    </ul>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-save"></i> Guardar
                    </button>
                    <a href="privilegio.php?action=listar" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

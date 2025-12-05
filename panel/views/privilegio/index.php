<?php
if (!isset($desdeRouter)) {
    die("Acceso no autorizado");
}

include_once __DIR__ . "/../alert.php";

// Asegurar que $data existe
if (!isset($data) || !is_array($data)) {
    $data = [];
}
?>

<div class="container mt-4">
    <h1>Privilegios del Sistema</h1>

    <div class="btn-group mb-3" role="group">
        <a href="privilegio.php?action=create" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Nuevo Privilegio
        </a>
        <a href="rol_privilegio.php" class="btn btn-info">
            <i class="bi bi-link-45deg"></i> Asignar a Roles
        </a>
        <a href="reportes/reporte.php?tipo=privilegios&formato=pdf" 
           class="btn btn-primary" 
           target="_blank">
            <i class="bi bi-file-pdf"></i> Imprimir
        </a>
    </div>

    <?php if (empty($data)): ?>
        <div class="alert alert-info">
            No hay privilegios registrados.
            <a href="privilegio.php?action=create">Crear el primer privilegio</a>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th width="10%">#</th>
                        <th width="60%">Privilegio</th>
                        <th width="30%">Opciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($data as $p): ?>
                <tr>
                    <td><?= htmlspecialchars($p['id_privilegio'] ?? '') ?></td>
                    <td>
                        <strong><?= htmlspecialchars($p['privilegio'] ?? '') ?></strong>
                    </td>
                    
                    <td>
                        <div class="btn-group btn-group-sm" role="group">
                            <a href="privilegio.php?action=update&id=<?= $p['id_privilegio'] ?>" 
                               class="btn btn-warning" title="Editar">
                                <i class="bi bi-pencil"></i> Editar
                            </a>
                            
                            <button type="button" 
                                    class="btn btn-danger" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#modalEliminar<?= $p['id_privilegio'] ?>">
                                <i class="bi bi-trash"></i> Eliminar
                            </button>
                        </div>
                    </td>
                </tr>

                <!-- Modal de confirmación para cada privilegio -->
                <div class="modal fade" id="modalEliminar<?= $p['id_privilegio'] ?>" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header bg-danger text-white">
                                <h5 class="modal-title">Confirmar Eliminación</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <p class="mb-0">¿Está seguro que desea eliminar el privilegio:</p>
                                <p class="fw-bold fs-5 text-center"><?= htmlspecialchars($p['privilegio']) ?>?</p>
                                <p class="text-danger small">
                                    <i class="bi bi-exclamation-triangle"></i>
                                    Si este privilegio está asignado a roles, no podrá eliminarse.
                                </p>
                                <p class="text-muted small mb-0">Esta acción no se puede deshacer.</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <a href="privilegio.php?action=delete&id=<?= $p['id_privilegio'] ?>" 
                                   class="btn btn-danger">
                                    Sí, Eliminar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <div class="alert alert-info mt-4">
        <strong><i class="bi bi-info-circle"></i> Sobre los privilegios:</strong>
        <ul class="mb-0">
            <li>Los privilegios son permisos específicos que se asignan a los roles</li>
            <li>Se recomienda usar nombres descriptivos y en formato: <code>Módulo Acción</code></li>
            <li>Ejemplos: <code>Categoria Listar</code>, <code>Producto Nuevo</code>, <code>Usuario Eliminar</code></li>
            <li>Los privilegios se agrupan por roles en <a href="rol_privilegio.php">Asignar a Roles</a></li>
        </ul>
    </div>

    <div class="card mt-3">
        <div class="card-header bg-secondary text-white">
            <strong>Convención de nombres recomendada</strong>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h6>Módulos comunes:</h6>
                    <ul class="small">
                        <li>Categoria</li>
                        <li>Producto</li>
                        <li>Proveedor</li>
                        <li>Usuario</li>
                        <li>Rol</li>
                        <li>Privilegio</li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h6>Acciones típicas:</h6>
                    <ul class="small">
                        <li>Listar</li>
                        <li>Nuevo</li>
                        <li>Actualizar</li>
                        <li>Eliminar</li>
                        <li>Ver</li>
                        <li>Exportar</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

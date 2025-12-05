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
    <h1>Roles del Sistema</h1>

    <div class="btn-group mb-3" role="group">
        <a href="rol.php?action=create" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Nuevo Rol
        </a>
        <a href="rol_privilegio.php" class="btn btn-info">
            <i class="bi bi-key"></i> Gestionar Privilegios
        </a>
        <a href="reportes/reporte.php?tipo=roles&formato=pdf" 
           class="btn btn-primary" 
           target="_blank">
            <i class="bi bi-file-pdf"></i> Imprimir
        </a>
    </div>

    <?php if (empty($data)): ?>
        <div class="alert alert-info">
            No hay roles registrados.
            <a href="rol.php?action=create">Crear el primer rol</a>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th width="10%">#</th>
                        <th width="60%">Nombre del Rol</th>
                        <th width="30%">Opciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($data as $r): ?>
                <tr>
                    <td><?= htmlspecialchars($r['id_rol'] ?? '') ?></td>
                    <td>
                        <strong><?= htmlspecialchars($r['nombre'] ?? '') ?></strong>
                    </td>
                    
                    <td>
                        <div class="btn-group btn-group-sm" role="group">
                            <a href="rol.php?action=update&id=<?= $r['id_rol'] ?>" 
                               class="btn btn-warning" title="Editar">
                                <i class="bi bi-pencil"></i> Editar
                            </a>
                            
                            <button type="button" 
                                    class="btn btn-danger" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#modalEliminar<?= $r['id_rol'] ?>">
                                <i class="bi bi-trash"></i> Eliminar
                            </button>
                        </div>
                    </td>
                </tr>

                <!-- Modal de confirmación para cada rol -->
                <div class="modal fade" id="modalEliminar<?= $r['id_rol'] ?>" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header bg-danger text-white">
                                <h5 class="modal-title">Confirmar Eliminación</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <p class="mb-0">¿Está seguro que desea eliminar el rol:</p>
                                <p class="fw-bold fs-5 text-center"><?= htmlspecialchars($r['nombre']) ?>?</p>
                                <p class="text-danger small">
                                    <i class="bi bi-exclamation-triangle"></i>
                                    Si este rol está asignado a usuarios, no podrá eliminarse.
                                </p>
                                <p class="text-muted small mb-0">Esta acción no se puede deshacer.</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <a href="rol.php?action=delete&id=<?= $r['id_rol'] ?>" 
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
        <strong><i class="bi bi-info-circle"></i> Información:</strong>
        <ul class="mb-0">
            <li>Los roles agrupan permisos que se asignan a los usuarios</li>
            <li>Ejemplos comunes: Administrador, Operador, Supervisor, etc.</li>
            <li>No olvides asignar privilegios a los roles en <a href="rol_privilegio.php">Gestionar Privilegios</a></li>
        </ul>
    </div>
</div>

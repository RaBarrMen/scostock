<?php
if (!isset($desdeRouter)) {
    die("Acceso no autorizado");
}

include_once __DIR__ . "/../alert.php";

// Asegurar que $data existe
if (!isset($data) || !is_array($data)) {
    $data = [];
}

// Agrupar privilegios por rol para mejor visualización
$privilegiosPorRol = [];
foreach ($data as $item) {
    $rolNombre = $item['rol'] ?? 'Sin rol';
    if (!isset($privilegiosPorRol[$rolNombre])) {
        $privilegiosPorRol[$rolNombre] = [
            'id_rol' => $item['id_rol'] ?? 0,
            'privilegios' => []
        ];
    }
    $privilegiosPorRol[$rolNombre]['privilegios'][] = $item;
}
?>

<div class="container mt-4">
    <h1>Gestión de Privilegios por Rol</h1>

    <div class="btn-group mb-3" role="group">
        <a href="rol_privilegio.php?action=create" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Asignar Privilegio
        </a>
        <a href="rol.php?action=listar" class="btn btn-info">
            <i class="bi bi-person-badge"></i> Gestionar Roles
        </a>
        <a href="privilegio.php?action=listar" class="btn btn-secondary">
            <i class="bi bi-key"></i> Gestionar Privilegios
        </a>
        <a href="#" class="btn btn-primary" onclick="window.print(); return false;">
            <i class="bi bi-printer"></i> Imprimir
        </a>
    </div>

    <?php if (empty($data)): ?>
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> No hay privilegios asignados a roles.
            <a href="rol_privilegio.php?action=create">Asignar el primer privilegio</a>
        </div>
    <?php else: ?>
        
        <!-- Vista agrupada por rol -->
        <div class="row">
            <?php foreach ($privilegiosPorRol as $rolNombre => $rolData): ?>
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-person-badge"></i> 
                            <?= htmlspecialchars($rolNombre) ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group">
                            <?php foreach ($rolData['privilegios'] as $priv): ?>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <span>
                                    <i class="bi bi-key text-success"></i>
                                    <?= htmlspecialchars($priv['privilegio'] ?? '') ?>
                                </span>
                                <button type="button" 
                                        class="btn btn-danger btn-sm" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#modalEliminar<?= $priv['id_rol'] ?>_<?= $priv['id_privilegio'] ?>">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>

                            <!-- Modal de confirmación -->
                            <div class="modal fade" id="modalEliminar<?= $priv['id_rol'] ?>_<?= $priv['id_privilegio'] ?>" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger text-white">
                                            <h5 class="modal-title">Confirmar Eliminación</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>¿Está seguro que desea eliminar este privilegio del rol?</p>
                                            <div class="alert alert-warning mb-0">
                                                <strong>Rol:</strong> <?= htmlspecialchars($priv['rol']) ?><br>
                                                <strong>Privilegio:</strong> <?= htmlspecialchars($priv['privilegio']) ?>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                            <a href="rol_privilegio.php?action=delete&id_rol=<?= $priv['id_rol'] ?>&id_privilegio=<?= $priv['id_privilegio'] ?>" 
                                               class="btn btn-danger">
                                                Sí, Eliminar
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="mt-2 text-muted small">
                            <i class="bi bi-info-circle"></i> Total: <?= count($rolData['privilegios']) ?> privilegios
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Vista de tabla completa -->
        <div class="card mt-4">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0">Vista Detallada</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th width="40%">Rol</th>
                                <th width="45%">Privilegio</th>
                                <th width="15%">Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($data as $rp): ?>
                        <tr>
                            <td>
                                <i class="bi bi-person-badge text-primary"></i>
                                <strong><?= htmlspecialchars($rp['rol'] ?? '') ?></strong>
                            </td>
                            <td>
                                <i class="bi bi-key text-success"></i>
                                <?= htmlspecialchars($rp['privilegio'] ?? '') ?>
                            </td>
                            <td>
                                <button type="button" 
                                        class="btn btn-danger btn-sm" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#modalEliminarTabla<?= $rp['id_rol'] ?>_<?= $rp['id_privilegio'] ?>">
                                    <i class="bi bi-trash"></i> Eliminar
                                </button>
                            </td>
                        </tr>

                        <!-- Modal para tabla -->
                        <div class="modal fade" id="modalEliminarTabla<?= $rp['id_rol'] ?>_<?= $rp['id_privilegio'] ?>" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header bg-danger text-white">
                                        <h5 class="modal-title">Confirmar Eliminación</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>¿Está seguro que desea eliminar este privilegio del rol?</p>
                                        <div class="alert alert-warning mb-0">
                                            <strong>Rol:</strong> <?= htmlspecialchars($rp['rol']) ?><br>
                                            <strong>Privilegio:</strong> <?= htmlspecialchars($rp['privilegio']) ?>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                        <a href="rol_privilegio.php?action=delete&id_rol=<?= $rp['id_rol'] ?>&id_privilegio=<?= $rp['id_privilegio'] ?>" 
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
            </div>
        </div>
    <?php endif; ?>

    <div class="alert alert-info mt-4">
        <strong><i class="bi bi-info-circle"></i> Información:</strong>
        <ul class="mb-0">
            <li>Cada rol puede tener múltiples privilegios asignados</li>
            <li>Los usuarios heredan todos los privilegios de sus roles</li>
            <li>Si un usuario tiene múltiples roles, tendrá todos los privilegios de cada rol</li>
        </ul>
    </div>
</div>

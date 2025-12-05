<?php
if (!isset($desdeRouter)) {
    die("Acceso no autorizado");
}

include_once __DIR__ . "/../alert.php";

// Asegurar que $data existe
if (!isset($data) || !is_array($data)) {
    $data = [];
}

// Agrupar roles por usuario para mejor visualización
$rolesPorUsuario = [];
foreach ($data as $item) {
    $usuarioNombre = $item['usuario'] ?? 'Sin usuario';
    if (!isset($rolesPorUsuario[$usuarioNombre])) {
        $rolesPorUsuario[$usuarioNombre] = [
            'id_usuario' => $item['id_usuario'] ?? 0,
            'email' => $item['email'] ?? '',
            'roles' => []
        ];
    }
    $rolesPorUsuario[$usuarioNombre]['roles'][] = $item;
}
?>

<div class="container mt-4">
    <h1>Gestión de Roles por Usuario</h1>

    <div class="btn-group mb-3" role="group">
        <a href="usuario_rol.php?action=create" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Asignar Rol
        </a>
        <a href="usuario.php?action=listar" class="btn btn-info">
            <i class="bi bi-people"></i> Gestionar Usuarios
        </a>
        <a href="rol.php?action=listar" class="btn btn-secondary">
            <i class="bi bi-person-badge"></i> Gestionar Roles
        </a>
        <a href="reportes/reporte.php?tipo=usuario_rol&formato=pdf" 
           class="btn btn-primary" 
           target="_blank">
            <i class="bi bi-file-pdf"></i> Imprimir
        </a>
    </div>

    <?php if (empty($data)): ?>
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> No hay roles asignados a usuarios.
            <a href="usuario_rol.php?action=create">Asignar el primer rol</a>
        </div>
    <?php else: ?>
        
        <!-- Vista agrupada por usuario -->
        <div class="row">
            <?php foreach ($rolesPorUsuario as $usuarioNombre => $userData): ?>
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-person-circle"></i> 
                            <?= htmlspecialchars($usuarioNombre) ?>
                        </h5>
                        <small><?= htmlspecialchars($userData['email']) ?></small>
                    </div>
                    <div class="card-body">
                        <?php if (empty($userData['roles'])): ?>
                            <p class="text-muted">Sin roles asignados</p>
                        <?php else: ?>
                            <div class="list-group">
                                <?php foreach ($userData['roles'] as $ur): ?>
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>
                                        <i class="bi bi-person-badge text-primary"></i>
                                        <strong><?= htmlspecialchars($ur['rol'] ?? '') ?></strong>
                                    </span>
                                    <button type="button" 
                                            class="btn btn-danger btn-sm" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#modalEliminar<?= $ur['id_usuario'] ?>_<?= $ur['id_rol'] ?>">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>

                                <!-- Modal de confirmación -->
                                <div class="modal fade" id="modalEliminar<?= $ur['id_usuario'] ?>_<?= $ur['id_rol'] ?>" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger text-white">
                                                <h5 class="modal-title">Confirmar Eliminación</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>¿Está seguro que desea eliminar este rol del usuario?</p>
                                                <div class="alert alert-warning mb-0">
                                                    <strong>Usuario:</strong> <?= htmlspecialchars($ur['usuario']) ?><br>
                                                    <strong>Rol:</strong> <?= htmlspecialchars($ur['rol']) ?>
                                                </div>
                                                <p class="text-danger small mt-2 mb-0">
                                                    <i class="bi bi-exclamation-triangle"></i>
                                                    El usuario perderá todos los privilegios asociados a este rol.
                                                </p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                <a href="usuario_rol.php?action=delete&id_usuario=<?= $ur['id_usuario'] ?>&id_rol=<?= $ur['id_rol'] ?>" 
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
                                <i class="bi bi-info-circle"></i> Total: <?= count($userData['roles']) ?> rol(es)
                            </div>
                        <?php endif; ?>
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
                                <th width="45%">Usuario</th>
                                <th width="40%">Rol</th>
                                <th width="15%">Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($data as $ur): ?>
                        <tr>
                            <td>
                                <i class="bi bi-person-circle text-info"></i>
                                <strong><?= htmlspecialchars($ur['usuario'] ?? '') ?></strong>
                                <br>
                                <small class="text-muted"><?= htmlspecialchars($ur['email'] ?? '') ?></small>
                            </td>
                            <td>
                                <i class="bi bi-person-badge text-primary"></i>
                                <?= htmlspecialchars($ur['rol'] ?? '') ?>
                            </td>
                            <td>
                                <button type="button" 
                                        class="btn btn-danger btn-sm" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#modalEliminarTabla<?= $ur['id_usuario'] ?>_<?= $ur['id_rol'] ?>">
                                    <i class="bi bi-trash"></i> Eliminar
                                </button>
                            </td>
                        </tr>

                        <!-- Modal para tabla -->
                        <div class="modal fade" id="modalEliminarTabla<?= $ur['id_usuario'] ?>_<?= $ur['id_rol'] ?>" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header bg-danger text-white">
                                        <h5 class="modal-title">Confirmar Eliminación</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>¿Está seguro que desea eliminar este rol del usuario?</p>
                                        <div class="alert alert-warning mb-0">
                                            <strong>Usuario:</strong> <?= htmlspecialchars($ur['usuario']) ?><br>
                                            <strong>Rol:</strong> <?= htmlspecialchars($ur['rol']) ?>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                        <a href="usuario_rol.php?action=delete&id_usuario=<?= $ur['id_usuario'] ?>&id_rol=<?= $ur['id_rol'] ?>" 
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
            <li>Los usuarios pueden tener múltiples roles asignados</li>
            <li>Cada rol otorga un conjunto específico de privilegios</li>
            <li>Si un usuario tiene múltiples roles, tendrá la suma de todos sus privilegios</li>
            <li>Un usuario sin roles asignados no podrá acceder a ninguna funcionalidad del sistema</li>
        </ul>
    </div>

    <div class="card mt-3">
        <div class="card-header bg-secondary text-white">
            <strong>Recomendaciones de asignación</strong>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <h6><i class="bi bi-shield-check"></i> Administradores</h6>
                    <p class="small">Asignar rol <strong>ADMIN</strong> para acceso completo al sistema.</p>
                </div>
                <div class="col-md-4">
                    <h6><i class="bi bi-gear"></i> Personal Operativo</h6>
                    <p class="small">Asignar rol <strong>OPERADOR</strong> para gestión diaria.</p>
                </div>
                <div class="col-md-4">
                    <h6><i class="bi bi-eye"></i> Solo Consulta</h6>
                    <p class="small">Asignar rol <strong>PROPIETARIO</strong> para reportes y visualización.</p>
                </div>
            </div>
        </div>
    </div>
</div>

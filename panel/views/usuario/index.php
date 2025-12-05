<?php
if (!isset($desdeRouter)) {
    die("Acceso no autorizado");
}

include_once __DIR__ . "/../alert.php";

// Verificar rol (solo ADMIN)
$esAdmin = in_array('ADMIN', array_map('strtoupper', $_SESSION['roles'] ?? []));

// Asegurar que $data existe
if (!isset($data) || !is_array($data)) {
    $data = [];
}
?>

<div class="container mt-4">
    <h1>Usuarios</h1>

    <div class="btn-group mb-3" role="group">
        <a href="usuario.php?action=create" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Nuevo Usuario
        </a>
        <a href="#" class="btn btn-primary" onclick="window.print(); return false;">
            <i class="bi bi-printer"></i> Imprimir
        </a>
    </div>

    <?php if (empty($data)): ?>
        <div class="alert alert-info">
            No hay usuarios registrados.
            <a href="usuario.php?action=create">Crear el primer usuario</a>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Fecha Creación</th>
                        <th>Activo</th>
                        <th>Opciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($data as $u): ?>
                <tr>
                    <td><?= htmlspecialchars($u['id_usuario'] ?? '') ?></td>
                    <td><?= htmlspecialchars($u['nombre'] ?? '') ?></td>
                    <td><?= htmlspecialchars($u['email'] ?? '') ?></td>
                    <td><?= isset($u['created_at']) ? date('d/m/Y', strtotime($u['created_at'])) : '' ?></td>
                    <td>
                        <?php if (($u['activo'] ?? 0) == 1): ?>
                            <span class="badge bg-success">Activo</span>
                        <?php else: ?>
                            <span class="badge bg-secondary">Inactivo</span>
                        <?php endif; ?>
                    </td>
                    
                    <td>
                        <div class="btn-group btn-group-sm" role="group">
                            <a href="usuario.php?action=update&id=<?= $u['id_usuario'] ?>" 
                               class="btn btn-warning" title="Editar">
                                Editar
                            </a>
                            
                            <button type="button" 
                                    class="btn btn-danger" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#modalEliminar<?= $u['id_usuario'] ?>">
                                Eliminar
                            </button>
                        </div>
                    </td>
                </tr>

                <!-- Modal de confirmación para cada usuario -->
                <div class="modal fade" id="modalEliminar<?= $u['id_usuario'] ?>" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header bg-danger text-white">
                                <h5 class="modal-title">Confirmar Eliminación</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <p class="mb-0">¿Está seguro que desea eliminar el usuario:</p>
                                <p class="fw-bold fs-5 text-center"><?= htmlspecialchars($u['nombre']) ?>?</p>
                                <p class="text-muted small">Email: <?= htmlspecialchars($u['email']) ?></p>
                                <p class="text-muted small mb-0">Esta acción no se puede deshacer.</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <a href="usuario.php?action=delete&id=<?= $u['id_usuario'] ?>" 
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
</div>
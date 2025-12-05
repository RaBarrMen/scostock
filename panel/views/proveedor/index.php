<?php
if (!isset($desdeRouter)) {
    die("Acceso no autorizado");
}

include_once __DIR__ . "/../alert.php";

// Verificar roles
$esAdmin = in_array('ADMIN', array_map('strtoupper', $_SESSION['roles'] ?? []));
$esOperador = in_array('OPERADOR', array_map('strtoupper', $_SESSION['roles'] ?? []));
$puedeEditar = $esAdmin || $esOperador;

// Asegurar que $data existe
if (!isset($data) || !is_array($data)) {
    $data = [];
}
?>

<div class="container mt-4">
    <h1>Proveedores</h1>

    <div class="btn-group mb-3" role="group">
        <?php if ($puedeEditar): ?>
            <a href="proveedor.php?action=create" class="btn btn-success">
                <i class="bi bi-plus-circle"></i> Nuevo Proveedor
            </a>
        <?php endif; ?>
        <a href="#" class="btn btn-primary" onclick="window.print(); return false;">
            <i class="bi bi-printer"></i> Imprimir
        </a>
    </div>

    <?php if (empty($data)): ?>
        <div class="alert alert-info">
            No hay proveedores registrados.
            <?php if ($puedeEditar): ?>
                <a href="proveedor.php?action=create">Crear el primer proveedor</a>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Teléfono</th>
                        <th>Email</th>
                        <?php if ($puedeEditar || $esAdmin): ?>
                            <th>Opciones</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($data as $prov): ?>
                <tr>
                    <td><?= htmlspecialchars($prov['id_proveedor'] ?? '') ?></td>
                    <td><?= htmlspecialchars($prov['nombre'] ?? '') ?></td>
                    <td><?= htmlspecialchars($prov['telefono'] ?? '') ?></td>
                    <td><?= htmlspecialchars($prov['email'] ?? '') ?></td>
                    
                    <?php if ($puedeEditar || $esAdmin): ?>
                    <td>
                        <div class="btn-group btn-group-sm" role="group">
                            <?php if ($puedeEditar): ?>
                                <a href="proveedor.php?action=update&id=<?= $prov['id_proveedor'] ?>" 
                                   class="btn btn-warning" title="Editar">
                                    Editar
                                </a>
                            <?php endif; ?>
                            
                            <?php if ($esAdmin): ?>
                                <button type="button" 
                                        class="btn btn-danger" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#modalEliminar<?= $prov['id_proveedor'] ?>">
                                    Eliminar
                                </button>
                            <?php endif; ?>
                        </div>
                    </td>
                    <?php endif; ?>
                </tr>

                <!-- Modal de confirmación para cada proveedor -->
                <?php if ($esAdmin): ?>
                <div class="modal fade" id="modalEliminar<?= $prov['id_proveedor'] ?>" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header bg-danger text-white">
                                <h5 class="modal-title">Confirmar Eliminación</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <p class="mb-0">¿Está seguro que desea eliminar el proveedor:</p>
                                <p class="fw-bold fs-5 text-center"><?= htmlspecialchars($prov['nombre']) ?>?</p>
                                <p class="text-muted small mb-0">Esta acción no se puede deshacer.</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <a href="proveedor.php?action=delete&id=<?= $prov['id_proveedor'] ?>" 
                                   class="btn btn-danger">
                                    Sí, Eliminar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

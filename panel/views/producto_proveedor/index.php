<?php
if (!isset($desdeRouter)) {
    die("Acceso no autorizado");
}

include_once __DIR__ . "/../alert.php";

// Asegurar que $data existe
if (!isset($data) || !is_array($data)) {
    $data = [];
}

// Agrupar productos por proveedor
$productosPorProveedor = [];
foreach ($data as $item) {
    $proveedorNombre = $item['proveedor'] ?? 'Sin proveedor';
    if (!isset($productosPorProveedor[$proveedorNombre])) {
        $productosPorProveedor[$proveedorNombre] = [
            'id_proveedor' => $item['id_proveedor'] ?? 0,
            'telefono' => $item['proveedor_telefono'] ?? '',
            'email' => $item['proveedor_email'] ?? '',
            'productos' => []
        ];
    }
    $productosPorProveedor[$proveedorNombre]['productos'][] = $item;
}

// Verificar permisos de edición
$puedeEditar = in_array('ADMIN', array_map('strtoupper', $_SESSION['roles'] ?? [])) || 
               in_array('OPERADOR', array_map('strtoupper', $_SESSION['roles'] ?? []));
?>

<div class="container mt-4">
    <h1>Gestión de Productos por Proveedor</h1>

    <div class="btn-group mb-3" role="group">
        <?php if ($puedeEditar): ?>
        <a href="producto_proveedor.php?action=create" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Asignar Proveedor
        </a>
        <?php endif; ?>
        <a href="producto.php?action=listar" class="btn btn-info">
            <i class="bi bi-box-seam"></i> Gestionar Productos
        </a>
        <a href="proveedor.php?action=listar" class="btn btn-secondary">
            <i class="bi bi-truck"></i> Gestionar Proveedores
        </a>
        <a href="reportes/reporte.php?tipo=producto_proveedor&formato=pdf" 
           class="btn btn-primary" 
           target="_blank">
            <i class="bi bi-file-pdf"></i> Imprimir
        </a>
    </div>

    <?php if (empty($data)): ?>
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> No hay relaciones producto-proveedor registradas.
            <?php if ($puedeEditar): ?>
                <a href="producto_proveedor.php?action=create">Asignar el primer proveedor</a>
            <?php endif; ?>
        </div>
    <?php else: ?>
        
        <!-- Vista agrupada por proveedor -->
        <div class="row">
            <?php foreach ($productosPorProveedor as $proveedorNombre => $proveedorData): ?>
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-truck"></i> 
                            <?= htmlspecialchars($proveedorNombre) ?>
                        </h5>
                        <small>
                            <i class="bi bi-telephone"></i> <?= htmlspecialchars($proveedorData['telefono']) ?> | 
                            <i class="bi bi-envelope"></i> <?= htmlspecialchars($proveedorData['email']) ?>
                        </small>
                    </div>
                    <div class="card-body">
                        <?php if (empty($proveedorData['productos'])): ?>
                            <p class="text-muted">Sin productos asignados</p>
                        <?php else: ?>
                            <div class="list-group">
                                <?php foreach ($proveedorData['productos'] as $pp): ?>
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>
                                        <i class="bi bi-box-seam text-success"></i>
                                        <strong><?= htmlspecialchars($pp['producto'] ?? '') ?></strong>
                                        <br>
                                        <small class="text-muted">SKU: <?= htmlspecialchars($pp['producto_sku'] ?? '') ?></small>
                                    </span>
                                    <?php if ($puedeEditar): ?>
                                    <button type="button" 
                                            class="btn btn-danger btn-sm" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#modalEliminar<?= $pp['id_producto'] ?>_<?= $pp['id_proveedor'] ?>">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                    <?php endif; ?>
                                </div>

                                <!-- Modal de confirmación -->
                                <?php if ($puedeEditar): ?>
                                <div class="modal fade" id="modalEliminar<?= $pp['id_producto'] ?>_<?= $pp['id_proveedor'] ?>" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger text-white">
                                                <h5 class="modal-title">Confirmar Eliminación</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>¿Está seguro que desea eliminar esta relación?</p>
                                                <div class="alert alert-warning mb-0">
                                                    <strong>Producto:</strong> <?= htmlspecialchars($pp['producto']) ?><br>
                                                    <strong>Proveedor:</strong> <?= htmlspecialchars($pp['proveedor']) ?>
                                                </div>
                                                <p class="text-danger small mt-2 mb-0">
                                                    <i class="bi bi-exclamation-triangle"></i>
                                                    El producto dejará de estar asociado a este proveedor.
                                                </p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                <a href="producto_proveedor.php?action=delete&id_producto=<?= $pp['id_producto'] ?>&id_proveedor=<?= $pp['id_proveedor'] ?>" 
                                                   class="btn btn-danger">
                                                    Sí, Eliminar
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                            <div class="mt-2 text-muted small">
                                <i class="bi bi-info-circle"></i> Total: <?= count($proveedorData['productos']) ?> producto(s)
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
                                <th width="45%">Producto</th>
                                <th width="40%">Proveedor</th>
                                <?php if ($puedeEditar): ?>
                                <th width="15%">Opciones</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($data as $pp): ?>
                        <tr>
                            <td>
                                <i class="bi bi-box-seam text-success"></i>
                                <strong><?= htmlspecialchars($pp['producto'] ?? '') ?></strong>
                                <br>
                                <small class="text-muted">SKU: <?= htmlspecialchars($pp['producto_sku'] ?? '') ?></small>
                            </td>
                            <td>
                                <i class="bi bi-truck text-primary"></i>
                                <?= htmlspecialchars($pp['proveedor'] ?? '') ?>
                                <br>
                                <small class="text-muted">
                                    <i class="bi bi-telephone"></i> <?= htmlspecialchars($pp['proveedor_telefono'] ?? '') ?>
                                </small>
                            </td>
                            <?php if ($puedeEditar): ?>
                            <td>
                                <button type="button" 
                                        class="btn btn-danger btn-sm" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#modalEliminarTabla<?= $pp['id_producto'] ?>_<?= $pp['id_proveedor'] ?>">
                                    <i class="bi bi-trash"></i> Eliminar
                                </button>
                            </td>
                            <?php endif; ?>
                        </tr>

                        <!-- Modal para tabla -->
                        <?php if ($puedeEditar): ?>
                        <div class="modal fade" id="modalEliminarTabla<?= $pp['id_producto'] ?>_<?= $pp['id_proveedor'] ?>" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header bg-danger text-white">
                                        <h5 class="modal-title">Confirmar Eliminación</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>¿Está seguro que desea eliminar esta relación?</p>
                                        <div class="alert alert-warning mb-0">
                                            <strong>Producto:</strong> <?= htmlspecialchars($pp['producto']) ?><br>
                                            <strong>Proveedor:</strong> <?= htmlspecialchars($pp['proveedor']) ?>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                        <a href="producto_proveedor.php?action=delete&id_producto=<?= $pp['id_producto'] ?>&id_proveedor=<?= $pp['id_proveedor'] ?>" 
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
            </div>
        </div>
    <?php endif; ?>

    <div class="alert alert-info mt-4">
        <strong><i class="bi bi-info-circle"></i> Información:</strong>
        <ul class="mb-0">
            <li>Un producto puede tener múltiples proveedores</li>
            <li>Esta relación facilita la gestión de compras y stock</li>
            <li>Los vendedores pueden filtrar productos por proveedor en el catálogo</li>
        </ul>
    </div>
</div>


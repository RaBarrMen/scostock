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
    <h1>Productos</h1>

    <div class="btn-group mb-3" role="group">
        <?php if ($puedeEditar): ?>
            <a href="producto.php?action=create" class="btn btn-success">
                <i class="bi bi-plus-circle"></i> Nuevo Producto
            </a>
        <?php endif; ?>
        <a href="reportes/reporte.php?tipo=productos&formato=pdf" 
           class="btn btn-primary" 
           target="_blank">
            <i class="bi bi-file-pdf"></i> Imprimir
        </a>
    </div>

    <?php if (empty($data)): ?>
        <div class="alert alert-info">
            No hay productos registrados.
            <?php if ($puedeEditar): ?>
                <a href="producto.php?action=create">Crear el primer producto</a>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Imagen</th>
                        <th>SKU</th>
                        <th>Nombre</th>
                        <th>Categoría</th>
                        <th>Precio Venta</th>
                        <th>Min. Stock</th>
                        <th>Activo</th>
                        <?php if ($puedeEditar || $esAdmin): ?>
                            <th>Opciones</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($data as $p): ?>
                <tr>
                    <td><?= htmlspecialchars($p['id_producto'] ?? '') ?></td>

                    <td>
                        <?php if (!empty($p['imagen'])): ?>
                            <img src="../images/producto/<?= htmlspecialchars($p['imagen']) ?>"
                                 width="65" height="65" 
                                 class="img-thumbnail"
                                 style="object-fit:cover;"
                                 alt="Imagen producto">
                        <?php else: ?>
                            <span class="text-muted">Sin imagen</span>
                        <?php endif; ?>
                    </td>

                    <td><?= htmlspecialchars($p['sku'] ?? '') ?></td>
                    <td><?= htmlspecialchars($p['nombre'] ?? '') ?></td>
                    <td><?= htmlspecialchars($p['categoria'] ?? '') ?></td>
                    <td>$<?= number_format($p['precio_venta'] ?? 0, 2) ?></td>
                    <td><?= htmlspecialchars($p['min_stock'] ?? 0) ?></td>

                    <td>
                        <?php if (($p['activo'] ?? 0) == 1): ?>
                            <span class="badge bg-success">Activo</span>
                        <?php else: ?>
                            <span class="badge bg-secondary">Inactivo</span>
                        <?php endif; ?>
                    </td>

                    <?php if ($puedeEditar || $esAdmin): ?>
                    <td>
                        <div class="btn-group btn-group-sm" role="group">
                            <?php if ($puedeEditar): ?>
                                <a href="producto.php?action=update&id=<?= $p['id_producto'] ?>" 
                                   class="btn btn-warning" title="Editar">
                                    Editar
                                </a>
                            <?php endif; ?>
                            
                            <?php if ($esAdmin): ?>
                                <button type="button" 
                                        class="btn btn-danger" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#modalEliminar<?= $p['id_producto'] ?>">
                                    Eliminar
                                </button>
                            <?php endif; ?>
                        </div>
                    </td>
                    <?php endif; ?>
                </tr>

                <!-- Modal de confirmación para cada producto -->
                <?php if ($esAdmin): ?>
                <div class="modal fade" id="modalEliminar<?= $p['id_producto'] ?>" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header bg-danger text-white">
                                <h5 class="modal-title">Confirmar Eliminación</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <p class="mb-0">¿Está seguro que desea eliminar el producto:</p>
                                <p class="fw-bold fs-5 text-center"><?= htmlspecialchars($p['nombre']) ?>?</p>
                                <p class="text-muted small mb-0">Esta acción no se puede deshacer.</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <a href="producto.php?action=delete&id=<?= $p['id_producto'] ?>" 
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
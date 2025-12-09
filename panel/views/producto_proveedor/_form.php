<?php
if (!isset($desdeRouter)) {
    die("Acceso no autorizado");
}

include_once __DIR__ . "/../alert.php";

// Verificar que existan productos y proveedores
if (!isset($productos) || empty($productos)) {
    echo '<div class="alert alert-warning">No hay productos disponibles. <a href="../producto.php?action=create">Crear un producto</a></div>';
    $productos = [];
}

if (!isset($proveedores) || empty($proveedores)) {
    echo '<div class="alert alert-warning">No hay proveedores disponibles. <a href="../proveedor.php?action=create">Crear un proveedor</a></div>';
    $proveedores = [];
}
?>

<div class="container mt-4">
    <h1>Asignar Proveedor a Producto</h1>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="producto_proveedor.php?action=save" id="formAsignar">
                
                <div class="mb-3">
                    <label class="form-label">Producto *</label>
                    <select name="id_producto" class="form-control" id="selectProducto" required>
                        <option value="">Seleccione un producto...</option>
                        <?php foreach ($productos as $p): ?>
                            <option value="<?= $p['id_producto'] ?>" data-sku="<?= htmlspecialchars($p['sku']) ?>">
                                <?= htmlspecialchars($p['nombre']) ?> (SKU: <?= htmlspecialchars($p['sku']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <small class="text-muted">Selecciona el producto al que deseas asignar un proveedor</small>
                </div>

                <div class="mb-3">
                    <label class="form-label">Proveedor *</label>
                    <select name="id_proveedor" class="form-control" id="selectProveedor" required>
                        <option value="">Seleccione un proveedor...</option>
                        <?php foreach ($proveedores as $pr): ?>
                            <option value="<?= $pr['id_proveedor'] ?>">
                                <?= htmlspecialchars($pr['nombre']) ?> - <?= htmlspecialchars($pr['telefono']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <small class="text-muted">Selecciona el proveedor que deseas asignar</small>
                </div>

                <div class="alert alert-info">
                    <strong><i class="bi bi-lightbulb"></i> Nota:</strong>
                    <ul class="mb-0">
                        <li>Un producto puede tener múltiples proveedores</li>
                        <li>Esto permite comparar precios y disponibilidad</li>
                        <li>Los vendedores podrán filtrar productos por proveedor</li>
                    </ul>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-save"></i> Asignar Proveedor
                    </button>
                    <a href="producto_proveedor.php?action=listar" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>


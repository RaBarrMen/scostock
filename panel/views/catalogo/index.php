<?php
if (!isset($desdeRouter)) {
    die("Acceso no autorizado");
}

// Verificar si es vendedor (para mostrar mensaje de bienvenida)
$esVendedor = in_array('VENDEDOR', array_map('strtoupper', $_SESSION['roles'] ?? []));

// Asegurar que los datos existen
if (!isset($productos) || !is_array($productos)) {
    $productos = [];
}

if (!isset($categorias) || !is_array($categorias)) {
    $categorias = [];
}

$categoriaSeleccionada = $_GET['categoria'] ?? null;
?>

<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>
                <i class="bi bi-shop"></i> Catálogo de Productos
            </h1>
            <?php if ($esVendedor): ?>
                <p class="text-muted">
                    <i class="bi bi-info-circle"></i> 
                    Bienvenido, aquí puedes consultar nuestro inventario disponible
                </p>
            <?php endif; ?>
        </div>
        <div class="col-md-4 text-end">
            <span class="badge bg-primary" style="font-size: 1rem;">
                <i class="bi bi-box-seam"></i> 
                <?= count($productos) ?> productos disponibles
            </span>
        </div>
    </div>

    <!-- Filtro por categoría -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-3">
                    <label class="form-label fw-bold">
                        <i class="bi bi-funnel"></i> Filtrar por categoría:
                    </label>
                </div>
                <div class="col-md-7">
                    <select class="form-select" id="filtroCategoria" onchange="filtrarCategoria()">
                        <option value="">📦 Todas las categorías</option>
                        <?php foreach ($categorias as $cat): ?>
                            <option value="<?= $cat['id_categoria'] ?>" 
                                    <?= $categoriaSeleccionada == $cat['id_categoria'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <a href="catalogo.php?action=listar" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-x-circle"></i> Limpiar
                    </a>
                </div>
            </div>
        </div>
    </div>

    <?php if (empty($productos)): ?>
        <div class="alert alert-info text-center py-5">
            <i class="bi bi-inbox" style="font-size: 3rem;"></i>
            <h4 class="mt-3">No hay productos disponibles</h4>
            <?php if ($categoriaSeleccionada): ?>
                <p>No se encontraron productos en esta categoría.</p>
                <a href="catalogo.php?action=listar" class="btn btn-primary">
                    <i class="bi bi-arrow-left"></i> Ver todos los productos
                </a>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <!-- Grid de productos (Cards) -->
        <div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-4">
            <?php foreach ($productos as $prod): ?>
            <div class="col">
                <div class="card h-100 shadow-sm hover-card">
                    <!-- Imagen del producto -->
                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                         style="height: 200px; overflow: hidden;">
                        <?php if (!empty($prod['imagen'])): ?>
                            <img src="../images/producto/<?= htmlspecialchars($prod['imagen']) ?>" 
                                 alt="<?= htmlspecialchars($prod['nombre']) ?>"
                                 style="max-width: 100%; max-height: 100%; object-fit: contain;">
                        <?php else: ?>
                            <i class="bi bi-box-seam text-muted" style="font-size: 4rem;"></i>
                        <?php endif; ?>
                    </div>

                    <div class="card-body d-flex flex-column">
                        <!-- Categoría -->
                        <span class="badge bg-secondary mb-2 align-self-start">
                            <i class="bi bi-tag"></i> 
                            <?= htmlspecialchars($prod['categoria'] ?? 'Sin categoría') ?>
                        </span>

                        <!-- Nombre del producto -->
                        <h5 class="card-title">
                            <?= htmlspecialchars($prod['nombre']) ?>
                        </h5>

                        <!-- SKU -->
                        <p class="text-muted small mb-2">
                            <strong>SKU:</strong> <?= htmlspecialchars($prod['sku']) ?>
                        </p>

                        <!-- Precio -->
                        <div class="mt-auto">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted small">Precio de venta:</span>
                                <h4 class="text-success mb-0">
                                    $<?= number_format($prod['precio_venta'], 2) ?>
                                </h4>
                            </div>

                            <!-- Unidad de medida -->
                            <p class="text-muted small mb-2">
                                <i class="bi bi-rulers"></i> 
                                por <?= htmlspecialchars($prod['unidad_medida']) ?>
                            </p>

                            <!-- Botón Ver Detalles -->
                            <button class="btn btn-outline-primary btn-sm w-100" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#modalDetalle<?= $prod['id_producto'] ?>">
                                <i class="bi bi-eye"></i> Ver detalles
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal de detalles del producto -->
            <div class="modal fade" id="modalDetalle<?= $prod['id_producto'] ?>" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title">
                                <i class="bi bi-info-circle"></i> 
                                Detalles del Producto
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Imagen grande -->
                            <div class="text-center mb-3">
                                <?php if (!empty($prod['imagen'])): ?>
                                    <img src="../images/producto/<?= htmlspecialchars($prod['imagen']) ?>" 
                                         alt="<?= htmlspecialchars($prod['nombre']) ?>"
                                         class="img-fluid rounded"
                                         style="max-height: 300px;">
                                <?php else: ?>
                                    <i class="bi bi-box-seam text-muted" style="font-size: 8rem;"></i>
                                <?php endif; ?>
                            </div>

                            <h4><?= htmlspecialchars($prod['nombre']) ?></h4>
                            
                            <table class="table table-sm">
                                <tr>
                                    <th width="40%">SKU:</th>
                                    <td><?= htmlspecialchars($prod['sku']) ?></td>
                                </tr>
                                <tr>
                                    <th>Categoría:</th>
                                    <td>
                                        <span class="badge bg-secondary">
                                            <?= htmlspecialchars($prod['categoria'] ?? 'Sin categoría') ?>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Unidad de Medida:</th>
                                    <td><?= htmlspecialchars($prod['unidad_medida']) ?></td>
                                </tr>
                                <tr>
                                    <th>Precio de Costo:</th>
                                    <td class="text-muted">$<?= number_format($prod['precio_costo'], 2) ?></td>
                                </tr>
                                <tr>
                                    <th>Precio de Venta:</th>
                                    <td class="text-success fw-bold">
                                        $<?= number_format($prod['precio_venta'], 2) ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Margen:</th>
                                    <td>
                                        <?php
                                        $margen = 0;
                                        if ($prod['precio_costo'] > 0) {
                                            $margen = (($prod['precio_venta'] - $prod['precio_costo']) / $prod['precio_costo']) * 100;
                                        }
                                        ?>
                                        <span class="badge bg-info">
                                            <?= number_format($margen, 1) ?>%
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Stock Mínimo:</th>
                                    <td><?= $prod['min_stock'] ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="bi bi-x-circle"></i> Cerrar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Script para filtro -->
<script>
function filtrarCategoria() {
    const select = document.getElementById('filtroCategoria');
    const categoria = select.value;
    
    if (categoria) {
        window.location.href = `catalogo.php?action=listar&categoria=${categoria}`;
    } else {
        window.location.href = 'catalogo.php?action=listar';
    }
}
</script>

<!-- Estilos adicionales para efecto hover -->
<style>
.hover-card {
    transition: transform 0.2s, box-shadow 0.2s;
}

.hover-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}
</style>
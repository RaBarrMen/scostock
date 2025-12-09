<?php
if (!isset($desdeRouter)) {
    die("Acceso no autorizado");
}

// Verificar si es vendedor
$esVendedor = in_array('VENDEDOR', array_map('strtoupper', $_SESSION['roles'] ?? []));

if (!isset($productos) || !is_array($productos)) {
    $productos = [];
}

if (!isset($categorias) || !is_array($categorias)) {
    $categorias = [];
}

if (!isset($proveedores) || !is_array($proveedores)) {
    $proveedores = [];
}

$categoriaSeleccionada = $_GET['categoria'] ?? null;
$proveedorSeleccionado = $_GET['proveedor'] ?? null;
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
                    Consulta inventario y actualiza stock
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

    <!-- Alertas -->
    <?php if (isset($_GET['success']) && $_GET['success'] === 'stock_actualizado'): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle"></i> Stock actualizado correctamente
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        <?php if ($_GET['error'] === 'stock_negativo'): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bi bi-exclamation-triangle"></i> Error: El stock no puede ser negativo
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php elseif ($_GET['error'] === 'cantidad_invalida'): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bi bi-exclamation-triangle"></i> Error: La cantidad debe ser un número positivo
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php else: ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bi bi-exclamation-triangle"></i> Error al actualizar el stock
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <!-- Filtros -->
    <div class="card mb-4">
        <div class="card-body">
            <!-- Filtro por Categoría -->
            <div class="row align-items-center mb-3">
                <div class="col-md-3">
                    <label class="form-label fw-bold">
                        <i class="bi bi-funnel"></i> Filtrar por categoría:
                    </label>
                </div>
                <div class="col-md-9">
                    <select class="form-select" id="filtroCategoria" onchange="aplicarFiltros()">
                        <option value="">📦 Todas las categorías</option>
                        <?php foreach ($categorias as $cat): ?>
                            <option value="<?= $cat['id_categoria'] ?>" 
                                    <?= $categoriaSeleccionada == $cat['id_categoria'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <!-- Filtro por Proveedor -->
            <div class="row align-items-center mb-3">
                <div class="col-md-3">
                    <label class="form-label fw-bold">
                        <i class="bi bi-truck"></i> Filtrar por proveedor:
                    </label>
                </div>
                <div class="col-md-9">
                    <select class="form-select" id="filtroProveedor" onchange="aplicarFiltros()">
                        <option value="">🚚 Todos los proveedores</option>
                        <?php foreach ($proveedores as $prov): ?>
                            <option value="<?= $prov['id_proveedor'] ?>" 
                                    <?= $proveedorSeleccionado == $prov['id_proveedor'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($prov['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <!-- Botones de acción -->
            <div class="row">
                <div class="col-md-12 text-end">
                    <a href="catalogo.php?action=listar" class="btn btn-outline-secondary">
                        <i class="bi bi-x-circle"></i> Limpiar Filtros
                    </a>
                </div>
            </div>

            <!-- Indicador de filtros activos -->
            <?php if ($categoriaSeleccionada || $proveedorSeleccionado): ?>
            <div class="mt-3">
                <strong>Filtros activos:</strong>
                <?php if ($categoriaSeleccionada): ?>
                    <?php 
                    $catNombre = '';
                    foreach ($categorias as $cat) {
                        if ($cat['id_categoria'] == $categoriaSeleccionada) {
                            $catNombre = $cat['nombre'];
                            break;
                        }
                    }
                    ?>
                    <span class="badge bg-info me-2">
                        <i class="bi bi-tag"></i> <?= htmlspecialchars($catNombre) ?>
                        <a href="catalogo.php?action=listar<?= $proveedorSeleccionado ? '&proveedor=' . $proveedorSeleccionado : '' ?>" 
                           class="text-white text-decoration-none ms-1">×</a>
                    </span>
                <?php endif; ?>
                
                <?php if ($proveedorSeleccionado): ?>
                    <?php 
                    $provNombre = '';
                    foreach ($proveedores as $prov) {
                        if ($prov['id_proveedor'] == $proveedorSeleccionado) {
                            $provNombre = $prov['nombre'];
                            break;
                        }
                    }
                    ?>
                    <span class="badge bg-success me-2">
                        <i class="bi bi-truck"></i> <?= htmlspecialchars($provNombre) ?>
                        <a href="catalogo.php?action=listar<?= $categoriaSeleccionada ? '&categoria=' . $categoriaSeleccionada : '' ?>" 
                           class="text-white text-decoration-none ms-1">×</a>
                    </span>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <?php if (empty($productos)): ?>
        <div class="alert alert-info text-center py-5">
            <i class="bi bi-inbox" style="font-size: 3rem;"></i>
            <h4 class="mt-3">No hay productos disponibles</h4>
            <?php if ($categoriaSeleccionada || $proveedorSeleccionado): ?>
                <p>No se encontraron productos con los filtros seleccionados.</p>
                <a href="catalogo.php?action=listar" class="btn btn-primary">
                    <i class="bi bi-arrow-left"></i> Ver todos los productos
                </a>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <!-- Grid de productos (Cards) -->
        <div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-4">
            <?php foreach ($productos as $prod): ?>
            <?php
            // Obtener stock
            $stockActual = intval($prod['stock'] ?? 0);
            $minStock = intval($prod['min_stock'] ?? 0);
            
            // Determinar color del badge de stock
            if ($stockActual <= 0) {
                $stockBadge = 'bg-danger';
                $stockIcon = 'bi-x-circle';
            } elseif ($stockActual <= $minStock) {
                $stockBadge = 'bg-warning text-dark';
                $stockIcon = 'bi-exclamation-triangle';
            } else {
                $stockBadge = 'bg-success';
                $stockIcon = 'bi-check-circle';
            }
            ?>
            <div class="col">
                <div class="card h-100 shadow-sm hover-card">
                    <!-- Imagen del producto -->
                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center position-relative" 
                         style="height: 200px; overflow: hidden;">
                        <?php if (!empty($prod['imagen'])): ?>
                            <img src="../images/producto/<?= htmlspecialchars($prod['imagen']) ?>" 
                                 alt="<?= htmlspecialchars($prod['nombre']) ?>"
                                 style="max-width: 100%; max-height: 100%; object-fit: contain;">
                        <?php else: ?>
                            <i class="bi bi-box-seam text-muted" style="font-size: 4rem;"></i>
                        <?php endif; ?>
                        
                        <!-- Badge de stock en la esquina -->
                        <span class="position-absolute top-0 end-0 m-2 badge <?= $stockBadge ?>">
                            <i class="<?= $stockIcon ?>"></i> 
                            Stock: <?= $stockActual ?>
                        </span>
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

                            <!-- Botones -->
                            <div class="btn-group w-100 mb-2">
                                <button class="btn btn-outline-primary btn-sm" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#modalDetalle<?= $prod['id_producto'] ?>">
                                    <i class="bi bi-eye"></i> Detalles
                                </button>
                                <button class="btn btn-outline-success btn-sm" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#modalStock<?= $prod['id_producto'] ?>">
                                    <i class="bi bi-arrow-repeat"></i> Stock
                                </button>
                            </div>
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
                                    <th>Stock Actual:</th>
                                    <td>
                                        <span class="badge <?= $stockBadge ?>">
                                            <i class="<?= $stockIcon ?>"></i>
                                            <?= $stockActual ?> unidades
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Stock Mínimo:</th>
                                    <td><?= $minStock ?></td>
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

            <!-- Modal de actualización de stock -->
            <div class="modal fade" id="modalStock<?= $prod['id_producto'] ?>" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <form action="catalogo.php?action=actualizar_stock" method="POST">
                            <input type="hidden" name="id_producto" value="<?= $prod['id_producto'] ?>">
                            
                            <div class="modal-header bg-success text-white">
                                <h5 class="modal-title">
                                    <i class="bi bi-arrow-repeat"></i> 
                                    Actualizar Stock
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            
                            <div class="modal-body">
                                <h5><?= htmlspecialchars($prod['nombre']) ?></h5>
                                <p class="text-muted">SKU: <?= htmlspecialchars($prod['sku']) ?></p>
                                
                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle"></i>
                                    <strong>Stock actual:</strong> <?= $stockActual ?> unidades
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">
                                        <i class="bi bi-list-task"></i> Tipo de movimiento
                                    </label>
                                    <select name="tipo" class="form-select" required>
                                        <option value="ENTRADA">➕ Entrada (Agregar stock)</option>
                                        <option value="SALIDA">➖ Salida (Quitar stock)</option>
                                        <option value="AJUSTE">⚙️ Ajuste (Establecer cantidad exacta)</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">
                                        <i class="bi bi-123"></i> Cantidad
                                    </label>
                                    <input type="number" 
                                           name="cantidad" 
                                           class="form-control" 
                                           min="0" 
                                           step="1"
                                           required
                                           placeholder="Ingresa la cantidad">
                                    <small class="form-text text-muted">
                                        Para ENTRADA/SALIDA: cantidad a sumar/restar<br>
                                        Para AJUSTE: nueva cantidad total
                                    </small>
                                </div>
                            </div>
                            
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    <i class="bi bi-x-circle"></i> Cancelar
                                </button>
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-check-circle"></i> Actualizar Stock
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Script para filtros -->
<script>
function aplicarFiltros() {
    const categoria = document.getElementById('filtroCategoria').value;
    const proveedor = document.getElementById('filtroProveedor').value;
    
    let url = 'catalogo.php?action=listar';
    
    if (categoria) {
        url += '&categoria=' + categoria;
    }
    
    if (proveedor) {
        url += '&proveedor=' + proveedor;
    }
    
    window.location.href = url;
}
</script>

<!-- Estilos adicionales -->
<style>
.hover-card {
    transition: transform 0.2s, box-shadow 0.2s;
}

.hover-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}
</style>
<?php if (!isset($desdeRouter)) die("Acceso no autorizado"); ?>

<div class="container-fluid mt-4">
    <h1 class="mb-4">
        <i class="bi bi-speedometer2"></i> Dashboard - SCOSTOCK
    </h1>

    <!-- Cards de Resumen -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Total Productos</h6>
                            <h2 class="mt-2 mb-0"><?= count($productos) ?></h2>
                            <small><i class="bi bi-box-seam"></i> Registrados</small>
                        </div>
                        <div>
                            <i class="bi bi-box-seam" style="font-size: 3rem; opacity: 0.3;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Valor Inventario</h6>
                            <h2 class="mt-2 mb-0">$<?= number_format($valorTotalInventario, 0) ?></h2>
                            <small><i class="bi bi-cash-stack"></i> Total</small>
                        </div>
                        <div>
                            <i class="bi bi-currency-dollar" style="font-size: 3rem; opacity: 0.3;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Stock Bajo</h6>
                            <h2 class="mt-2 mb-0"><?= $productosStockBajo ?></h2>
                            <small><i class="bi bi-exclamation-triangle"></i> Productos</small>
                        </div>
                        <div>
                            <i class="bi bi-exclamation-triangle" style="font-size: 3rem; opacity: 0.3;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Proveedores</h6>
                            <h2 class="mt-2 mb-0"><?= count($proveedores) ?></h2>
                            <small><i class="bi bi-truck"></i> Activos</small>
                        </div>
                        <div>
                            <i class="bi bi-truck" style="font-size: 3rem; opacity: 0.3;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficas Row 1 -->
    <div class="row mb-4">
        <!-- Gráfica de Donut: Estado del Stock -->
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-pie-chart-fill"></i> Estado del Stock</h5>
                </div>
                <div class="card-body">
                    <div id="chartStockDonut" style="height: 350px;"></div>
                </div>
            </div>
        </div>

        <!-- Gráfica de Barras: Productos por Categoría -->
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-bar-chart-fill"></i> Productos por Categoría</h5>
                </div>
                <div class="card-body">
                    <div id="chartCategoriasBar" style="height: 350px;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficas Row 2 -->
    <div class="row mb-4">
        <!-- Gráfica de Columnas: Stock por Categoría -->
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="bi bi-bar-chart-line-fill"></i> Stock Total por Categoría</h5>
                </div>
                <div class="card-body">
                    <div id="chartStockCategoria" style="height: 350px;"></div>
                </div>
            </div>
        </div>

        <!-- Gráfica de Pie: Productos Activos vs Inactivos -->
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="bi bi-pie-chart"></i> Productos Activos vs Inactivos</h5>
                </div>
                <div class="card-body">
                    <div id="chartActivosInactivos" style="height: 350px;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráfica de Top 5 y Tabla -->
    <div class="row mb-4">
        <!-- Gráfica de Barras Horizontales: Top 5 Productos Más Caros -->
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="bi bi-star-fill"></i> Top 5 Productos Más Caros</h5>
                </div>
                <div class="card-body">
                    <div id="chartTop5" style="height: 350px;"></div>
                </div>
            </div>
        </div>

        <!-- Tabla de Stock Bajo -->
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0"><i class="bi bi-exclamation-triangle-fill"></i> Productos con Stock Bajo</h5>
                </div>
                <div class="card-body" style="max-height: 350px; overflow-y: auto;">
                    <?php 
                    $hayStockBajo = false;
                    foreach ($productos as $prod) {
                        $stock = intval($prod['stock'] ?? 0);
                        $minStock = floatval($prod['min_stock'] ?? 0);
                        if ($stock <= $minStock) {
                            $hayStockBajo = true;
                            break;
                        }
                    }
                    ?>

                    <?php if ($hayStockBajo): ?>
                    <div class="table-responsive">
                        <table class="table table-sm table-striped">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Stock</th>
                                    <th>Mín</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($productos as $prod): ?>
                                    <?php 
                                    $stock = intval($prod['stock'] ?? 0);
                                    $minStock = floatval($prod['min_stock'] ?? 0);
                                    if ($stock <= $minStock):
                                    ?>
                                    <tr>
                                        <td>
                                            <strong><?= htmlspecialchars($prod['nombre']) ?></strong><br>
                                            <small class="text-muted"><?= htmlspecialchars($prod['sku']) ?></small>
                                        </td>
                                        <td>
                                            <span class="badge bg-danger"><?= $stock ?></span>
                                        </td>
                                        <td><?= intval($minStock) ?></td>
                                        <td>
                                            <a href="producto.php?action=edit&id=<?= $prod['id_producto'] ?>" 
                                               class="btn btn-sm btn-warning">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                        <div class="alert alert-success text-center mb-0">
                            <i class="bi bi-check-circle" style="font-size: 3rem;"></i>
                            <h5 class="mt-3">¡Todo está bien!</h5>
                            <p class="mb-0">No hay productos con stock bajo.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- ✅ Scripts de Google Charts -->
<script type="text/javascript">
    // Cargar librería de Google Charts
    google.charts.load('current', {'packages':['corechart', 'bar']});
    google.charts.setOnLoadCallback(drawCharts);

    function drawCharts() {
        drawStockDonut();
        drawCategoriasBar();
        drawStockCategoria();
        drawActivosInactivos();
        drawTop5();
    }

    // 1. Gráfica Donut: Estado del Stock
    function drawStockDonut() {
        var data = google.visualization.arrayToDataTable([
            ['Estado', 'Cantidad'],
            ['Stock Bajo', <?= $productosStockBajo ?>],
            ['Stock Normal', <?= $productosStockNormal ?>],
            ['Stock Alto', <?= $productosStockAlto ?>]
        ]);

        var options = {
            title: 'Distribución del Estado del Stock',
            pieHole: 0.4,
            colors: ['#dc3545', '#ffc107', '#28a745'],
            legend: { position: 'bottom' },
            chartArea: { width: '90%', height: '75%' }
        };

        var chart = new google.visualization.PieChart(document.getElementById('chartStockDonut'));
        chart.draw(data, options);
    }

    // 2. Gráfica de Barras: Productos por Categoría
    function drawCategoriasBar() {
        var data = google.visualization.arrayToDataTable([
            ['Categoría', 'Productos'],
            <?php foreach ($productosPorCategoria as $nombre => $cantidad): ?>
            ['<?= addslashes($nombre) ?>', <?= $cantidad ?>],
            <?php endforeach; ?>
        ]);

        var options = {
            title: 'Cantidad de Productos por Categoría',
            colors: ['#667eea'],
            legend: { position: 'none' },
            chartArea: { width: '80%', height: '70%' },
            hAxis: { title: 'Número de Productos' },
            vAxis: { title: 'Categoría' }
        };

        var chart = new google.visualization.BarChart(document.getElementById('chartCategoriasBar'));
        chart.draw(data, options);
    }

    // 3. Gráfica de Columnas: Stock por Categoría
    function drawStockCategoria() {
        var data = google.visualization.arrayToDataTable([
            ['Categoría', 'Stock Total'],
            <?php foreach ($stockPorCategoria as $nombre => $stock): ?>
            ['<?= addslashes($nombre) ?>', <?= $stock ?>],
            <?php endforeach; ?>
        ]);

        var options = {
            title: 'Stock Total por Categoría',
            colors: ['#ff9800'],
            legend: { position: 'none' },
            chartArea: { width: '85%', height: '70%' },
            vAxis: { title: 'Unidades en Stock' },
            hAxis: { title: 'Categoría' }
        };

        var chart = new google.visualization.ColumnChart(document.getElementById('chartStockCategoria'));
        chart.draw(data, options);
    }

    // 4. Gráfica de Pie: Activos vs Inactivos
    function drawActivosInactivos() {
        var data = google.visualization.arrayToDataTable([
            ['Estado', 'Cantidad'],
            ['Activos', <?= $productosActivos ?>],
            ['Inactivos', <?= $productosInactivos ?>]
        ]);

        var options = {
            title: 'Productos Activos vs Inactivos',
            colors: ['#28a745', '#dc3545'],
            legend: { position: 'bottom' },
            chartArea: { width: '90%', height: '75%' }
        };

        var chart = new google.visualization.PieChart(document.getElementById('chartActivosInactivos'));
        chart.draw(data, options);
    }

    // 5. Gráfica Top 5 Productos Más Caros
    function drawTop5() {
        var data = google.visualization.arrayToDataTable([
            ['Producto', 'Precio'],
            <?php foreach ($top5Caros as $prod): ?>
            ['<?= addslashes(substr($prod['nombre'], 0, 20)) ?>', <?= $prod['precio_venta'] ?>],
            <?php endforeach; ?>
        ]);

        var options = {
            title: 'Top 5 Productos Más Caros',
            colors: ['#6f42c1'],
            legend: { position: 'none' },
            chartArea: { width: '70%', height: '70%' },
            hAxis: { title: 'Precio ($)' }
        };

        var chart = new google.visualization.BarChart(document.getElementById('chartTop5'));
        chart.draw(data, options);
    }

    // Redimensionar gráficas al cambiar tamaño de ventana
    window.addEventListener('resize', function() {
        drawCharts();
    });
</script>


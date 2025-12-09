<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= $titulo ?></title>
    <style>
        * { 
            margin: 0; 
            padding: 0; 
            box-sizing: border-box; 
        }
        
        body { 
            font-family: Arial, sans-serif; 
            font-size: 9pt; 
            color: #333; 
        }
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            text-align: center;
            margin-bottom: 20px;
        }
        
        .header h1 { 
            font-size: 22pt; 
            margin: 10px 0; 
        }
        
        .header .empresa { 
            font-size: 8pt; 
            opacity: 0.9; 
        }
        
        .info-reporte {
            text-align: right;
            margin-bottom: 15px;
            font-size: 8pt;
            color: #666;
        }
        
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #667eea;
            font-size: 16pt;
        }
        
        /* Sección de proveedor */
        .proveedor-section {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }
        
        .proveedor-header {
            background: #667eea;
            color: white;
            padding: 8px 12px;
            margin-bottom: 10px;
            font-size: 10pt;
        }
        
        .proveedor-header strong {
            font-size: 11pt;
        }
        
        .proveedor-header .contacto {
            font-size: 7pt;
            opacity: 0.9;
            margin-top: 3px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 7pt;
        }
        
        th {
            background: #667eea;
            color: white;
            padding: 6px 4px;
            text-align: left;
            font-size: 7pt;
        }
        
        td {
            padding: 5px 4px;
            border-bottom: 1px solid #ddd;
            font-size: 7pt;
        }
        
        tr:nth-child(even) { 
            background: #f8f9fa; 
        }
        
        .badge {
            display: inline-block;
            padding: 2px 5px;
            border-radius: 3px;
            font-size: 6pt;
            font-weight: bold;
        }
        
        .badge-success { 
            background: #28a745; 
            color: white; 
        }
        
        .badge-danger { 
            background: #dc3545; 
            color: white; 
        }
        
        .badge-info { 
            background: #17a2b8; 
            color: white; 
        }
        
        .resumen {
            margin-top: 20px;
            padding: 10px;
            background: #f8f9fa;
            border-left: 4px solid #667eea;
            font-size: 8pt;
        }
        
        .resumen-grid {
            display: table;
            width: 100%;
            margin-top: 10px;
        }
        
        .resumen-item {
            display: table-cell;
            padding: 5px 10px;
            text-align: center;
            border-right: 1px solid #ddd;
        }
        
        .resumen-item:last-child {
            border-right: none;
        }
        
        .resumen-valor {
            font-size: 14pt;
            font-weight: bold;
            color: #667eea;
        }
        
        .resumen-label {
            font-size: 7pt;
            color: #666;
            margin-top: 5px;
        }
        
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 7pt;
            color: #666;
            padding: 10px;
            border-top: 1px solid #ddd;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1><?= $empresa['nombre'] ?></h1>
        <div class="empresa">
            <?= $empresa['direccion'] ?> | 
            Tel: <?= $empresa['telefono'] ?> | 
            <?= $empresa['email'] ?>
        </div>
    </div>

    <div class="info-reporte">
        <strong>Fecha:</strong> <?= date('d/m/Y H:i') ?> | 
        <strong>Usuario:</strong> <?= $_SESSION['nombre'] ?? 'Sistema' ?>
    </div>

    <h2><?= $titulo ?></h2>

    <?php if (empty($data)): ?>
        <p style="text-align: center; color: #999; padding: 20px;">
            No hay relaciones producto-proveedor registradas
        </p>
    <?php else: ?>
        
        <?php
        // Agrupar por proveedor
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
        
        // Variables para resumen general
        $totalProveedores = count($productosPorProveedor);
        $totalRelaciones = count($data);
        $totalProductosUnicos = count(array_unique(array_column($data, 'id_producto')));
        $sumaValorInventario = 0;
        ?>

        <?php foreach ($productosPorProveedor as $proveedorNombre => $proveedorData): ?>
        <div class="proveedor-section">
            <!-- Header del Proveedor -->
            <div class="proveedor-header">
                <strong>🚚 <?= htmlspecialchars($proveedorNombre) ?></strong>
                <div class="contacto">
                    📞 <?= htmlspecialchars($proveedorData['telefono']) ?> | 
                    📧 <?= htmlspecialchars($proveedorData['email']) ?>
                </div>
            </div>

            <!-- Tabla de Productos -->
            <table>
                <thead>
                    <tr>
                        <th width="4%">#</th>
                        <th width="8%">SKU</th>
                        <th width="20%">Producto</th>
                        <th width="12%">Categoría</th>
                        <th width="8%">Unidad</th>
                        <th width="8%">P. Costo</th>
                        <th width="8%">P. Venta</th>
                        <th width="6%">Stock</th>
                        <th width="6%">S. Mín</th>
                        <th width="8%">Margen</th>
                        <th width="7%">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $contador = 1;
                    $totalProductosProveedor = count($proveedorData['productos']);
                    $sumaMargen = 0;
                    $sumaStock = 0;
                    $productosStockBajo = 0;
                    
                    foreach ($proveedorData['productos'] as $pp): 
                        // Datos del producto
                        $costo = floatval($pp['precio_costo'] ?? 0);
                        $venta = floatval($pp['precio_venta'] ?? 0);
                        $stock = intval($pp['stock'] ?? 0);
                        $minStock = floatval($pp['min_stock'] ?? 0);
                        $esActivo = intval($pp['activo'] ?? 1) == 1;
                        
                        // Calcular margen
                        $margen = 0;
                        if ($costo > 0) {
                            $margen = (($venta - $costo) / $costo) * 100;
                        }
                        $sumaMargen += $margen;
                        $sumaStock += $stock;
                        
                        // Calcular valor de inventario
                        $sumaValorInventario += ($venta * $stock);
                        
                        // Stock bajo
                        if ($stock <= $minStock) {
                            $productosStockBajo++;
                        }
                    ?>
                    <tr>
                        <td class="text-center"><?= $contador++ ?></td>
                        <td><?= htmlspecialchars($pp['producto_sku'] ?? 'N/A') ?></td>
                        <td><strong><?= htmlspecialchars($pp['producto'] ?? 'N/A') ?></strong></td>
                        <td>
                            <?php if (!empty($pp['categoria'] ?? '')): ?>
                                <span class="badge badge-info">
                                    <?= htmlspecialchars($pp['categoria']) ?>
                                </span>
                            <?php else: ?>
                                <span style="color: #999;">Sin categoría</span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($pp['unidad_medida'] ?? 'pieza') ?></td>
                        <td class="text-right">
                            <?php if ($costo > 0): ?>
                                $<?= number_format($costo, 2) ?>
                            <?php else: ?>
                                N/A
                            <?php endif; ?>
                        </td>
                        <td class="text-right">
                            <?php if ($venta > 0): ?>
                                $<?= number_format($venta, 2) ?>
                            <?php else: ?>
                                N/A
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <?= $stock ?>
                        </td>
                        <td class="text-center">
                            <?= intval($minStock) ?>
                        </td>
                        <td class="text-right">
                            <?= number_format($margen, 1) ?>%
                        </td>
                        <td class="text-center">
                            <?php if ($esActivo): ?>
                                <span class="badge badge-success">Activo</span>
                            <?php else: ?>
                                <span class="badge badge-danger">Inactivo</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Mini resumen por proveedor -->
            <div style="background: #f8f9fa; padding: 5px 10px; font-size: 7pt; color: #666; margin-bottom: 15px;">
                <strong>Productos:</strong> <?= $totalProductosProveedor ?> | 
                <strong>Stock total:</strong> <?= $sumaStock ?> unidades | 
                <strong>Stock bajo:</strong> <?= $productosStockBajo ?> productos | 
                <strong>Margen promedio:</strong> <?= $totalProductosProveedor > 0 ? number_format($sumaMargen / $totalProductosProveedor, 1) : '0' ?>%
            </div>
        </div>
        <?php endforeach; ?>

        <!-- Resumen General -->
        <div class="resumen">
            <strong>Resumen General del Sistema</strong>
            <div class="resumen-grid">
                <div class="resumen-item">
                    <div class="resumen-valor"><?= $totalProveedores ?></div>
                    <div class="resumen-label">Total Proveedores</div>
                </div>
                <div class="resumen-item">
                    <div class="resumen-valor"><?= $totalProductosUnicos ?></div>
                    <div class="resumen-label">Productos Únicos</div>
                </div>
                <div class="resumen-item">
                    <div class="resumen-valor"><?= $totalRelaciones ?></div>
                    <div class="resumen-label">Total Relaciones</div>
                </div>
                <div class="resumen-item">
                    <div class="resumen-valor">
                        <?= $totalProveedores > 0 ? number_format($totalRelaciones / $totalProveedores, 1) : '0' ?>
                    </div>
                    <div class="resumen-label">Productos/Proveedor</div>
                </div>
                <div class="resumen-item">
                    <div class="resumen-valor">$<?= number_format($sumaValorInventario, 2) ?></div>
                    <div class="resumen-label">Valor Inventario</div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="footer">
        Generado por <?= $empresa['nombre'] ?> - <?= date('d/m/Y H:i:s') ?>
    </div>
</body>
</html>
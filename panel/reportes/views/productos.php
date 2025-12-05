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
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
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
            No hay productos registrados
        </p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th width="4%">#</th>
                    <th width="8%">SKU</th>
                    <th width="22%">Producto</th>
                    <th width="13%">Categoría</th>
                    <th width="8%">Unidad</th>
                    <th width="9%">P. Costo</th>
                    <th width="9%">P. Venta</th>
                    <th width="6%">Stock Mín</th>
                    <th width="8%">Margen</th>
                    <th width="7%">Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $totalProductos = 0;
                $totalActivos = 0;
                $totalInactivos = 0;
                $sumaPrecios = 0;
                
                foreach ($data as $prod): 
                    $totalProductos++;
                    $esActivo = ($prod['activo'] ?? 0) == 1;
                    if ($esActivo) {
                        $totalActivos++;
                    } else {
                        $totalInactivos++;
                    }
                    
                    $sumaPrecios += $prod['precio_venta'] ?? 0;
                    
                    // Calcular margen
                    $costo = $prod['precio_costo'] ?? 0;
                    $venta = $prod['precio_venta'] ?? 0;
                    $margen = 0;
                    if ($costo > 0) {
                        $margen = (($venta - $costo) / $costo) * 100;
                    }
                ?>
                <tr>
                    <td><?= $prod['id_producto'] ?></td>
                    <td><?= htmlspecialchars($prod['sku']) ?></td>
                    <td><strong><?= htmlspecialchars($prod['nombre']) ?></strong></td>
                    <td><?= htmlspecialchars($prod['categoria'] ?? 'Sin categoría') ?></td>
                    <td><?= htmlspecialchars($prod['unidad_medida']) ?></td>
                    <td class="text-right">$<?= number_format($prod['precio_costo'], 2) ?></td>
                    <td class="text-right">$<?= number_format($prod['precio_venta'], 2) ?></td>
                    <td class="text-right"><?= $prod['min_stock'] ?></td>
                    <td class="text-right"><?= number_format($margen, 1) ?>%</td>
                    <td>
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

        <!-- Resumen estadístico -->
        <div class="resumen">
            <strong>Resumen del Inventario</strong>
            <div class="resumen-grid">
                <div class="resumen-item">
                    <div class="resumen-valor"><?= $totalProductos ?></div>
                    <div class="resumen-label">Total Productos</div>
                </div>
                <div class="resumen-item">
                    <div class="resumen-valor"><?= $totalActivos ?></div>
                    <div class="resumen-label">Activos</div>
                </div>
                <div class="resumen-item">
                    <div class="resumen-valor"><?= $totalInactivos ?></div>
                    <div class="resumen-label">Inactivos</div>
                </div>
                <div class="resumen-item">
                    <div class="resumen-valor">$<?= number_format($sumaPrecios, 2) ?></div>
                    <div class="resumen-label">Suma Precios Venta</div>
                </div>
                <div class="resumen-item">
                    <div class="resumen-valor">$<?= $totalProductos > 0 ? number_format($sumaPrecios / $totalProductos, 2) : '0.00' ?></div>
                    <div class="resumen-label">Precio Promedio</div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="footer">
        Generado por <?= $empresa['nombre'] ?> - <?= date('d/m/Y H:i:s') ?>
    </div>
</body>
</html>
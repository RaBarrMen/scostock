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
            font-size: 11pt; 
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
            font-size: 24pt; 
            margin: 10px 0; 
        }
        
        .header .empresa { 
            font-size: 9pt; 
            opacity: 0.9; 
        }
        
        .info-reporte {
            text-align: right;
            margin-bottom: 15px;
            font-size: 9pt;
            color: #666;
        }
        
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #667eea;
            font-size: 18pt;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        th {
            background: #667eea;
            color: white;
            padding: 10px;
            text-align: left;
            font-size: 10pt;
        }
        
        td {
            padding: 8px 10px;
            border-bottom: 1px solid #ddd;
            font-size: 9pt;
        }
        
        tr:nth-child(even) { 
            background: #f8f9fa; 
        }
        
        .contacto-item {
            display: inline-block;
            margin-right: 15px;
        }
        
        .contacto-icon {
            color: #667eea;
            font-weight: bold;
        }
        
        .resumen {
            margin-top: 20px;
            padding: 15px;
            background: #f8f9fa;
            border-left: 4px solid #667eea;
            font-size: 9pt;
        }
        
        .resumen-grid {
            display: table;
            width: 100%;
            margin-top: 10px;
        }
        
        .resumen-item {
            display: table-cell;
            padding: 10px;
            text-align: center;
            border-right: 1px solid #ddd;
        }
        
        .resumen-item:last-child {
            border-right: none;
        }
        
        .resumen-valor {
            font-size: 18pt;
            font-weight: bold;
            color: #667eea;
        }
        
        .resumen-label {
            font-size: 8pt;
            color: #666;
            margin-top: 5px;
        }
        
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 8pt;
            color: #666;
            padding: 10px;
            border-top: 1px solid #ddd;
        }
        
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 7pt;
            font-weight: bold;
            background: #17a2b8;
            color: white;
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
            No hay proveedores registrados
        </p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th width="8%">#</th>
                    <th width="30%">Proveedor</th>
                    <th width="42%">Información de Contacto</th>
                    <th width="20%">Fecha Registro</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $totalProveedores = 0;
                $conTelefono = 0;
                $conEmail = 0;
                
                foreach ($data as $prov): 
                    $totalProveedores++;
                    
                    if (!empty($prov['telefono'])) {
                        $conTelefono++;
                    }
                    
                    if (!empty($prov['email'])) {
                        $conEmail++;
                    }
                ?>
                <tr>
                    <td><?= $prov['id_proveedor'] ?></td>
                    <td>
                        <strong><?= htmlspecialchars($prov['nombre']) ?></strong>
                    </td>
                    <td>
                        <?php if (!empty($prov['telefono'])): ?>
                            <div class="contacto-item">
                                <span class="contacto-icon">📞</span>
                                <?= htmlspecialchars($prov['telefono']) ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($prov['email'])): ?>
                            <div class="contacto-item">
                                <span class="contacto-icon">✉</span>
                                <?= htmlspecialchars($prov['email']) ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (empty($prov['telefono']) && empty($prov['email'])): ?>
                            <span style="color: #999;">Sin información de contacto</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?= date('d/m/Y', strtotime($prov['created_at'])) ?>
                        <br>
                        <small style="color: #666;">
                            <?= date('H:i', strtotime($prov['created_at'])) ?>
                        </small>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Resumen estadístico -->
        <div class="resumen">
            <strong>Resumen del Directorio de Proveedores</strong>
            <div class="resumen-grid">
                <div class="resumen-item">
                    <div class="resumen-valor"><?= $totalProveedores ?></div>
                    <div class="resumen-label">Total Proveedores</div>
                </div>
                <div class="resumen-item">
                    <div class="resumen-valor"><?= $conTelefono ?></div>
                    <div class="resumen-label">Con Teléfono</div>
                </div>
                <div class="resumen-item">
                    <div class="resumen-valor"><?= $conEmail ?></div>
                    <div class="resumen-label">Con Email</div>
                </div>
                <div class="resumen-item">
                    <div class="resumen-valor">
                        <?= $totalProveedores > 0 ? round(($conTelefono / $totalProveedores) * 100) : 0 ?>%
                    </div>
                    <div class="resumen-label">Con Datos Completos</div>
                </div>
            </div>
        </div>

        <!-- Lista alfabética (opcional) -->
        <div style="margin-top: 20px; padding: 10px; background: #f8f9fa; font-size: 8pt;">
            <strong>Índice Alfabético:</strong>
            <?php
            $nombres = array_map(function($p) {
                return $p['nombre'];
            }, $data);
            sort($nombres);
            echo implode(' • ', array_map('htmlspecialchars', $nombres));
            ?>
        </div>
    <?php endif; ?>

    <div class="footer">
        Generado por <?= $empresa['nombre'] ?> - <?= date('d/m/Y H:i:s') ?>
    </div>
</body>
</html>
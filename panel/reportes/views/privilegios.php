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
        
        .resumen {
            margin-top: 20px;
            padding: 15px;
            background: #f8f9fa;
            border-left: 4px solid #667eea;
            font-size: 9pt;
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
        
        .convencion {
            margin-top: 20px;
            padding: 12px;
            background: #e7f3ff;
            border: 1px solid #b3d9ff;
            border-radius: 5px;
            font-size: 8pt;
        }
        
        .convencion h4 {
            color: #004085;
            margin-bottom: 8px;
            font-size: 10pt;
        }
        
        .convencion-grid {
            display: table;
            width: 100%;
            margin-top: 8px;
        }
        
        .convencion-col {
            display: table-cell;
            padding: 5px 10px;
            vertical-align: top;
            width: 50%;
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
            No hay privilegios registrados
        </p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th width="15%">#</th>
                    <th width="85%">Privilegio</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data as $priv): ?>
                <tr>
                    <td><?= $priv['id_privilegio'] ?></td>
                    <td><strong><?= htmlspecialchars($priv['privilegio']) ?></strong></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="resumen">
            <strong>Total de privilegios:</strong> <?= count($data) ?>
        </div>

        <!-- Convención de nombres -->
        <div class="convencion">
            <h4>📋 Convención de Nombres Recomendada</h4>
            <p style="margin-bottom: 8px;">
                Se recomienda usar el formato: <strong>Módulo Acción</strong>
            </p>
            
            <div class="convencion-grid">
                <div class="convencion-col">
                    <strong>Módulos comunes:</strong>
                    <ul style="margin: 5px 0 0 15px; padding: 0;">
                        <li>Categoria</li>
                        <li>Producto</li>
                        <li>Proveedor</li>
                        <li>Usuario</li>
                        <li>Rol</li>
                        <li>Privilegio</li>
                    </ul>
                </div>
                <div class="convencion-col">
                    <strong>Acciones típicas:</strong>
                    <ul style="margin: 5px 0 0 15px; padding: 0;">
                        <li>Listar</li>
                        <li>Nuevo</li>
                        <li>Actualizar</li>
                        <li>Eliminar</li>
                        <li>Ver</li>
                        <li>Exportar</li>
                    </ul>
                </div>
            </div>
            
            <p style="margin-top: 10px; font-style: italic;">
                <strong>Ejemplos:</strong> Categoria Listar, Producto Nuevo, Usuario Eliminar
            </p>
        </div>
    <?php endif; ?>

    <div class="footer">
        Generado por <?= $empresa['nombre'] ?> - <?= date('d/m/Y H:i:s') ?>
    </div>
</body>
</html>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= $titulo ?></title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 11pt; color: #333; }
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            text-align: center;
            margin-bottom: 20px;
        }
        
        .header h1 { font-size: 24pt; margin: 10px 0; }
        .header .empresa { font-size: 9pt; opacity: 0.9; }
        
        .info-reporte {
            text-align: right;
            margin-bottom: 15px;
            font-size: 9pt;
            color: #666;
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
        
        tr:nth-child(even) { background: #f8f9fa; }
        
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

    <h2 style="text-align: center; margin-bottom: 20px; color: #667eea;">
        <?= $titulo ?>
    </h2>

    <table>
        <thead>
            <tr>
                <th width="10%">#</th>
                <th width="30%">Nombre</th>
                <th width="50%">Descripción</th>
                <th width="10%">Fecha Registro</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($data)): ?>
                <tr>
                    <td colspan="4" style="text-align: center; color: #999;">
                        No hay categorías registradas
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($data as $cat): ?>
                <tr>
                    <td><?= $cat['id_categoria'] ?></td>
                    <td><strong><?= htmlspecialchars($cat['nombre']) ?></strong></td>
                    <td><?= htmlspecialchars($cat['descripcion'] ?? 'Sin descripción') ?></td>
                    <td><?= date('d/m/Y', strtotime($cat['created_at'])) ?></td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <div style="margin-top: 20px; font-size: 9pt; color: #666;">
        <strong>Total de categorías:</strong> <?= count($data) ?>
    </div>

    <div class="footer">
        Generado por <?= $empresa['nombre'] ?> - <?= date('d/m/Y H:i:s') ?>
    </div>
</body>
</html>
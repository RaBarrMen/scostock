<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= $titulo ?></title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 10pt; color: #333; }
        
        .header {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
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
        
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #007bff;
            font-size: 18pt;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        th {
            background: #007bff;
            color: white;
            padding: 10px 8px;
            text-align: left;
            font-size: 9pt;
        }
        
        td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
            font-size: 9pt;
        }
        
        tr:nth-child(even) { background: #f8f9fa; }
        
        .badge-priv {
            display: inline-block;
            padding: 3px 8px;
            margin: 2px;
            border-radius: 3px;
            font-size: 7pt;
            background: #28a745;
            color: white;
        }
        
        .resumen {
            margin-top: 20px;
            padding: 15px;
            background: #f8f9fa;
            border-left: 4px solid #007bff;
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
    </style>
</head>
<body>
    <div class="header">
        <h1><?= $empresa['nombre'] ?></h1>
        <div class="empresa">
            <?= $empresa['direccion'] ?> | Tel: <?= $empresa['telefono'] ?> | <?= $empresa['email'] ?>
        </div>
    </div>

    <div class="info-reporte">
        <strong>Fecha:</strong> <?= date('d/m/Y H:i') ?> | 
        <strong>Usuario:</strong> <?= $_SESSION['nombre'] ?? 'Sistema' ?>
    </div>

    <h2><?= $titulo ?></h2>

    <?php if (empty($data)): ?>
        <p style="text-align: center; color: #999; padding: 20px;">
            No hay privilegios asignados a roles
        </p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th width="35%">Rol</th>
                    <th width="65%">Privilegios Asignados</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                // Agrupar por rol
                $rolesAgrupados = [];
                foreach ($data as $item) {
                    $idRol = $item['id_rol'];
                    if (!isset($rolesAgrupados[$idRol])) {
                        $rolesAgrupados[$idRol] = [
                            'nombre' => $item['rol'],
                            'privilegios' => []
                        ];
                    }
                    $rolesAgrupados[$idRol]['privilegios'][] = $item['privilegio'];
                }
                
                foreach ($rolesAgrupados as $rol):
                ?>
                <tr>
                    <td><strong><?= htmlspecialchars($rol['nombre']) ?></strong></td>
                    <td>
                        <?php foreach ($rol['privilegios'] as $priv): ?>
                            <span class="badge-priv"><?= htmlspecialchars($priv) ?></span>
                        <?php endforeach; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="resumen">
            <strong>Resumen:</strong> 
            Total de asignaciones: <?= count($data) ?> | 
            Roles con privilegios: <?= count($rolesAgrupados) ?>
        </div>
    <?php endif; ?>

    <div class="footer">
        Generado por <?= $empresa['nombre'] ?> - <?= date('d/m/Y H:i:s') ?>
    </div>
</body>
</html>
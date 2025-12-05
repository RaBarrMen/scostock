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
            font-size: 10pt; 
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
        
        .rol-card {
            margin-bottom: 20px;
            border: 2px solid #667eea;
            border-radius: 8px;
            overflow: hidden;
            page-break-inside: avoid;
        }
        
        .rol-header {
            background: #667eea;
            color: white;
            padding: 12px 15px;
            font-size: 12pt;
            font-weight: bold;
        }
        
        .rol-body {
            padding: 15px;
            background: white;
        }
        
        .privilegio-item {
            display: inline-block;
            margin: 4px;
            padding: 5px 10px;
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            font-size: 8pt;
        }
        
        .privilegio-icon {
            color: #28a745;
            margin-right: 3px;
        }
        
        .sin-privilegios {
            color: #999;
            font-style: italic;
            padding: 10px;
            text-align: center;
            background: #fff3cd;
            border-radius: 4px;
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
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 8pt;
        }
        
        th {
            background: #667eea;
            color: white;
            padding: 8px;
            text-align: left;
        }
        
        td {
            padding: 6px 8px;
            border-bottom: 1px solid #ddd;
        }
        
        tr:nth-child(even) { 
            background: #f8f9fa; 
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
            No hay roles registrados en el sistema
        </p>
    <?php else: ?>
        
        <!-- Vista de tarjetas por rol -->
        <?php 
        $totalRoles = 0;
        $rolesConPrivilegios = 0;
        $rolesSinPrivilegios = 0;
        $totalPrivilegios = 0;
        
        foreach ($data as $rol): 
            $totalRoles++;
            $tienePrivilegios = !empty($rol['privilegios']);
            
            if ($tienePrivilegios) {
                $rolesConPrivilegios++;
                // Contar privilegios
                $privilegiosArray = array_map('trim', explode(',', $rol['privilegios']));
                $totalPrivilegios += count($privilegiosArray);
            } else {
                $rolesSinPrivilegios++;
            }
        ?>
        
        <div class="rol-card">
            <div class="rol-header">
                🎭 <?= htmlspecialchars($rol['rol']) ?>
            </div>
            <div class="rol-body">
                <?php if ($tienePrivilegios): ?>
                    <div style="margin-bottom: 8px; color: #666; font-size: 8pt;">
                        <strong>Privilegios asignados:</strong> 
                        <?= count($privilegiosArray) ?>
                    </div>
                    <?php foreach ($privilegiosArray as $privilegio): ?>
                        <span class="privilegio-item">
                            <span class="privilegio-icon">✓</span>
                            <?= htmlspecialchars($privilegio) ?>
                        </span>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="sin-privilegios">
                        ⚠️ Este rol no tiene privilegios asignados
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <?php endforeach; ?>

        <!-- Resumen estadístico -->
        <div class="resumen">
            <strong>Resumen del Sistema de Permisos</strong>
            <div class="resumen-grid">
                <div class="resumen-item">
                    <div class="resumen-valor"><?= $totalRoles ?></div>
                    <div class="resumen-label">Total Roles</div>
                </div>
                <div class="resumen-item">
                    <div class="resumen-valor"><?= $rolesConPrivilegios ?></div>
                    <div class="resumen-label">Con Privilegios</div>
                </div>
                <div class="resumen-item">
                    <div class="resumen-valor"><?= $rolesSinPrivilegios ?></div>
                    <div class="resumen-label">Sin Privilegios</div>
                </div>
                <div class="resumen-item">
                    <div class="resumen-valor"><?= $totalPrivilegios ?></div>
                    <div class="resumen-label">Total Asignaciones</div>
                </div>
                <div class="resumen-item">
                    <div class="resumen-valor">
                        <?= $totalRoles > 0 ? round($totalPrivilegios / $totalRoles, 1) : 0 ?>
                    </div>
                    <div class="resumen-label">Promedio por Rol</div>
                </div>
            </div>
        </div>

        <!-- Tabla resumen -->
        <h3 style="margin-top: 30px; margin-bottom: 10px; color: #667eea; font-size: 12pt;">
            Matriz de Permisos
        </h3>
        
        <table>
            <thead>
                <tr>
                    <th width="30%">Rol</th>
                    <th width="15%">Cant. Privilegios</th>
                    <th width="55%">Privilegios</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data as $rol): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($rol['rol']) ?></strong></td>
                    <td style="text-align: center;">
                        <?php 
                        if (!empty($rol['privilegios'])) {
                            $privilegiosArray = array_map('trim', explode(',', $rol['privilegios']));
                            echo count($privilegiosArray);
                        } else {
                            echo '0';
                        }
                        ?>
                    </td>
                    <td>
                        <?php 
                        if (!empty($rol['privilegios'])) {
                            echo htmlspecialchars($rol['privilegios']);
                        } else {
                            echo '<span style="color: #999;">Sin privilegios</span>';
                        }
                        ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Recomendaciones -->
        <div style="margin-top: 20px; padding: 12px; background: #d1ecf1; border-left: 4px solid #0c5460; font-size: 8pt;">
            <strong>💡 Recomendaciones de Seguridad:</strong>
            <ul style="margin: 8px 0 0 20px; padding: 0;">
                <li>Revisa periódicamente los privilegios asignados a cada rol</li>
                <li>Los roles sin privilegios no permitirán acceso a ninguna funcionalidad</li>
                <li>Asegúrate de que el rol ADMIN tenga todos los privilegios necesarios</li>
                <li>Documenta el propósito de cada rol para futura referencia</li>
            </ul>
        </div>

        <!-- Leyenda de roles comunes -->
        <div style="margin-top: 15px; padding: 10px; background: #fff; border: 1px solid #ddd; font-size: 8pt;">
            <strong>📋 Roles Comunes del Sistema:</strong>
            <div style="margin-top: 8px;">
                <div style="margin: 5px 0;">
                    <strong>ADMIN:</strong> Acceso total al sistema, todos los privilegios
                </div>
                <div style="margin: 5px 0;">
                    <strong>OPERADOR:</strong> Gestión de productos, inventario y proveedores
                </div>
                <div style="margin: 5px 0;">
                    <strong>PROPIETARIO:</strong> Solo lectura de reportes e información
                </div>
            </div>
        </div>

    <?php endif; ?>

    <div class="footer">
        Generado por <?= $empresa['nombre'] ?> - <?= date('d/m/Y H:i:s') ?> | Documento Confidencial
    </div>
</body>
</html>
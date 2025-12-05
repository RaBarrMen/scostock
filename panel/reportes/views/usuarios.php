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
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        th {
            background: #667eea;
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
        
        tr:nth-child(even) { 
            background: #f8f9fa; 
        }
        
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 7pt;
            font-weight: bold;
        }
        
        .badge-success { 
            background: #28a745; 
            color: white; 
        }
        
        .badge-secondary { 
            background: #6c757d; 
            color: white; 
        }
        
        .badge-role {
            display: inline-block;
            padding: 2px 6px;
            margin: 2px;
            border-radius: 3px;
            font-size: 7pt;
            background: #17a2b8;
            color: white;
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
        
        .roles-cell {
            max-width: 200px;
        }
        
        .sin-roles {
            color: #999;
            font-style: italic;
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
            No hay usuarios registrados
        </p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th width="6%">#</th>
                    <th width="28%">Nombre</th>
                    <th width="28%">Email</th>
                    <th width="20%">Roles Asignados</th>
                    <th width="10%">Estado</th>
                    <th width="8%">Registro</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $totalUsuarios = 0;
                $totalActivos = 0;
                $totalInactivos = 0;
                $conRoles = 0;
                $sinRoles = 0;
                
                foreach ($data as $u): 
                    $totalUsuarios++;
                    $esActivo = ($u['activo'] ?? 0) == 1;
                    
                    if ($esActivo) {
                        $totalActivos++;
                    } else {
                        $totalInactivos++;
                    }
                    
                    $tieneRoles = !empty($u['roles']);
                    if ($tieneRoles) {
                        $conRoles++;
                    } else {
                        $sinRoles++;
                    }
                    
                    // Separar roles si vienen en cadena
                    $rolesArray = [];
                    if ($tieneRoles) {
                        $rolesArray = array_map('trim', explode(',', $u['roles']));
                    }
                ?>
                <tr>
                    <td><?= $u['id_usuario'] ?></td>
                    <td><strong><?= htmlspecialchars($u['nombre']) ?></strong></td>
                    <td><?= htmlspecialchars($u['email']) ?></td>
                    <td class="roles-cell">
                        <?php if ($tieneRoles): ?>
                            <?php foreach ($rolesArray as $rol): ?>
                                <span class="badge-role"><?= htmlspecialchars($rol) ?></span>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <span class="sin-roles">Sin roles</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($esActivo): ?>
                            <span class="badge badge-success">Activo</span>
                        <?php else: ?>
                            <span class="badge badge-secondary">Inactivo</span>
                        <?php endif; ?>
                    </td>
                    <td style="font-size: 8pt;">
                        <?= date('d/m/Y', strtotime($u['created_at'])) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Resumen estadístico -->
        <div class="resumen">
            <strong>Resumen del Sistema de Usuarios</strong>
            <div class="resumen-grid">
                <div class="resumen-item">
                    <div class="resumen-valor"><?= $totalUsuarios ?></div>
                    <div class="resumen-label">Total Usuarios</div>
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
                    <div class="resumen-valor"><?= $conRoles ?></div>
                    <div class="resumen-label">Con Roles</div>
                </div>
                <div class="resumen-item">
                    <div class="resumen-valor"><?= $sinRoles ?></div>
                    <div class="resumen-label">Sin Roles</div>
                </div>
            </div>
        </div>

        <!-- Distribución de roles -->
        <?php
        // Contar usuarios por rol
        $rolesCuenta = [];
        foreach ($data as $u) {
            if (!empty($u['roles'])) {
                $rolesArray = array_map('trim', explode(',', $u['roles']));
                foreach ($rolesArray as $rol) {
                    if (!isset($rolesCuenta[$rol])) {
                        $rolesCuenta[$rol] = 0;
                    }
                    $rolesCuenta[$rol]++;
                }
            }
        }
        
        if (!empty($rolesCuenta)):
        ?>
        <div style="margin-top: 15px; padding: 10px; background: #fff; border: 1px solid #ddd; font-size: 9pt;">
            <strong>Distribución de Roles:</strong>
            <div style="margin-top: 10px;">
                <?php foreach ($rolesCuenta as $rol => $cuenta): ?>
                    <div style="display: inline-block; margin: 5px 15px 5px 0;">
                        <span class="badge-role"><?= htmlspecialchars($rol) ?></span>
                        <strong><?= $cuenta ?></strong> usuario<?= $cuenta != 1 ? 's' : '' ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Nota de seguridad -->
        <div style="margin-top: 15px; padding: 10px; background: #fff3cd; border-left: 4px solid #ffc107; font-size: 8pt;">
            <strong>⚠️ Nota de Seguridad:</strong> Este reporte contiene información sensible. 
            Los usuarios sin roles asignados no podrán acceder al sistema. 
            Se recomienda mantener este documento en un lugar seguro.
        </div>
    <?php endif; ?>

    <div class="footer">
        Generado por <?= $empresa['nombre'] ?> - <?= date('d/m/Y H:i:s') ?> | Documento Confidencial
    </div>
</body>
</html>
<?php
// Detectar rol del usuario
$esVendedor = in_array('VENDEDOR', array_map('strtoupper', $_SESSION['roles'] ?? []));
$esAdmin = in_array('ADMIN', array_map('strtoupper', $_SESSION['roles'] ?? []));
$esOperador = in_array('OPERADOR', array_map('strtoupper', $_SESSION['roles'] ?? []));
$esPropietario = in_array('PROPIETARIO', array_map('strtoupper', $_SESSION['roles'] ?? []));

// Determinar si tiene acceso administrativo
$tieneAccesoAdmin = $esAdmin || $esOperador || $esPropietario;
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<!-- ApexCharts para gráficas -->
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    

<!-- QRCode.js para códigos QR -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<style>
        .logo-scostock {
            width: 72px;
            height: 72px;
            border-radius: 50%;      
            object-fit: contain;     
            background-color: #f8f9fa; 
            padding: 6px;            
        }
    </style>

<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm mb-4">
  <div class="container-fluid">

    <a class="navbar-brand fw-bold" href="<?= $esVendedor ? 'catalogo.php?action=listar' : 'categoria.php?action=listar' ?>">
      <img src="../images/img/logo_scostock.png"
             alt="ScoStock logo"
             class="mb-2 logo-scostock">       
      ScoStock <?= $esVendedor ? 'Vendedor' : 'Admin' ?>
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarContent">

      <ul class="navbar-nav me-auto mb-2 mb-lg-0">

        <!-- ✅ CATÁLOGO - VISIBLE PARA TODOS -->
        <li class="nav-item">
          <a class="nav-link" href="catalogo.php?action=listar">
            <i class="bi bi-shop"></i> Catálogo
          </a>
        </li>

        <!-- ✅ OPCIONES ADMINISTRATIVAS - SOLO PARA ADMIN/OPERADOR/PROPIETARIO -->
        <?php if ($tieneAccesoAdmin): ?>

        <li class="nav-item">
          <a class="nav-link" href="categoria.php?action=listar">
            <i class="bi bi-tags"></i> Categorías
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="producto.php?action=listar">
            <i class="bi bi-box-seam"></i> Productos
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="proveedor.php?action=listar">
            <i class="bi bi-truck"></i> Proveedores
          </a>
        </li>

        <!-- Dashboard (Admin, Operador, Propietario) -->
                <?php if ($tieneAccesoAdmin): ?>
                <li class="nav-item">
                    <a class="nav-link" href="dashboard.php">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                </li>
                <?php endif; ?>

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
            <i class="bi bi-gear"></i> Usuarios & Roles
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="usuario.php?action=listar">
              <i class="bi bi-people"></i> Usuarios
            </a></li>
            <li><a class="dropdown-item" href="rol.php?action=listar">
              <i class="bi bi-person-badge"></i> Roles
            </a></li>
            <li><a class="dropdown-item" href="privilegio.php?action=listar">
              <i class="bi bi-key"></i> Privilegios
            </a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="usuario_rol.php?action=listar">
              <i class="bi bi-person-check"></i> Usuario - Rol
            </a></li>
            <li><a class="dropdown-item" href="rol_privilegio.php?action=listar">
              <i class="bi bi-shield-check"></i> Rol - Privilegio
            </a></li>
            <li><a class="dropdown-item" href="producto_proveedor.php?action=listar">
              <i class="bi bi-link-45deg"></i> Producto - Proveedor
            </a></li>
          </ul>
        </li>

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
            <i class="bi bi-file-earmark-pdf"></i> Reportes
          </a>
          <ul class="dropdown-menu">
            <li><h6 class="dropdown-header">Catálogos</h6></li>
            <li><a class="dropdown-item" href="reportes/reporte.php?tipo=categorias&formato=pdf" target="_blank">
              <i class="bi bi-tags"></i> Categorías
            </a></li>
            <li><a class="dropdown-item" href="reportes/reporte.php?tipo=productos&formato=pdf" target="_blank">
              <i class="bi bi-box-seam"></i> Productos
            </a></li>
            <li><a class="dropdown-item" href="reportes/reporte.php?tipo=proveedores&formato=pdf" target="_blank">
              <i class="bi bi-truck"></i> Proveedores
            </a></li>
            
            <li><hr class="dropdown-divider"></li>
            <li><h6 class="dropdown-header">Seguridad</h6></li>
            <li><a class="dropdown-item" href="reportes/reporte.php?tipo=usuarios&formato=pdf" target="_blank">
              <i class="bi bi-people"></i> Usuarios
            </a></li>
            <li><a class="dropdown-item" href="reportes/reporte.php?tipo=roles&formato=pdf" target="_blank">
              <i class="bi bi-person-badge"></i> Roles
            </a></li>
            <li><a class="dropdown-item" href="reportes/reporte.php?tipo=privilegios&formato=pdf" target="_blank">
              <i class="bi bi-key"></i> Privilegios
            </a></li>
            <li><a class="dropdown-item" href="reportes/reporte.php?tipo=usuario_rol&formato=pdf" target="_blank">
              <i class="bi bi-person-check"></i> Usuario-Rol
            </a></li>
            <li><a class="dropdown-item" href="reportes/reporte.php?tipo=rol_privilegio&formato=pdf" target="_blank">
              <i class="bi bi-shield-check"></i> Rol-Privilegio
            </a></li>  
            <li><a class="dropdown-item" href="reportes/reporte.php?tipo=producto_proveedor&formato=pdf" target="_blank">
              <i class="bi bi-link-45deg"></i> Producto-Proveedor
            </a></li>          
            
          </ul>
        </li>

        <?php endif; ?>

      </ul>

      <ul class="navbar-nav">
        <li class="nav-item">
          <span class="nav-link">
            <i class="bi bi-person-circle"></i> 
            <?= htmlspecialchars($_SESSION['nombre'] ?? 'Usuario') ?>
            <?php if ($esVendedor): ?>
              <span class="badge bg-info">Vendedor</span>
            <?php elseif ($esAdmin): ?>
              <span class="badge bg-danger">Admin</span>
            <?php elseif ($esOperador): ?>
              <span class="badge bg-warning text-dark">Operador</span>
            <?php elseif ($esPropietario): ?>
              <span class="badge bg-success">Propietario</span>
            <?php endif; ?>
          </span>
        </li>
        <li class="nav-item">
          <a class="nav-link text-danger fw-bold" href="login.php?action=logout">
            <i class="bi bi-box-arrow-right"></i> Cerrar sesión
          </a>
        </li>
      </ul>

    </div>
  </div>
</nav>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
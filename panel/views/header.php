<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm mb-4">
  <div class="container-fluid">

    <a class="navbar-brand fw-bold" href="dashboard.php">ScoStock Admin</a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarContent">

      <ul class="navbar-nav me-auto mb-2 mb-lg-0">

        <li class="nav-item">
          <a class="nav-link" href="categoria.php?action=index">Categorías</a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="producto.php?action=index">Productos</a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="proveedor.php?action=index">Proveedores</a>
        </li>

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
            Usuarios & Roles
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="usuario.php?action=index">Usuarios</a></li>
            <li><a class="dropdown-item" href="rol.php?action=index">Roles</a></li>
            <li><a class="dropdown-item" href="privilegio.php?action=index">Privilegios</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="usuario_rol.php?action=read">Usuario - Rol</a></li>
            <li><a class="dropdown-item" href="rol_privilegio.php?action=read">Rol - Privilegio</a></li>
          </ul>
        </li>

      </ul>

      <ul class="navbar-nav">
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

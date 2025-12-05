<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="../../../css/bootstrap.min.css">
</head>
<body>

<div class="d-flex">
    
    <!-- MENU LATERAL -->
    <div class="bg-dark text-white p-3" style="width: 250px; height: 100vh;">
        <h4>SCOSTOCK</h4>
        <hr>
        <a href="../usuario/index.php" class="text-white d-block">Usuarios</a>
        <a href="../categoria/index.php" class="text-white d-block">Categorías</a>
        <a href="../producto/index.php" class="text-white d-block">Productos</a>
        <a href="../proveedor/index.php" class="text-white d-block">Proveedores</a>
        <a href="../movimiento/index.php" class="text-white d-block">Movimientos</a>
        <hr>
        <a href="../../../controllers/loginController.php?action=logout" class="text-danger d-block">Cerrar sesión</a>
    </div>

    <!-- CONTENIDO -->
    <div class="p-4" style="flex-grow: 1;">
        <h1>Bienvenido, <?= $_SESSION['nombre'] ?></h1>
        <p>Panel de administración.</p>
    </div>

</div>

</body>
</html>

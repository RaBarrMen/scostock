<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <title>Login SCOSTOCK</title>
</head>

<body class="bg-light d-flex justify-content-center align-items-center vh-100">

<div class="card shadow p-4" style="width: 380px;">
    <h3 class="text-center mb-3">SCOSTOCK</h3>

    <?php if(isset($_GET['error'])): ?>
        <div class="alert alert-danger">Correo o contraseña incorrectos</div>
    <?php endif; ?>

    <?php if(isset($_GET['reset'])): ?>
        <div class="alert alert-success">Contraseña actualizada correctamente</div>
    <?php endif; ?>

    <?php if(isset($_GET['enviado'])): ?>
        <div class="alert alert-info">Se ha enviado un correo con instrucciones.</div>
    <?php endif; ?>

    <!-- OJO: la acción apunta al ROUTER, no a /views -->
    <form action="login.php?action=login" method="POST">

        <div class="mb-3">
            <label class="form-label">Correo</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Contraseña</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary w-100 mb-2">
            Iniciar sesión
        </button>

        <!-- Ahora también pasa por el router -->
        <a href="login.php?action=recuperar" class="btn btn-link w-100">
            ¿Olvidaste tu contraseña?
        </a>

    </form>
</div>

</body>
</html>

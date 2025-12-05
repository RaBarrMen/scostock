<?php require_once "../middleware/auth.php"; ?>

<h1>Bienvenido al panel SCOSTOCK</h1>
<p>Usuario: <?= $_SESSION['id_usuario'] ?></p>

<a href="../controllers/loginController.php?action=logout">Cerrar sesión</a>

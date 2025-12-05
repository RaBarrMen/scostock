<?php $token = $_GET['token'] ?? ''; ?>

<form action="../../loginController.php?action=reset_password" method="POST">
    <h2>Restablecer contraseña</h2>

    <input type="hidden" name="token" value="<?= $token ?>">

    <label>Nueva contraseña</label>
    <input type="password" name="password" required>

    <button type="submit">Cambiar</button>

    <?php if(isset($_GET['error'])): ?>
        <p>Token inválido o expirado</p>
    <?php endif; ?>
</form>

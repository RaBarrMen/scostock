<form action="../../loginController.php?action=enviar_token" method="POST">
    <h2>Recuperar contraseña</h2>

    <label>Email:</label>
    <input type="email" name="email" required>

    <button type="submit">Enviar enlace</button>

    <?php if(isset($_GET['enviado'])): ?>
        <p>Si el correo existe, se envió un enlace.</p>
    <?php endif; ?>
</form>

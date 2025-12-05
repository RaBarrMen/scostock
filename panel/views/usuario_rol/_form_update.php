<h1>Editar Rol</h1>

<form method="POST" action="rol.php?action=update&id=<?= $data['id_rol'] ?>">
    <div class="mb-3">
        <label class="form-label">Nombre del Rol</label>
        <input type="text" name="nombre" class="form-control" value="<?= $data['nombre'] ?>" required>
    </div>

    <button class="btn btn-warning" name="enviar">Actualizar</button>
</form>

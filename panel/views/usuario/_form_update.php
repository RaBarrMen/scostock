<?php
if (!isset($desdeRouter)) {
    die("Acceso no autorizado");
}

include_once __DIR__ . "/../alert.php";

// Verificar que existan los datos
if (!isset($data) || empty($data)) {
    echo '<div class="alert alert-danger">Error: No se encontraron los datos del usuario.</div>';
    echo '<a href="usuario.php?action=listar" class="btn btn-secondary">Volver</a>';
    exit;
}
?>

<div class="container mt-4">
    <h1>Editar Usuario</h1>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="usuario.php?action=edited">
                
                <!-- Campo oculto con el ID -->
                <input type="hidden" name="id_usuario" value="<?= htmlspecialchars($data['id_usuario']) ?>">

                <div class="mb-3">
                    <label class="form-label">Nombre Completo *</label>
                    <input type="text" 
                           name="nombre" 
                           class="form-control" 
                           value="<?= htmlspecialchars($data['nombre'] ?? '') ?>"
                           maxlength="100"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email *</label>
                    <input type="email" 
                           name="email" 
                           class="form-control" 
                           value="<?= htmlspecialchars($data['email'] ?? '') ?>"
                           maxlength="100"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nueva Contraseña (opcional)</label>
                    <input type="password" 
                           name="password" 
                           class="form-control" 
                           minlength="6">
                    <small class="text-muted">Dejar vacío para mantener la contraseña actual. Mínimo 6 caracteres si se cambia.</small>
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" 
                           name="activo" 
                           class="form-check-input" 
                           id="checkActivo"
                           <?= ($data['activo'] ?? 0) == 1 ? 'checked' : '' ?>>
                    <label class="form-check-label" for="checkActivo">
                        Usuario activo
                    </label>
                </div>

                <div class="alert alert-info">
                    <strong>Nota:</strong> Para gestionar los roles de este usuario, ve a la sección de <a href="usuario_rol.php?id=<?= $data['id_usuario'] ?>">Usuarios y Roles</a>.
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-save"></i> Actualizar
                    </button>
                    <a href="usuario.php?action=listar" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

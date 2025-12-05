<?php
if (!isset($desdeRouter)) {
    die("Acceso no autorizado");
}

include_once __DIR__ . "/../alert.php";
?>

<div class="container mt-4">
    <h1>Nuevo Usuario</h1>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="usuario.php?action=save">
                
                <div class="mb-3">
                    <label class="form-label">Nombre Completo *</label>
                    <input type="text" 
                           name="nombre" 
                           class="form-control" 
                           maxlength="100"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email *</label>
                    <input type="email" 
                           name="email" 
                           class="form-control" 
                           maxlength="100"
                           placeholder="usuario@ejemplo.com"
                           required>
                    <small class="text-muted">El email debe ser único en el sistema</small>
                </div>

                <div class="mb-3">
                    <label class="form-label">Contraseña *</label>
                    <input type="password" 
                           name="password" 
                           class="form-control" 
                           minlength="6"
                           required>
                    <small class="text-muted">Mínimo 6 caracteres</small>
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" 
                           name="activo" 
                           class="form-check-input" 
                           id="checkActivo"
                           checked>
                    <label class="form-check-label" for="checkActivo">
                        Usuario activo
                    </label>
                </div>

                <div class="alert alert-info">
                    <strong>Nota:</strong> Después de crear el usuario, no olvides asignarle roles en la sección de <a href="usuario_rol.php">Usuarios y Roles</a>.
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-save"></i> Guardar
                    </button>
                    <a href="usuario.php?action=listar" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

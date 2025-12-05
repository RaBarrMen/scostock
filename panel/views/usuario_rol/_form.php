<?php
if (!isset($desdeRouter)) {
    die("Acceso no autorizado");
}

include_once __DIR__ . "/../alert.php";

// Verificar que existan usuarios y roles
if (!isset($usuarios) || empty($usuarios)) {
    echo '<div class="alert alert-warning">No hay usuarios disponibles. <a href="../usuario.php?action=create">Crear un usuario</a></div>';
    $usuarios = [];
}

if (!isset($roles) || empty($roles)) {
    echo '<div class="alert alert-warning">No hay roles disponibles. <a href="../rol.php?action=create">Crear un rol</a></div>';
    $roles = [];
}
?>

<div class="container mt-4">
    <h1>Asignar Rol a Usuario</h1>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="usuario_rol.php?action=save" id="formAsignar">
                
                <div class="mb-3">
                    <label class="form-label">Usuario *</label>
                    <select name="id_usuario" class="form-control" id="selectUsuario" required>
                        <option value="">Seleccione un usuario...</option>
                        <?php foreach ($usuarios as $u): ?>
                            <option value="<?= $u['id_usuario'] ?>" data-email="<?= htmlspecialchars($u['email']) ?>">
                                <?= htmlspecialchars($u['nombre']) ?> (<?= htmlspecialchars($u['email']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <small class="text-muted">Selecciona el usuario al que deseas asignar un rol</small>
                </div>

                <div class="mb-3">
                    <label class="form-label">Rol *</label>
                    <select name="id_rol" class="form-control" id="selectRol" required>
                        <option value="">Seleccione un rol...</option>
                        <?php foreach ($roles as $r): ?>
                            <option value="<?= $r['id_rol'] ?>">
                                <?= htmlspecialchars($r['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <small class="text-muted">Selecciona el rol que deseas asignar</small>
                </div>

                <div class="alert alert-info">
                    <strong><i class="bi bi-lightbulb"></i> Guía de roles:</strong>
                    <ul class="mb-0">
                        <li><strong>ADMIN:</strong> Acceso total - Para administradores del sistema</li>
                        <li><strong>OPERADOR:</strong> Gestión operativa - Para empleados que manejan inventario y productos</li>
                        <li><strong>PROPIETARIO:</strong> Solo consulta - Para dueños que solo revisan reportes</li>
                    </ul>
                </div>

                <div class="alert alert-warning">
                    <strong><i class="bi bi-exclamation-triangle"></i> Importante:</strong>
                    <ul class="mb-0">
                        <li>Un usuario puede tener múltiples roles</li>
                        <li>Los privilegios de cada rol se suman</li>
                        <li>Si el usuario ya tiene este rol, se mostrará un mensaje de advertencia</li>
                        <li>Los cambios en roles afectan inmediatamente al usuario</li>
                    </ul>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-save"></i> Asignar Rol
                    </button>
                    <a href="usuario_rol.php?action=listar" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Ejemplos de configuración común -->
    <div class="card mt-4">
        <div class="card-header bg-secondary text-white">
            <h5 class="mb-0">Configuraciones Comunes</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="card border-success mb-3">
                        <div class="card-header bg-success text-white">
                            <strong>Administrador Total</strong>
                        </div>
                        <div class="card-body">
                            <p class="small mb-2"><strong>Rol:</strong> ADMIN</p>
                            <p class="small mb-0"><strong>Acceso:</strong> Todo el sistema</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-warning mb-3">
                        <div class="card-header bg-warning">
                            <strong>Empleado de Almacén</strong>
                        </div>
                        <div class="card-body">
                            <p class="small mb-2"><strong>Rol:</strong> OPERADOR</p>
                            <p class="small mb-0"><strong>Acceso:</strong> Productos, Inventario, Proveedores</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-info mb-3">
                        <div class="card-header bg-info text-white">
                            <strong>Dueño/Supervisor</strong>
                        </div>
                        <div class="card-body">
                            <p class="small mb-2"><strong>Roles:</strong> PROPIETARIO + OPERADOR</p>
                            <p class="small mb-0"><strong>Acceso:</strong> Reportes + Gestión</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

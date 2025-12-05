<?php
if (!isset($desdeRouter)) {
    die("Acceso no autorizado");
}

include_once __DIR__ . "/../alert.php";

// Verificar que existan roles y privilegios
if (!isset($roles) || empty($roles)) {
    echo '<div class="alert alert-warning">No hay roles disponibles. <a href="../rol.php?action=create">Crear un rol</a></div>';
    $roles = [];
}

if (!isset($privilegios) || empty($privilegios)) {
    echo '<div class="alert alert-warning">No hay privilegios disponibles. <a href="../privilegio.php?action=create">Crear un privilegio</a></div>';
    $privilegios = [];
}
?>

<div class="container mt-4">
    <h1>Asignar Privilegio a Rol</h1>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="rol_privilegio.php?action=save" id="formAsignar">
                
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
                    <small class="text-muted">Selecciona el rol al que deseas asignar privilegios</small>
                </div>

                <div class="mb-3">
                    <label class="form-label">Privilegio *</label>
                    <select name="id_privilegio" class="form-control" id="selectPrivilegio" required>
                        <option value="">Seleccione un privilegio...</option>
                        <?php foreach ($privilegios as $p): ?>
                            <option value="<?= $p['id_privilegio'] ?>">
                                <?= htmlspecialchars($p['privilegio']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <small class="text-muted">Selecciona el privilegio que deseas asignar</small>
                </div>

                <div class="alert alert-info">
                    <strong><i class="bi bi-lightbulb"></i> Recomendación:</strong>
                    <ul class="mb-0">
                        <li><strong>ADMIN:</strong> Asigna todos los privilegios del sistema</li>
                        <li><strong>OPERADOR:</strong> Privilegios de gestión (Listar, Nuevo, Actualizar)</li>
                        <li><strong>PROPIETARIO:</strong> Solo privilegios de lectura y reportes</li>
                    </ul>
                </div>

                <div class="alert alert-warning">
                    <strong><i class="bi bi-exclamation-triangle"></i> Nota:</strong>
                    Si el rol ya tiene este privilegio asignado, se mostrará un mensaje de advertencia.
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-save"></i> Asignar Privilegio
                    </button>
                    <a href="rol_privilegio.php?action=listar" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Asignación rápida múltiple (Opcional) -->
    <div class="card mt-4">
        <div class="card-header bg-secondary text-white">
            <h5 class="mb-0">Asignación Rápida por Módulo</h5>
        </div>
        <div class="card-body">
            <p class="text-muted">Privilegios comúnmente asignados juntos:</p>
            
            <div class="row">
                <div class="col-md-4">
                    <h6>Categorías (CRUD completo)</h6>
                    <ul class="small">
                        <li>Categoria Listar</li>
                        <li>Categoria Nuevo</li>
                        <li>Categoria Actualizar</li>
                        <li>Categoria Eliminar</li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h6>Productos (CRUD completo)</h6>
                    <ul class="small">
                        <li>Producto Listar</li>
                        <li>Producto Nuevo</li>
                        <li>Producto Actualizar</li>
                        <li>Producto Eliminar</li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h6>Solo Lectura</h6>
                    <ul class="small">
                        <li>Categoria Listar</li>
                        <li>Producto Listar</li>
                        <li>Reporte Ver</li>
                        <li>Inventario Ver</li>
                    </ul>
                </div>
            </div>

            <p class="text-muted small mb-0">
                <i class="bi bi-info-circle"></i> Asigna los privilegios uno por uno según las necesidades de cada rol.
            </p>
        </div>
    </div>
</div>

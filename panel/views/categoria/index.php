<?php
if (!isset($desdeRouter)) {
    die("Acceso no autorizado");
}

// Corregir la ruta del alert.php
include_once __DIR__ . "/../alert.php";  // Cambiado aquí

// Verificar si es ADMIN
$esAdmin = in_array('ADMIN', array_map('strtoupper', $_SESSION['roles'] ?? []));

// Asegurar que $data existe
if (!isset($data) || !is_array($data)) {
    $data = [];
}
?>

<div class="container mt-4">
    <h1>Categorías</h1>

    <div class="btn-group mb-3" role="group" aria-label="Basic mixed styles example">
        <?php if ($esAdmin): ?>
            <a href="categoria.php?action=create" class="btn btn-success">Nueva</a>
        <?php endif; ?>
        <a href="reportes/reporte.php?tipo=categorias&formato=pdf" 
           class="btn btn-primary" 
           target="_blank">
            <i class="bi bi-file-pdf"></i> Imprimir
        </a>
    </div>

    <?php if (empty($data)): ?>
        <div class="alert alert-info">
            No hay categorías registradas.
            <?php if ($esAdmin): ?>
                <a href="categoria.php?action=create">Crear la primera categoría</a>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Imagen</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <?php if ($esAdmin): ?>
                        <th>Opciones</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($data as $cat): ?>
            <tr>
                <th scope="row"><?= htmlspecialchars($cat['id_categoria'] ?? '') ?></th>

                <td>
                    <?php if (!empty($cat['imagen'])): ?>
                        <img src="../images/categoria/<?= htmlspecialchars($cat['imagen']) ?>" 
                             width="75" height="75" alt="Imagen categoría">
                    <?php else: ?>
                        <span class="text-muted">Sin imagen</span>
                    <?php endif; ?>
                </td>

                <td><?= htmlspecialchars($cat['nombre'] ?? '') ?></td>
                <td><?= htmlspecialchars($cat['descripcion'] ?? '') ?></td>

                <?php if ($esAdmin): ?>
                <td>
                    <div class="btn-group" role="group">
                        <a href="categoria.php?action=update&id=<?= $cat['id_categoria'] ?>" 
                           class="btn btn-warning btn-sm">Editar</a>
                        
                        <!-- Botón que abre el modal -->
                        <button type="button" 
                                class="btn btn-danger btn-sm" 
                                data-bs-toggle="modal" 
                                data-bs-target="#modalEliminar<?= $cat['id_categoria'] ?>">
                            Eliminar
                        </button>
                    </div>
                </td>
                <?php endif; ?>
            </tr>

            <!-- Modal de confirmación para cada categoría -->
            <?php if ($esAdmin): ?>
            <div class="modal fade" id="modalEliminar<?= $cat['id_categoria'] ?>" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title">Confirmar Eliminación</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p class="mb-0">¿Está seguro que desea eliminar la categoría:</p>
                            <p class="fw-bold fs-5 text-center"><?= htmlspecialchars($cat['nombre']) ?>?</p>
                            <p class="text-muted small mb-0">Esta acción no se puede deshacer.</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <a href="categoria.php?action=delete&id=<?= $cat['id_categoria'] ?>" 
                               class="btn btn-danger">
                                Sí, Eliminar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
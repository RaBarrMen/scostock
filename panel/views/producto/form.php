<?php
if (!isset($desdeRouter)) {
    die("Acceso no autorizado");
}

include_once __DIR__ . "/../alert.php";

// Detectar si es edición o creación
$esEdicion = isset($data) && !empty($data) && isset($data['id_producto']);
$titulo = $esEdicion ? "Editar Producto" : "Nuevo Producto";
$actionForm = $esEdicion ? "edited" : "save";

// Validar que existan las categorías
if (!isset($categorias) || empty($categorias)) {
    echo '<div class="alert alert-warning">No hay categorías disponibles. <a href="../categoria.php?action=create">Crear una categoría</a></div>';
    $categorias = [];
}
?>

<div class="container mt-4">
    <h1><?= $titulo ?></h1>

    <div class="card">
        <div class="card-body">
            <form method="POST" 
                  action="producto.php?action=<?= $actionForm ?>" 
                  enctype="multipart/form-data">
                
                <?php if ($esEdicion): ?>
                    <input type="hidden" name="id_producto" value="<?= htmlspecialchars($data['id_producto']) ?>">
                <?php endif; ?>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Nombre *</label>
                            <input type="text" 
                                   name="nombre" 
                                   class="form-control" 
                                   value="<?= $esEdicion ? htmlspecialchars($data['nombre'] ?? '') : '' ?>"
                                   required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">SKU *</label>
                            <input type="text" 
                                   name="sku" 
                                   class="form-control" 
                                   value="<?= $esEdicion ? htmlspecialchars($data['sku'] ?? '') : '' ?>"
                                   required>
                            <small class="text-muted">Código único del producto</small>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Categoría *</label>
                            <select name="id_categoria" class="form-control" required>
                                <option value="">Seleccione una categoría</option>
                                <?php foreach ($categorias as $c): ?>
                                    <option value="<?= $c['id_categoria'] ?>"
                                            <?= ($esEdicion && $c['id_categoria'] == ($data['id_categoria'] ?? 0)) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($c['nombre']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Unidad de Medida *</label>
                            <input type="text" 
                                   name="unidad_medida" 
                                   class="form-control" 
                                   value="<?= $esEdicion ? htmlspecialchars($data['unidad_medida'] ?? '') : '' ?>"
                                   placeholder="Ej: Pieza, Kg, Litro"
                                   required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Precio Costo *</label>
                            <input type="number" 
                                   step="0.01" 
                                   name="precio_costo" 
                                   class="form-control" 
                                   value="<?= $esEdicion ? htmlspecialchars($data['precio_costo'] ?? '') : '' ?>"
                                   required>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Precio Venta *</label>
                            <input type="number" 
                                   step="0.01" 
                                   name="precio_venta" 
                                   class="form-control" 
                                   value="<?= $esEdicion ? htmlspecialchars($data['precio_venta'] ?? '') : '' ?>"
                                   required>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Stock Mínimo *</label>
                            <input type="number" 
                                   name="min_stock" 
                                   class="form-control" 
                                   value="<?= $esEdicion ? htmlspecialchars($data['min_stock'] ?? '') : '' ?>"
                                   required>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Imagen</label>
                    
                    <?php if ($esEdicion && !empty($data['imagen'])): ?>
                        <div class="mb-3">
                            <label class="form-label text-muted">Imagen actual:</label><br>
                            <img src="../images/producto/<?= htmlspecialchars($data['imagen']) ?>" 
                                 width="150" 
                                 height="150"
                                 class="img-thumbnail"
                                 style="object-fit:cover;"
                                 alt="Imagen actual">
                        </div>
                    <?php endif; ?>
                    
                    <input type="file" 
                           name="imagen" 
                           class="form-control"
                           accept="image/*">
                    <small class="form-text text-muted">
                        <?php if ($esEdicion): ?>
                            Dejar vacío para mantener la imagen actual
                        <?php else: ?>
                            Formatos permitidos: JPG, PNG, GIF (máx. 1MB)
                        <?php endif; ?>
                    </small>
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" 
                           name="activo" 
                           class="form-check-input" 
                           id="checkActivo"
                           <?= (!$esEdicion || ($data['activo'] ?? 0) == 1) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="checkActivo">
                        Producto activo
                    </label>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn <?= $esEdicion ? 'btn-warning' : 'btn-success' ?>">
                        <i class="bi bi-save"></i> <?= $esEdicion ? 'Actualizar' : 'Guardar' ?>
                    </button>
                    <a href="producto.php?action=listar" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
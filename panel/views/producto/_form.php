<?php
if (!isset($desdeRouter)) {
    die("Acceso no autorizado");
}

include_once __DIR__ . "/../alert.php";

// Validar que existan las categorías
if (!isset($categorias) || empty($categorias)) {
    echo '<div class="alert alert-warning">No hay categorías disponibles. <a href="../categoria.php?action=create">Crear una categoría</a></div>';
    $categorias = [];
}
?>

<div class="container mt-4">
    <h1>Nuevo Producto</h1>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="producto.php?action=save" enctype="multipart/form-data">
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Nombre *</label>
                            <input type="text" 
                                   name="nombre" 
                                   class="form-control" 
                                   required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">SKU *</label>
                            <input type="text" 
                                   name="sku" 
                                   class="form-control" 
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
                                    <option value="<?= $c['id_categoria'] ?>">
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
                                   placeholder="Ej: Pieza, Kg, Litro"
                                   required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Precio Costo *</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" 
                                       step="0.01" 
                                       name="precio_costo" 
                                       class="form-control" 
                                       required>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Precio Venta *</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" 
                                       step="0.01" 
                                       name="precio_venta" 
                                       class="form-control" 
                                       required>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Stock Mínimo *</label>
                            <input type="number" 
                                   name="min_stock" 
                                   class="form-control" 
                                   min="0"
                                   required>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Imagen</label>
                    <input type="file" 
                           name="imagen" 
                           class="form-control"
                           accept="image/*">
                    <small class="form-text text-muted">
                        Formatos permitidos: JPG, PNG, GIF (máx. 1MB)
                    </small>
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" 
                           name="activo" 
                           class="form-check-input" 
                           id="checkActivo"
                           checked>
                    <label class="form-check-label" for="checkActivo">
                        Producto activo
                    </label>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-save"></i> Guardar
                    </button>
                    <a href="producto.php?action=listar" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
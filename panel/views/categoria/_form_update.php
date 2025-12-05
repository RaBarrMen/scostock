<?php
if (!isset($desdeRouter)) {
    die("Acceso no autorizado");
}

// Incluir alertas
include_once __DIR__ . "/../alert.php";

// Verificar que existan los datos
if (!isset($data) || empty($data)) {
    echo '<div class="alert alert-danger">Error: No se encontraron los datos de la categoría.</div>';
    echo '<a href="categoria.php?action=listar" class="btn btn-secondary">Volver</a>';
    exit;
}
?>

<div class="container mt-4">
    <h1>Editar Categoría</h1>

    <div class="card">
        <div class="card-body">
            <form method="POST" 
                  action="categoria.php?action=edited" 
                  enctype="multipart/form-data">
                
                <!-- Campo oculto con el ID -->
                <input type="hidden" name="id_categoria" value="<?= htmlspecialchars($data['id_categoria']) ?>">

                <div class="mb-3">
                    <label class="form-label">Nombre *</label>
                    <input type="text" 
                           name="nombre" 
                           class="form-control" 
                           value="<?= htmlspecialchars($data['nombre'] ?? '') ?>"
                           maxlength="80"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Descripción</label>
                    <textarea name="descripcion" 
                              class="form-control" 
                              rows="3"
                              maxlength="255"><?= htmlspecialchars($data['descripcion'] ?? '') ?></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Imagen</label>
                    
                    <?php if (!empty($data['imagen'])): ?>
                        <div class="mb-3">
                            <label class="form-label text-muted">Imagen actual:</label><br>
                            <img src="../images/categoria/<?= htmlspecialchars($data['imagen']) ?>" 
                                 width="150" 
                                 height="150"
                                 class="img-thumbnail"
                                 alt="Imagen actual">
                        </div>
                    <?php endif; ?>
                    
                    <input type="file" 
                           name="imagen" 
                           class="form-control"
                           accept="image/*">
                    <small class="form-text text-muted">
                        Dejar vacío para mantener la imagen actual
                    </small>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-save"></i> Actualizar
                    </button>
                    <a href="categoria.php?action=listar" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
<?php
if (!isset($desdeRouter)) {
    die("Acceso no autorizado");
}

// Incluir alertas
include_once __DIR__ . "/../alert.php";
?>

<div class="container mt-4">
    <h1>Nueva Categoría</h1>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="categoria.php?action=save" enctype="multipart/form-data">
                <!-- resto del formulario -->
                <div class="mb-3">
                    <label class="form-label">Nombre *</label>
                    <input type="text" 
                           name="nombre" 
                           class="form-control" 
                           maxlength="80"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Descripción</label>
                    <textarea name="descripcion" 
                              class="form-control" 
                              rows="3"
                              maxlength="255"></textarea>
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

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success">Guardar</button>
                    <a href="categoria.php?action=listar" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
<?php
// Mensajes de éxito
if (isset($_GET['success'])) {
    $mensaje = '';
    switch ($_GET['success']) {
        case 1: 
            $mensaje = 'Registro creado exitosamente'; 
            break;
        case 2: 
            $mensaje = 'Registro actualizado exitosamente'; 
            break;
        case 3: 
            $mensaje = 'Registro eliminado exitosamente'; 
            break;
    }
    
    if ($mensaje) {
        ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>✓ Éxito:</strong> <?= $mensaje ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php
    }
}

// Mensajes de error
if (isset($_GET['error'])) {
    $mensaje = '';
    switch ($_GET['error']) {
        case 1: 
            $mensaje = 'Error al guardar el registro'; 
            break;
        case 2: 
            $mensaje = 'Datos inválidos. Verifica el formulario'; 
            break;
        case 3: 
            $mensaje = 'Registro no encontrado'; 
            break;
        case 4: 
            $mensaje = 'Error al eliminar el registro'; 
            break;
        case 5: 
            $mensaje = 'No se puede eliminar. El registro está siendo usado por otros elementos'; 
            break;
        case 6: 
            $mensaje = 'El rol ya tiene ese privilegio asignado'; 
            break;
    }
    
    if ($mensaje) {
        ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>✗ Error:</strong> <?= $mensaje ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php
    }
}
?>
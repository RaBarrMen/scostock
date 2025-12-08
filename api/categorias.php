<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

require_once "../models/categoria.php";

$categoria = new Categoria();
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    
    /* ==========================================
       GET - Obtener todos o uno
    ========================================== */
    case 'GET':
        if (isset($_GET['id'])) {
            $data = $categoria->readOne($_GET['id']);
            
            if ($data) {
                // Agregar URL completa de la imagen
                if (!empty($data['imagen'])) {
                    $data['imagen_url'] = "http://" . $_SERVER['HTTP_HOST'] . "/scostock/images/categoria/" . $data['imagen'];
                }
                
                echo json_encode([
                    "success" => true,
                    "data" => $data
                ]);
            } else {
                http_response_code(404);
                echo json_encode([
                    "success" => false,
                    "message" => "Categoría no encontrada"
                ]);
            }
        } else {
            $data = $categoria->read();
            
            // Agregar URLs de imágenes
            foreach ($data as &$item) {
                if (!empty($item['imagen'])) {
                    $item['imagen_url'] = "http://" . $_SERVER['HTTP_HOST'] . "/scostock/images/categoria/" . $item['imagen'];
                }
            }
            
            echo json_encode([
                "success" => true,
                "data" => $data,
                "total" => count($data)
            ]);
        }
        break;

    /* ==========================================
       POST - Crear con imagen (multipart/form-data)
    ========================================== */
    case 'POST':
        // Usar $_POST para multipart/form-data (con imagen)
        if (!empty($_POST['nombre'])) {
            $data = [
                'nombre' => $_POST['nombre'],
                'descripcion' => $_POST['descripcion'] ?? ''
            ];
            
            // La imagen se maneja automáticamente con $_FILES en tu método create()
            $resultado = $categoria->create($data);
            
            if ($resultado > 0) {
                http_response_code(201);
                echo json_encode([
                    "success" => true,
                    "message" => "Categoría creada exitosamente"
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    "success" => false,
                    "message" => "Error al crear categoría"
                ]);
            }
        } else {
            http_response_code(400);
            echo json_encode([
                "success" => false,
                "message" => "El nombre es requerido"
            ]);
        }
        break;

    /* ==========================================
       PUT - Actualizar
    ========================================== */
    case 'PUT':
        parse_str(file_get_contents("php://input"), $_PUT);
        
        if (!empty($_PUT['id']) && !empty($_PUT['nombre'])) {
            $data = [
                'nombre' => $_PUT['nombre'],
                'descripcion' => $_PUT['descripcion'] ?? ''
            ];
            
            $resultado = $categoria->update($data, $_PUT['id']);
            
            if ($resultado > 0) {
                echo json_encode([
                    "success" => true,
                    "message" => "Categoría actualizada exitosamente"
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    "success" => false,
                    "message" => "Error al actualizar categoría"
                ]);
            }
        } else {
            http_response_code(400);
            echo json_encode([
                "success" => false,
                "message" => "Datos incompletos"
            ]);
        }
        break;

    /* ==========================================
       DELETE - Eliminar
    ========================================== */
    case 'DELETE':
        if (isset($_GET['id'])) {
            $resultado = $categoria->delete($_GET['id']);
            
            if ($resultado === 0) {
                http_response_code(409);
                echo json_encode([
                    "success" => false,
                    "message" => "No se puede eliminar: categoría en uso"
                ]);
            } elseif ($resultado > 0) {
                echo json_encode([
                    "success" => true,
                    "message" => "Categoría eliminada exitosamente"
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    "success" => false,
                    "message" => "Error al eliminar categoría"
                ]);
            }
        } else {
            http_response_code(400);
            echo json_encode([
                "success" => false,
                "message" => "ID requerido"
            ]);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode([
            "success" => false,
            "message" => "Método no permitido"
        ]);
        break;
}
?>
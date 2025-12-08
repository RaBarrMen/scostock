<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

require_once "../models/proveedor.php";

$proveedor = new Proveedor();
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    
    /* ==========================================
       GET - Obtener todos o uno específico
    ========================================== */
    case 'GET':
        if (isset($_GET['id'])) {
            // Obtener uno
            $data = $proveedor->readOne($_GET['id']);
            
            if ($data) {
                echo json_encode([
                    "success" => true,
                    "data" => $data
                ]);
            } else {
                http_response_code(404);
                echo json_encode([
                    "success" => false,
                    "message" => "Proveedor no encontrado"
                ]);
            }
        } else {
            // Obtener todos
            $data = $proveedor->read();
            
            echo json_encode([
                "success" => true,
                "data" => $data,
                "total" => count($data)
            ]);
        }
        break;

    /* ==========================================
       POST - Crear nuevo
    ========================================== */
    case 'POST':
        $input = json_decode(file_get_contents("php://input"), true);
        
        if (!empty($input['nombre']) && !empty($input['telefono']) && !empty($input['email'])) {
            $resultado = $proveedor->create($input);
            
            if ($resultado > 0) {
                http_response_code(201);
                echo json_encode([
                    "success" => true,
                    "message" => "Proveedor creado exitosamente"
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    "success" => false,
                    "message" => "Error al crear proveedor"
                ]);
            }
        } else {
            http_response_code(400);
            echo json_encode([
                "success" => false,
                "message" => "Datos incompletos: nombre, telefono, email son requeridos"
            ]);
        }
        break;

    /* ==========================================
       PUT - Actualizar
    ========================================== */
    case 'PUT':
        $input = json_decode(file_get_contents("php://input"), true);
        
        if (!empty($input['id']) && !empty($input['nombre']) && !empty($input['telefono']) && !empty($input['email'])) {
            $resultado = $proveedor->update($input, $input['id']);
            
            if ($resultado > 0) {
                echo json_encode([
                    "success" => true,
                    "message" => "Proveedor actualizado exitosamente"
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    "success" => false,
                    "message" => "Error al actualizar proveedor"
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
            $resultado = $proveedor->delete($_GET['id']);
            
            if ($resultado === -1) {
                http_response_code(409);
                echo json_encode([
                    "success" => false,
                    "message" => "No se puede eliminar: proveedor en uso"
                ]);
            } elseif ($resultado > 0) {
                echo json_encode([
                    "success" => true,
                    "message" => "Proveedor eliminado exitosamente"
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    "success" => false,
                    "message" => "Error al eliminar proveedor"
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
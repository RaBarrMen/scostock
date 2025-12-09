<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

require_once "../models/producto.php";

$producto = new Producto();
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    
    /* ==========================================
       GET - Obtener todos o uno
    ========================================== */
    case 'GET':
        if (isset($_GET['id'])) {
            $data = $producto->readOne($_GET['id']);
            
            if ($data) {
                // Agregar URL completa de la imagen
                if (!empty($data['imagen'])) {
                    $data['imagen_url'] = "http://" . $_SERVER['HTTP_HOST'] . "/scostock/images/producto/" . $data['imagen'];
                } else {
                    $data['imagen_url'] = null;
                }
                
                echo json_encode([
                    "success" => true,
                    "data" => $data
                ]);
            } else {
                http_response_code(404);
                echo json_encode([
                    "success" => false,
                    "message" => "Producto no encontrado"
                ]);
            }
        } else {
            $data = $producto->read();
            
            // Agregar URLs de imágenes
            foreach ($data as &$item) {
                if (!empty($item['imagen'])) {
                    $item['imagen_url'] = "http://" . $_SERVER['HTTP_HOST'] . "/scostock/images/producto/" . $item['imagen'];
                } else {
                    $item['imagen_url'] = null;
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
       POST - Crear producto 
    ========================================== */
    case 'POST':
        // Verificar si viene JSON o FormData
        if (isset($_POST['nombre']) && isset($_POST['sku'])) {
            // FormData (con posible imagen)
            $data = [
                'nombre' => $_POST['nombre'],
                'sku' => $_POST['sku'],
                'id_categoria' => $_POST['id_categoria'] ?? null,
                'unidad_medida' => $_POST['unidad_medida'] ?? 'pieza',
                'precio_costo' => $_POST['precio_costo'] ?? 0,
                'precio_venta' => $_POST['precio_venta'] ?? 0,
                'min_stock' => $_POST['min_stock'] ?? 0,
                'activo' => isset($_POST['activo']) ? 1 : 0
            ];
            
            // La imagen se maneja automáticamente con $_FILES
            $resultado = $producto->create($data);
            
            if ($resultado === -2) {
                http_response_code(409);
                echo json_encode([
                    "success" => false,
                    "message" => "SKU duplicado"
                ]);
            } elseif ($resultado > 0) {
                // Obtener el último producto creado para devolver info completa
                $ultimoId = $producto->_DB->lastInsertId();
                $productoCreado = $producto->readOne($ultimoId);
                
                if (!empty($productoCreado['imagen'])) {
                    $productoCreado['imagen_url'] = "http://" . $_SERVER['HTTP_HOST'] . "/scostock/images/producto/" . $productoCreado['imagen'];
                }
                
                http_response_code(201);
                echo json_encode([
                    "success" => true,
                    "message" => "Producto creado exitosamente",
                    "data" => $productoCreado
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    "success" => false,
                    "message" => "Error al crear producto"
                ]);
            }
        } else {
            // JSON puro (sin imagen)
            $input = json_decode(file_get_contents("php://input"), true);
            
            if (!empty($input['nombre']) && !empty($input['sku'])) {
                $data = [
                    'nombre' => $input['nombre'],
                    'sku' => $input['sku'],
                    'id_categoria' => $input['id_categoria'] ?? null,
                    'unidad_medida' => $input['unidad_medida'] ?? 'pieza',
                    'precio_costo' => $input['precio_costo'] ?? 0,
                    'precio_venta' => $input['precio_venta'] ?? 0,
                    'min_stock' => $input['min_stock'] ?? 0,
                    'activo' => isset($input['activo']) ? 1 : 0
                ];
                
                $resultado = $producto->create($data);
                
                if ($resultado === -2) {
                    http_response_code(409);
                    echo json_encode([
                        "success" => false,
                        "message" => "SKU duplicado"
                    ]);
                } elseif ($resultado > 0) {
                    http_response_code(201);
                    echo json_encode([
                        "success" => true,
                        "message" => "Producto creado exitosamente (sin imagen)"
                    ]);
                } else {
                    http_response_code(500);
                    echo json_encode([
                        "success" => false,
                        "message" => "Error al crear producto"
                    ]);
                }
            } else {
                http_response_code(400);
                echo json_encode([
                    "success" => false,
                    "message" => "Nombre y SKU son requeridos"
                ]);
            }
        }
        break;

    /* ==========================================
       PUT - Actualizar producto
       Nota: Para actualizar imagen usa POST con _method=PUT
    ========================================== */
    case 'PUT':
        // PUT con JSON (sin imagen)
        $input = json_decode(file_get_contents("php://input"), true);
        
        if (!empty($input['id']) && !empty($input['nombre']) && !empty($input['sku'])) {
            $data = [
                'nombre' => $input['nombre'],
                'sku' => $input['sku'],
                'id_categoria' => $input['id_categoria'] ?? null,
                'unidad_medida' => $input['unidad_medida'] ?? 'pieza',
                'precio_costo' => $input['precio_costo'] ?? 0,
                'precio_venta' => $input['precio_venta'] ?? 0,
                'min_stock' => $input['min_stock'] ?? 0,
                'activo' => isset($input['activo']) ? 1 : 0
            ];
            
            $resultado = $producto->update($data, $input['id']);
            
            if ($resultado > 0) {
                $productoActualizado = $producto->readOne($input['id']);
                
                if (!empty($productoActualizado['imagen'])) {
                    $productoActualizado['imagen_url'] = "http://" . $_SERVER['HTTP_HOST'] . "/scostock/images/producto/" . $productoActualizado['imagen'];
                }
                
                echo json_encode([
                    "success" => true,
                    "message" => "Producto actualizado exitosamente",
                    "data" => $productoActualizado
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    "success" => false,
                    "message" => "Error al actualizar producto"
                ]);
            }
        } else {
            http_response_code(400);
            echo json_encode([
                "success" => false,
                "message" => "Datos incompletos: id, nombre y sku son requeridos"
            ]);
        }
        break;

    /* ==========================================
       DELETE - Eliminar producto
    ========================================== */
    case 'DELETE':
        if (isset($_GET['id'])) {
            $resultado = $producto->delete($_GET['id']);
            
            if ($resultado === -1) {
                http_response_code(409);
                echo json_encode([
                    "success" => false,
                    "message" => "No se puede eliminar: producto tiene movimientos asociados"
                ]);
            } elseif ($resultado > 0) {
                echo json_encode([
                    "success" => true,
                    "message" => "Producto eliminado exitosamente"
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    "success" => false,
                    "message" => "Error al eliminar producto"
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
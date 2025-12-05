<?php
session_start();
require_once "../models/sistema.php";
require_once "../models/producto.php";
require_once "../models/categoria.php";

// Verificar que el usuario esté logueado
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php?action=form");
    exit;
}

$sistema = new Sistema();
$producto = new Producto();
$categoriaModel = new Categoria();

$action = $_GET['action'] ?? 'listar';

switch ($action) {

    /* ============================
       CASOS QUE HACEN REDIRECT
    ============================ */
    case 'save':
        $sistema->checarRol(['ADMIN', 'OPERADOR']);
        
        $data = [
            'nombre'        => $_POST['nombre'] ?? '',
            'sku'           => $_POST['sku'] ?? '',
            'id_categoria'  => $_POST['id_categoria'] ?? 0,
            'unidad_medida' => $_POST['unidad_medida'] ?? '',
            'precio_costo'  => $_POST['precio_costo'] ?? 0,
            'precio_venta'  => $_POST['precio_venta'] ?? 0,
            'min_stock'     => $_POST['min_stock'] ?? 0,
            'activo'        => isset($_POST['activo']) ? 1 : 0
        ];

        $resultado = $producto->create($data);
        
        if ($resultado === -2) {
            header("Location: producto.php?action=create&error=6");
        } elseif ($resultado > 0) {
            header("Location: producto.php?action=listar&success=1");
        } else {
            header("Location: producto.php?action=create&error=1");
        }
        exit;

    case 'edited':
        $sistema->checarRol(['ADMIN', 'OPERADOR']);
        
        $id = $_POST['id_producto'] ?? 0;
        $data = [
            'nombre'        => $_POST['nombre'] ?? '',
            'sku'           => $_POST['sku'] ?? '',
            'id_categoria'  => $_POST['id_categoria'] ?? 0,
            'unidad_medida' => $_POST['unidad_medida'] ?? '',
            'precio_costo'  => $_POST['precio_costo'] ?? 0,
            'precio_venta'  => $_POST['precio_venta'] ?? 0,
            'min_stock'     => $_POST['min_stock'] ?? 0,
            'activo'        => isset($_POST['activo']) ? 1 : 0
        ];

        $resultado = $producto->update($data, $id);
        
        if ($resultado > 0) {
            header("Location: producto.php?action=listar&success=2");
        } else {
            header("Location: producto.php?action=update&id=$id&error=1");
        }
        exit;

    case 'delete':
        $sistema->checarRol('ADMIN');
        
        $id = $_GET['id'] ?? 0;
        $resultado = $producto->delete($id);
        
        if ($resultado > 0) {
            header("Location: producto.php?action=listar&success=3");
        } else if ($resultado === -1) {
            header("Location: producto.php?action=listar&error=7");
        } else {
            header("Location: producto.php?action=listar&error=4");
        }
        exit;

    /* ============================
       CASOS QUE MUESTRAN VISTAS
    ============================ */
    case 'listar':
    default:
        $sistema->checarRol(['ADMIN', 'OPERADOR', 'PROPIETARIO']);
        
        include_once __DIR__ . "/views/header.php";
        
        $data = $producto->read();
        
        if ($data === false || $data === null) {
            $data = [];
        }
        
        $desdeRouter = true;
        include __DIR__ . "/views/producto/index.php";
        break;

    case 'create':
        $sistema->checarRol(['ADMIN', 'OPERADOR']);
        
        include_once __DIR__ . "/views/header.php";
        
        $categorias = $categoriaModel->read();
        $desdeRouter = true;
        include __DIR__ . "/views/producto/_form.php";  // Archivo separado
        break;

    case 'update':
        $sistema->checarRol(['ADMIN', 'OPERADOR']);
        
        $id = $_GET['id'] ?? 0;
        $data = $producto->readOne($id);
        
        if (!$data) {
            header("Location: producto.php?action=listar&error=3");
            exit;
        }
        
        include_once __DIR__ . "/views/header.php";
        
        $categorias = $categoriaModel->read();
        $desdeRouter = true;
        include __DIR__ . "/views/producto/_form_update.php";  // Archivo separado
        break;
}
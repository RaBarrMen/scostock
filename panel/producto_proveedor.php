<?php
session_start();
require_once "../models/sistema.php";
require_once "../models/producto_proveedor.php";
require_once "../models/producto.php";
require_once "../models/proveedor.php";

// Verificar que el usuario esté logueado
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php?action=form");
    exit;
}

$sistema = new Sistema();
$productoProveedor = new ProductoProveedor();
$productoModel = new Producto();
$proveedorModel = new Proveedor();

$action = $_GET['action'] ?? 'listar';

switch ($action) {

    /* ============================
       CASOS QUE HACEN REDIRECT
    ============================ */
    case 'save':
        // Solo ADMIN y OPERADOR pueden asignar proveedores
        $sistema->checarRol(['ADMIN', 'OPERADOR']);
        
        $id_producto = $_POST['id_producto'] ?? 0;
        $id_proveedor = $_POST['id_proveedor'] ?? 0;

        if ($id_producto && $id_proveedor) {
            $resultado = $productoProveedor->create($id_producto, $id_proveedor);
            
            if ($resultado === -1) {
                header("Location: producto_proveedor.php?action=listar&error=6"); // Ya existe
            } elseif ($resultado > 0) {
                header("Location: producto_proveedor.php?action=listar&success=1");
            } else {
                header("Location: producto_proveedor.php?action=create&error=1");
            }
        } else {
            header("Location: producto_proveedor.php?action=create&error=2");
        }
        exit;

    case 'delete':
        // Solo ADMIN y OPERADOR pueden eliminar relaciones
        $sistema->checarRol(['ADMIN', 'OPERADOR']);
        
        $id_producto = $_GET['id_producto'] ?? 0;
        $id_proveedor = $_GET['id_proveedor'] ?? 0;
        
        if ($id_producto && $id_proveedor) {
            $resultado = $productoProveedor->delete($id_producto, $id_proveedor);
            
            if ($resultado > 0) {
                header("Location: producto_proveedor.php?action=listar&success=3");
            } else {
                header("Location: producto_proveedor.php?action=listar&error=4");
            }
        } else {
            header("Location: producto_proveedor.php?action=listar&error=3");
        }
        exit;

    /* ============================
       CASOS QUE MUESTRAN VISTAS
    ============================ */
    case 'listar':
    default:
        // Todos los roles pueden ver
        $sistema->checarRol(['ADMIN', 'OPERADOR', 'PROPIETARIO', 'VENDEDOR']);
        
        include_once __DIR__ . "/views/header.php";
        
        $data = $productoProveedor->read();
        
        if ($data === false || $data === null) {
            $data = [];
        }
        
        $desdeRouter = true;
        include __DIR__ . "/views/producto_proveedor/index.php";
        break;

    case 'create':
        // Solo ADMIN y OPERADOR pueden crear
        $sistema->checarRol(['ADMIN', 'OPERADOR']);
        
        include_once __DIR__ . "/views/header.php";
        
        $productos = $productoModel->read();
        $proveedores = $proveedorModel->read();
        
        $desdeRouter = true;
        include __DIR__ . "/views/producto_proveedor/_form.php";
        break;
}
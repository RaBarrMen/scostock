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
$productoModel = new Producto();
$categoriaModel = new Categoria();

// VENDEDOR, OPERADOR, ADMIN y PROPIETARIO pueden ver el catálogo
$sistema->checarRol(['VENDEDOR', 'OPERADOR', 'ADMIN', 'PROPIETARIO']);

$action = $_GET['action'] ?? 'listar';

switch ($action) {

    /* ============================
       ACTUALIZAR STOCK
    ============================ */
    case 'actualizar_stock':
        $id_producto = $_POST['id_producto'] ?? 0;
        $tipo = $_POST['tipo'] ?? 'AJUSTE';
        $cantidad = intval($_POST['cantidad'] ?? 0);

        if ($cantidad < 0) {
            header("Location: catalogo.php?action=listar&error=cantidad_invalida");
            exit;
        }

        $resultado = $productoModel->actualizarStock($id_producto, $tipo, $cantidad);

        if ($resultado === -1) {
            header("Location: catalogo.php?action=listar&error=stock_negativo");
        } elseif ($resultado > 0) {
            header("Location: catalogo.php?action=listar&success=stock_actualizado");
        } else {
            header("Location: catalogo.php?action=listar&error=1");
        }
        exit;

    /* ============================
       LISTAR CATÁLOGO
    ============================ */
    case 'listar':
    default:
        include_once __DIR__ . "/views/header.php";
        
        // Obtener filtro de categoría si existe
        $id_categoria = $_GET['categoria'] ?? null;
        
        // Obtener productos (filtrados o todos)
        if ($id_categoria) {
            $productos = $productoModel->readByCategoria($id_categoria);
        } else {
            $productos = $productoModel->readActivos();
        }
        
        // Obtener todas las categorías para el filtro
        $categorias = $categoriaModel->read();
        
        $desdeRouter = true;
        include __DIR__ . "/views/catalogo/index.php";
        break;
}
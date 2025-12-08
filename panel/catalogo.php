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
    case 'listar':
    default:
        include_once __DIR__ . "/views/header.php";
        
        // Obtener filtro de categoría si existe
        $id_categoria = $_GET['categoria'] ?? null;
        
        // Obtener productos (filtrados o todos)
        if ($id_categoria) {
            // Filtrar por categoría
            $productos = $productoModel->readByCategoria($id_categoria);
        } else {
            // Todos los productos activos
            $productos = $productoModel->readActivos();
        }
        
        // Obtener todas las categorías para el filtro
        $categorias = $categoriaModel->read();
        
        $desdeRouter = true;
        include __DIR__ . "/views/catalogo/index.php";
        break;
}
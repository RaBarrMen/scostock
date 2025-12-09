<?php
session_start();
require_once "../models/sistema.php";
require_once "../models/producto.php";
require_once "../models/categoria.php";
require_once "../models/proveedor.php";

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php?action=form");
    exit;
}

$sistema = new Sistema();
$sistema->checarRol(['ADMIN', 'OPERADOR', 'PROPIETARIO']);

$productoModel = new Producto();
$categoriaModel = new Categoria();
$proveedorModel = new Proveedor();

include_once __DIR__ . "/views/header.php";

// Obtener datos para las gráficas
$productos = $productoModel->read();
$categorias = $categoriaModel->read();
$proveedores = $proveedorModel->read();

// Preparar datos para gráficas
$productosStockBajo = 0;
$productosStockNormal = 0;
$productosStockAlto = 0;
$valorTotalInventario = 0;
$productosActivos = 0;
$productosInactivos = 0;

foreach ($productos as $prod) {
    $stock = intval($prod['stock'] ?? 0);
    $minStock = floatval($prod['min_stock'] ?? 0);
    $activo = intval($prod['activo'] ?? 1);
    
    // Contar activos/inactivos
    if ($activo == 1) {
        $productosActivos++;
    } else {
        $productosInactivos++;
    }
    
    // Clasificar por stock
    if ($stock <= 0) {
        $productosStockBajo++;
    } elseif ($stock <= $minStock) {
        $productosStockBajo++;
    } elseif ($stock <= $minStock * 2) {
        $productosStockNormal++;
    } else {
        $productosStockAlto++;
    }
    
    $valorTotalInventario += ($prod['precio_venta'] ?? 0) * $stock;
}

// Productos por categoría (para gráfica de barras)
$productosPorCategoria = [];
$stockPorCategoria = [];
foreach ($categorias as $cat) {
    $count = 0;
    $stockTotal = 0;
    foreach ($productos as $prod) {
        if (($prod['id_categoria'] ?? 0) == $cat['id_categoria']) {
            $count++;
            $stockTotal += intval($prod['stock'] ?? 0);
        }
    }
    $productosPorCategoria[$cat['nombre']] = $count;
    $stockPorCategoria[$cat['nombre']] = $stockTotal;
}

// Top 5 productos más caros
$productosOrdenados = $productos;
usort($productosOrdenados, function($a, $b) {
    return ($b['precio_venta'] ?? 0) <=> ($a['precio_venta'] ?? 0);
});
$top5Caros = array_slice($productosOrdenados, 0, 5);

$desdeRouter = true;
include __DIR__ . "/views/dashboard/index.php";
?>
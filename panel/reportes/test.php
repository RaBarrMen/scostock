<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Test de Reportes</h1>";

session_start();
echo "<p>Sesión activa: " . (isset($_SESSION['id_usuario']) ? 'SÍ' : 'NO') . "</p>";

if (isset($_SESSION['id_usuario'])) {
    echo "<p>Usuario: " . $_SESSION['nombre'] . "</p>";
    echo "<p>Roles: " . implode(', ', $_SESSION['roles'] ?? []) . "</p>";
}

echo "<p>Ruta actual: " . __DIR__ . "</p>";

// Probar conexión a BD
require_once "../../models/sistema.php";
require_once "../../models/reporte.php";

$reporte = new Reporte();

try {
    $data = $reporte->getCategorias();
    echo "<p>✅ Conexión a BD exitosa</p>";
    echo "<p>Total categorías: " . count($data) . "</p>";
    echo "<pre>";
    print_r($data);
    echo "</pre>";
} catch (Exception $e) {
    echo "<p>❌ Error: " . $e->getMessage() . "</p>";
}

// Verificar Dompdf
echo "<h2>Test Dompdf</h2>";
try {
    require_once '../../vendor/autoload.php';
    echo "<p>✅ Dompdf cargado correctamente</p>";
    
    $dompdf = new \Dompdf\Dompdf();
    echo "<p>✅ Instancia de Dompdf creada</p>";
} catch (Exception $e) {
    echo "<p>❌ Error con Dompdf: " . $e->getMessage() . "</p>";
}
<?php
// IMPORTANTE: Iniciar output buffering ANTES de cualquier cosa
ob_start();

session_start();
require_once "../../models/sistema.php";
require_once "../../models/reporte.php";

// Verificar que el usuario esté logueado
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../login.php?action=form");
    exit;
}

$sistema = new Sistema();
$reporte = new Reporte();

// Verificar permisos (ADMIN, OPERADOR y PROPIETARIO pueden ver reportes)
$sistema->checarRol(['ADMIN', 'OPERADOR', 'PROPIETARIO']);

$tipo = $_GET['tipo'] ?? 'categorias';
$formato = $_GET['formato'] ?? 'html'; // html o pdf

// Datos de la empresa
$empresa = [
    'nombre' => 'SCOSTOCK',
    'direccion' => 'Celaya, Guanajuato, México',
    'telefono' => '461-XXX-XXXX',
    'email' => 'contacto@scostock.com',
    'logo' => '../../images/logo.png'
];

// Obtener datos según el tipo de reporte
switch ($tipo) {
    case 'categorias':
        $titulo = 'Reporte de Categorías';
        $data = $reporte->getCategorias();
        $vista = 'categorias';
        break;
    
    case 'productos':
        $titulo = 'Reporte de Productos';
        $data = $reporte->getProductos();
        $vista = 'productos';
        break;
    
    case 'proveedores':
        $titulo = 'Reporte de Proveedores';
        $data = $reporte->getProveedores();
        $vista = 'proveedores';
        break;
    
    case 'usuarios':
        $titulo = 'Reporte de Usuarios y Roles';
        $data = $reporte->getUsuarios();
        $vista = 'usuarios';
        break;

    case 'roles':
        require_once "../../models/rol.php";
        $rolModel = new Rol();
        $titulo = 'Reporte de Roles';
        $data = $rolModel->read();
        $vista = 'roles';
        break;
    
    case 'privilegios':
        require_once "../../models/privilegio.php";
        $privilegioModel = new Privilegio();
        $titulo = 'Reporte de Privilegios';
        $data = $privilegioModel->read();
        $vista = 'privilegios';
        break;
    
    case 'roles_privilegios':
        $titulo = 'Reporte de Roles y Privilegios';
        $data = $reporte->getRolesPrivilegios();
        $vista = 'roles_privilegios';
        break;
    
    case 'productos_stock_bajo':
        $titulo = 'Reporte de Productos con Stock Bajo';
        $data = $reporte->getProductosStockBajo();
        $vista = 'productos_stock_bajo';
        break;
    
    case 'productos_por_categoria':
        $titulo = 'Reporte de Productos por Categoría';
        $data = $reporte->getProductosPorCategoria();
        $vista = 'productos_por_categoria';
        break;
    
    case 'usuario_rol':
        require_once "../../models/usuario_rol.php";
        $usuarioRolModel = new UsuarioRol();
        $titulo = 'Reporte de Usuarios y Roles Asignados';
        $data = $usuarioRolModel->read();
        $vista = 'usuario_rol';
        break;

    case 'rol_privilegio':
        require_once "../../models/rol_privilegio.php";
        $rolPrivilegioModel = new RolPrivilegio();
        $titulo = 'Reporte de Roles y Privilegios Asignados';
        $data = $rolPrivilegioModel->read();
        $vista = 'rol_privilegio';
        break;
    
        case 'producto_proveedor':
            require_once "../../models/producto_proveedor.php";
            $productoProveedorModel = new ProductoProveedor();
            $titulo = 'Reporte de Productos y Proveedores';
            $data = $productoProveedorModel->read();
            $vista = 'producto_proveedor';
            break;
    
    default:
        die("Tipo de reporte no válido");
}

// Si se solicita PDF, generar y mostrar
if ($formato === 'pdf') {
    // Limpiar cualquier salida previa
    ob_end_clean();
    
    // Iniciar nuevo buffer limpio
    ob_start();
    
    require_once '../../vendor/autoload.php';
    
    // Capturar el HTML de la vista
    include "views/{$vista}.php";
    $html = ob_get_clean();
    
    // Generar PDF
    $dompdf = new \Dompdf\Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    
    // Enviar el PDF al navegador
    $dompdf->stream("reporte_{$tipo}_" . date('Y-m-d') . ".pdf", ["Attachment" => false]);
    exit;
}

// Mostrar HTML (limpiar buffer y mostrar)
ob_end_flush();
include "views/{$vista}.php";
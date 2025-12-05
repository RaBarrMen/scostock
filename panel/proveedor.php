<?php
session_start();
require_once "../models/sistema.php";
require_once "../models/proveedor.php";

// Verificar que el usuario esté logueado
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php?action=form");
    exit;
}

$sistema = new Sistema();
$proveedor = new Proveedor();

$action = $_GET['action'] ?? 'listar';

switch ($action) {

    /* ============================
       CASOS QUE HACEN REDIRECT
    ============================ */
    case 'save':
        // ADMIN y OPERADOR pueden crear proveedores
        $sistema->checarRol(['ADMIN', 'OPERADOR']);
        
        $data = [
            'nombre' => $_POST['nombre'] ?? '',
            'telefono' => $_POST['telefono'] ?? '',
            'email' => $_POST['email'] ?? ''
        ];

        if ($proveedor->validate($data)) {
            $resultado = $proveedor->create($data);
            
            if ($resultado > 0) {
                header("Location: proveedor.php?action=listar&success=1");
            } else {
                header("Location: proveedor.php?action=create&error=1");
            }
        } else {
            header("Location: proveedor.php?action=create&error=2");
        }
        exit;

    case 'edited':
        // ADMIN y OPERADOR pueden editar proveedores
        $sistema->checarRol(['ADMIN', 'OPERADOR']);
        
        $id = $_POST['id_proveedor'] ?? 0;
        $data = [
            'nombre' => $_POST['nombre'] ?? '',
            'telefono' => $_POST['telefono'] ?? '',
            'email' => $_POST['email'] ?? ''
        ];

        if ($proveedor->validate($data)) {
            $resultado = $proveedor->update($data, $id);
            
            if ($resultado > 0) {
                header("Location: proveedor.php?action=listar&success=2");
            } else {
                header("Location: proveedor.php?action=update&id=$id&error=1");
            }
        } else {
            header("Location: proveedor.php?action=update&id=$id&error=2");
        }
        exit;

    case 'delete':
        // Solo ADMIN puede eliminar proveedores
        $sistema->checarRol('ADMIN');
        
        $id = $_GET['id'] ?? 0;
        $resultado = $proveedor->delete($id);
        
        if ($resultado > 0) {
            header("Location: proveedor.php?action=listar&success=3");
        } else if ($resultado === -1) {
            header("Location: proveedor.php?action=listar&error=5"); // Tiene productos asociados
        } else {
            header("Location: proveedor.php?action=listar&error=4");
        }
        exit;

    /* ============================
       CASOS QUE MUESTRAN VISTAS
    ============================ */
    case 'listar':
    default:
        // ADMIN y OPERADOR pueden ver proveedores
        $sistema->checarRol(['ADMIN', 'OPERADOR']);
        
        include_once __DIR__ . "/views/header.php";
        
        $data = $proveedor->read();
        
        if ($data === false || $data === null) {
            $data = [];
        }
        
        $desdeRouter = true;
        include __DIR__ . "/views/proveedor/index.php";
        break;

    case 'create':
        // ADMIN y OPERADOR pueden crear proveedores
        $sistema->checarRol(['ADMIN', 'OPERADOR']);
        
        include_once __DIR__ . "/views/header.php";
        
        $desdeRouter = true;
        include __DIR__ . "/views/proveedor/_form.php";
        break;

    case 'update':
        // ADMIN y OPERADOR pueden editar proveedores
        $sistema->checarRol(['ADMIN', 'OPERADOR']);
        
        $id = $_GET['id'] ?? 0;
        $data = $proveedor->readOne($id);
        
        if (!$data) {
            header("Location: proveedor.php?action=listar&error=3");
            exit;
        }
        
        include_once __DIR__ . "/views/header.php";
        
        $desdeRouter = true;
        include __DIR__ . "/views/proveedor/_form_update.php";
        break;
}
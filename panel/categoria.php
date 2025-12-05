<?php
session_start();
require_once "../models/sistema.php";
require_once "../models/categoria.php";

// Verificar que el usuario esté logueado
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php?action=form");
    exit;
}

$sistema = new Sistema();
$categoria = new Categoria();

$action = $_GET['action'] ?? 'listar';

switch ($action) {

    /* ============================
       CASOS QUE HACEN REDIRECT (sin header.php)
    ============================ */
    case 'save':
        $sistema->checarRol('ADMIN');
        
        $data = [
            'nombre' => $_POST['nombre'] ?? '',
            'descripcion' => $_POST['descripcion'] ?? ''
        ];

        if ($categoria->validate($data)) {
            $resultado = $categoria->create($data);
            
            if ($resultado > 0) {
                header("Location: categoria.php?action=listar&success=1");
            } else {
                header("Location: categoria.php?action=create&error=1");
            }
        } else {
            header("Location: categoria.php?action=create&error=2");
        }
        exit;

    case 'edited':
        $sistema->checarRol('ADMIN');
        
        $id = $_POST['id_categoria'] ?? 0;
        $data = [
            'nombre' => $_POST['nombre'] ?? '',
            'descripcion' => $_POST['descripcion'] ?? ''
        ];

        if ($categoria->validate($data)) {
            $resultado = $categoria->update($data, $id);
            
            if ($resultado > 0) {
                header("Location: categoria.php?action=listar&success=2");
            } else {
                header("Location: categoria.php?action=update&id=$id&error=1");
            }
        } else {
            header("Location: categoria.php?action=update&id=$id&error=2");
        }
        exit;

    case 'delete':
        $sistema->checarRol('ADMIN');
        
        $id = $_GET['id'] ?? 0;
        $resultado = $categoria->delete($id);
        
        if ($resultado > 0) {
            header("Location: categoria.php?action=listar&success=3");
        } else if ($resultado === 0) {
            header("Location: categoria.php?action=listar&error=5");
        } else {
            header("Location: categoria.php?action=listar&error=4");
        }
        exit;

    /* ============================
       CASOS QUE MUESTRAN VISTAS (con header.php)
    ============================ */
    case 'listar':
    default:
        $sistema->checarRol(['ADMIN', 'OPERADOR']);
        
        // Incluir header AQUÍ
        include_once __DIR__ . "/views/header.php";
        
        $data = $categoria->read();
        
        if ($data === false || $data === null) {
            $data = [];
        }
        
        $desdeRouter = true;
        include __DIR__ . "/views/categoria/index.php";
        break;

    case 'create':
        $sistema->checarRol('ADMIN');
        
        // Incluir header AQUÍ
        include_once __DIR__ . "/views/header.php";
        
        $desdeRouter = true;
        include __DIR__ . "/views/categoria/_form.php";
        break;

    case 'update':
        $sistema->checarRol('ADMIN');
        
        $id = $_GET['id'] ?? 0;
        $data = $categoria->readOne($id);
        
        if (!$data) {
            header("Location: categoria.php?action=listar&error=3");
            exit;
        }
        
        // Incluir header AQUÍ
        include_once __DIR__ . "/views/header.php";
        
        $desdeRouter = true;
        include __DIR__ . "/views/categoria/_form_update.php";
        break;
}
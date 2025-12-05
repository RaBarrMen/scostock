<?php
session_start();
require_once "../models/sistema.php";
require_once "../models/rol.php";

// Verificar que el usuario esté logueado
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php?action=form");
    exit;
}

$sistema = new Sistema();
$rol = new Rol();

$action = $_GET['action'] ?? 'listar';

switch ($action) {

    /* ============================
       CASOS QUE HACEN REDIRECT
    ============================ */
    case 'save':
        // Solo ADMIN puede crear roles
        $sistema->checarRol('ADMIN');
        
        $data = [
            'nombre' => $_POST['nombre'] ?? ''
        ];

        if ($rol->validate($data)) {
            $resultado = $rol->create($data);
            
            if ($resultado > 0) {
                header("Location: rol.php?action=listar&success=1");
            } else {
                header("Location: rol.php?action=create&error=1");
            }
        } else {
            header("Location: rol.php?action=create&error=2");
        }
        exit;

    case 'edited':
        // Solo ADMIN puede editar roles
        $sistema->checarRol('ADMIN');
        
        $id = $_POST['id_rol'] ?? 0;
        $data = [
            'nombre' => $_POST['nombre'] ?? ''
        ];

        if ($rol->validate($data)) {
            $resultado = $rol->update($data, $id);
            
            if ($resultado > 0) {
                header("Location: rol.php?action=listar&success=2");
            } else {
                header("Location: rol.php?action=update&id=$id&error=1");
            }
        } else {
            header("Location: rol.php?action=update&id=$id&error=2");
        }
        exit;

    case 'delete':
        // Solo ADMIN puede eliminar roles
        $sistema->checarRol('ADMIN');
        
        $id = $_GET['id'] ?? 0;
        $resultado = $rol->delete($id);
        
        if ($resultado > 0) {
            header("Location: rol.php?action=listar&success=3");
        } else if ($resultado === -1) {
            header("Location: rol.php?action=listar&error=5"); // Está asignado a usuarios
        } else {
            header("Location: rol.php?action=listar&error=4");
        }
        exit;

    /* ============================
       CASOS QUE MUESTRAN VISTAS
    ============================ */
    case 'listar':
    default:
        // Solo ADMIN puede ver roles
        $sistema->checarRol('ADMIN');
        
        include_once __DIR__ . "/views/header.php";
        
        $data = $rol->read();
        
        if ($data === false || $data === null) {
            $data = [];
        }
        
        $desdeRouter = true;
        include __DIR__ . "/views/rol/index.php";
        break;

    case 'create':
        // Solo ADMIN puede crear roles
        $sistema->checarRol('ADMIN');
        
        include_once __DIR__ . "/views/header.php";
        
        $desdeRouter = true;
        include __DIR__ . "/views/rol/_form.php";
        break;

    case 'update':
        // Solo ADMIN puede editar roles
        $sistema->checarRol('ADMIN');
        
        $id = $_GET['id'] ?? 0;
        $data = $rol->readOne($id);
        
        if (!$data) {
            header("Location: rol.php?action=listar&error=3");
            exit;
        }
        
        include_once __DIR__ . "/views/header.php";
        
        $desdeRouter = true;
        include __DIR__ . "/views/rol/_form_update.php";
        break;
}
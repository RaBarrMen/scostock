<?php
session_start();
require_once "../models/sistema.php";
require_once "../models/privilegio.php";

// Verificar que el usuario esté logueado
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php?action=form");
    exit;
}

$sistema = new Sistema();
$privilegio = new Privilegio();

$action = $_GET['action'] ?? 'listar';

switch ($action) {

    /* ============================
       CASOS QUE HACEN REDIRECT
    ============================ */
    case 'save':
        // Solo ADMIN puede crear privilegios
        $sistema->checarRol('ADMIN');
        
        $data = [
            'privilegio' => $_POST['privilegio'] ?? ''
        ];

        if ($privilegio->validate($data)) {
            $resultado = $privilegio->create($data);
            
            if ($resultado > 0) {
                header("Location: privilegio.php?action=listar&success=1");
            } else {
                header("Location: privilegio.php?action=create&error=1");
            }
        } else {
            header("Location: privilegio.php?action=create&error=2");
        }
        exit;

    case 'edited':
        // Solo ADMIN puede editar privilegios
        $sistema->checarRol('ADMIN');
        
        $id = $_POST['id_privilegio'] ?? 0;
        $data = [
            'privilegio' => $_POST['privilegio'] ?? ''
        ];

        if ($privilegio->validate($data)) {
            $resultado = $privilegio->update($data, $id);
            
            if ($resultado > 0) {
                header("Location: privilegio.php?action=listar&success=2");
            } else {
                header("Location: privilegio.php?action=update&id=$id&error=1");
            }
        } else {
            header("Location: privilegio.php?action=update&id=$id&error=2");
        }
        exit;

    case 'delete':
        // Solo ADMIN puede eliminar privilegios
        $sistema->checarRol('ADMIN');
        
        $id = $_GET['id'] ?? 0;
        $resultado = $privilegio->delete($id);
        
        if ($resultado > 0) {
            header("Location: privilegio.php?action=listar&success=3");
        } else if ($resultado === -1) {
            header("Location: privilegio.php?action=listar&error=5"); // Está asignado a roles
        } else {
            header("Location: privilegio.php?action=listar&error=4");
        }
        exit;

    /* ============================
       CASOS QUE MUESTRAN VISTAS
    ============================ */
    case 'listar':
    default:
        // Solo ADMIN puede ver privilegios
        $sistema->checarRol('ADMIN');
        
        include_once __DIR__ . "/views/header.php";
        
        $data = $privilegio->read();
        
        if ($data === false || $data === null) {
            $data = [];
        }
        
        $desdeRouter = true;
        include __DIR__ . "/views/privilegio/index.php";
        break;

    case 'create':
        // Solo ADMIN puede crear privilegios
        $sistema->checarRol('ADMIN');
        
        include_once __DIR__ . "/views/header.php";
        
        $desdeRouter = true;
        include __DIR__ . "/views/privilegio/_form.php";
        break;

    case 'update':
        // Solo ADMIN puede editar privilegios
        $sistema->checarRol('ADMIN');
        
        $id = $_GET['id'] ?? 0;
        $data = $privilegio->readOne($id);
        
        if (!$data) {
            header("Location: privilegio.php?action=listar&error=3");
            exit;
        }
        
        include_once __DIR__ . "/views/header.php";
        
        $desdeRouter = true;
        include __DIR__ . "/views/privilegio/_form_update.php";
        break;
}
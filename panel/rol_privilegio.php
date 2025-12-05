<?php
session_start();
require_once "../models/sistema.php";
require_once "../models/rol_privilegio.php";
require_once "../models/rol.php";
require_once "../models/privilegio.php";

// Verificar que el usuario esté logueado
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php?action=form");
    exit;
}

$sistema = new Sistema();
$rolPrivilegio = new RolPrivilegio();
$rolModel = new Rol();
$privilegioModel = new Privilegio();

$action = $_GET['action'] ?? 'listar';

switch ($action) {

    /* ============================
       CASOS QUE HACEN REDIRECT
    ============================ */
    case 'save':
        // Solo ADMIN puede asignar privilegios
        $sistema->checarRol('ADMIN');
        
        $id_rol = $_POST['id_rol'] ?? 0;
        $id_privilegio = $_POST['id_privilegio'] ?? 0;

        if ($id_rol && $id_privilegio) {
            $resultado = $rolPrivilegio->create($id_rol, $id_privilegio);
            
            if ($resultado === -1) {
                header("Location: rol_privilegio.php?action=listar&error=6"); // Ya existe
            } elseif ($resultado > 0) {
                header("Location: rol_privilegio.php?action=listar&success=1");
            } else {
                header("Location: rol_privilegio.php?action=create&error=1");
            }
        } else {
            header("Location: rol_privilegio.php?action=create&error=2");
        }
        exit;

    case 'delete':
        // Solo ADMIN puede eliminar privilegios
        $sistema->checarRol('ADMIN');
        
        $id_rol = $_GET['id_rol'] ?? 0;
        $id_privilegio = $_GET['id_privilegio'] ?? 0;
        
        if ($id_rol && $id_privilegio) {
            $resultado = $rolPrivilegio->delete($id_rol, $id_privilegio);
            
            if ($resultado > 0) {
                header("Location: rol_privilegio.php?action=listar&success=3");
            } else {
                header("Location: rol_privilegio.php?action=listar&error=4");
            }
        } else {
            header("Location: rol_privilegio.php?action=listar&error=3");
        }
        exit;

    /* ============================
       CASOS QUE MUESTRAN VISTAS
    ============================ */
    case 'listar':
    default:
        // Solo ADMIN puede ver asignaciones
        $sistema->checarRol('ADMIN');
        
        include_once __DIR__ . "/views/header.php";
        
        $data = $rolPrivilegio->read();
        
        if ($data === false || $data === null) {
            $data = [];
        }
        
        $desdeRouter = true;
        include __DIR__ . "/views/rol_privilegio/index.php";
        break;

    case 'create':
        // Solo ADMIN puede asignar privilegios
        $sistema->checarRol('ADMIN');
        
        include_once __DIR__ . "/views/header.php";
        
        $roles = $rolModel->read();
        $privilegios = $privilegioModel->read();
        
        $desdeRouter = true;
        include __DIR__ . "/views/rol_privilegio/_form.php";
        break;
}
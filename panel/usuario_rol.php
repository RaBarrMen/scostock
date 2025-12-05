<?php
session_start();
require_once "../models/sistema.php";
require_once "../models/usuario_rol.php";
require_once "../models/usuario.php";
require_once "../models/rol.php";

// Verificar que el usuario esté logueado
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php?action=form");
    exit;
}

$sistema = new Sistema();
$usuarioRol = new UsuarioRol();
$usuarioModel = new Usuario();
$rolModel = new Rol();

$action = $_GET['action'] ?? 'listar';

switch ($action) {

    /* ============================
       CASOS QUE HACEN REDIRECT
    ============================ */
    case 'save':
        // Solo ADMIN puede asignar roles
        $sistema->checarRol('ADMIN');
        
        $id_usuario = $_POST['id_usuario'] ?? 0;
        $id_rol = $_POST['id_rol'] ?? 0;

        if ($id_usuario && $id_rol) {
            $resultado = $usuarioRol->create($id_usuario, $id_rol);
            
            if ($resultado === -1) {
                header("Location: usuario_rol.php?action=listar&error=6"); // Ya existe
            } elseif ($resultado > 0) {
                header("Location: usuario_rol.php?action=listar&success=1");
            } else {
                header("Location: usuario_rol.php?action=create&error=1");
            }
        } else {
            header("Location: usuario_rol.php?action=create&error=2");
        }
        exit;

    case 'delete':
        // Solo ADMIN puede eliminar roles de usuarios
        $sistema->checarRol('ADMIN');
        
        $id_usuario = $_GET['id_usuario'] ?? 0;
        $id_rol = $_GET['id_rol'] ?? 0;
        
        if ($id_usuario && $id_rol) {
            $resultado = $usuarioRol->delete($id_usuario, $id_rol);
            
            if ($resultado > 0) {
                header("Location: usuario_rol.php?action=listar&success=3");
            } else {
                header("Location: usuario_rol.php?action=listar&error=4");
            }
        } else {
            header("Location: usuario_rol.php?action=listar&error=3");
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
        
        $data = $usuarioRol->read();
        
        if ($data === false || $data === null) {
            $data = [];
        }
        
        $desdeRouter = true;
        include __DIR__ . "/views/usuario_rol/index.php";
        break;

    case 'create':
        // Solo ADMIN puede asignar roles
        $sistema->checarRol('ADMIN');
        
        include_once __DIR__ . "/views/header.php";
        
        $usuarios = $usuarioModel->read();
        $roles = $rolModel->read();
        
        $desdeRouter = true;
        include __DIR__ . "/views/usuario_rol/_form.php";
        break;
}
<?php
session_start();
require_once "../models/sistema.php";
require_once "../models/usuario.php";

// Verificar que el usuario esté logueado
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php?action=form");
    exit;
}

$sistema = new Sistema();
$usuario = new Usuario();

$action = $_GET['action'] ?? 'listar';

switch ($action) {

    /* ============================
       CASOS QUE HACEN REDIRECT
    ============================ */
    case 'save':
        // Solo ADMIN puede crear usuarios
        $sistema->checarRol('ADMIN');
        
        $data = [
            'nombre' => $_POST['nombre'] ?? '',
            'email' => $_POST['email'] ?? '',
            'password' => $_POST['password'] ?? '',
            'activo' => isset($_POST['activo']) ? 1 : 0
        ];

        if ($usuario->validate($data)) {
            $resultado = $usuario->create($data);
            
            if ($resultado === -2) {
                header("Location: usuario.php?action=create&error=6"); // Email duplicado
            } elseif ($resultado > 0) {
                header("Location: usuario.php?action=listar&success=1");
            } else {
                header("Location: usuario.php?action=create&error=1");
            }
        } else {
            header("Location: usuario.php?action=create&error=2");
        }
        exit;

    case 'edited':
        // Solo ADMIN puede editar usuarios
        $sistema->checarRol('ADMIN');
        
        $id = $_POST['id_usuario'] ?? 0;
        $data = [
            'nombre' => $_POST['nombre'] ?? '',
            'email' => $_POST['email'] ?? '',
            'password' => $_POST['password'] ?? '', // Puede estar vacío
            'activo' => isset($_POST['activo']) ? 1 : 0
        ];

        if ($usuario->validate($data)) {
            $resultado = $usuario->update($data, $id);
            
            if ($resultado > 0) {
                header("Location: usuario.php?action=listar&success=2");
            } else {
                header("Location: usuario.php?action=update&id=$id&error=1");
            }
        } else {
            header("Location: usuario.php?action=update&id=$id&error=2");
        }
        exit;

    case 'delete':
        // Solo ADMIN puede eliminar usuarios
        $sistema->checarRol('ADMIN');
        
        $id = $_GET['id'] ?? 0;
        $resultado = $usuario->delete($id);
        
        if ($resultado > 0) {
            header("Location: usuario.php?action=listar&success=3");
        } else if ($resultado === -1) {
            header("Location: usuario.php?action=listar&error=7"); // Tiene roles asignados
        } else {
            header("Location: usuario.php?action=listar&error=4");
        }
        exit;

    /* ============================
       CASOS QUE MUESTRAN VISTAS
    ============================ */
    case 'listar':
    default:
        // Solo ADMIN puede ver usuarios
        $sistema->checarRol('ADMIN');
        
        include_once __DIR__ . "/views/header.php";
        
        $data = $usuario->read();
        
        if ($data === false || $data === null) {
            $data = [];
        }
        
        $desdeRouter = true;
        include __DIR__ . "/views/usuario/index.php";
        break;

    case 'create':
        // Solo ADMIN puede crear usuarios
        $sistema->checarRol('ADMIN');
        
        include_once __DIR__ . "/views/header.php";
        
        $desdeRouter = true;
        include __DIR__ . "/views/usuario/_form.php";
        break;

    case 'update':
        // Solo ADMIN puede editar usuarios
        $sistema->checarRol('ADMIN');
        
        $id = $_GET['id'] ?? 0;
        $data = $usuario->readOne($id);
        
        if (!$data) {
            header("Location: usuario.php?action=listar&error=3");
            exit;
        }
        
        include_once __DIR__ . "/views/header.php";
        
        $desdeRouter = true;
        include __DIR__ . "/views/usuario/_form_update.php";
        break;
}
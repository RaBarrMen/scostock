<?php
session_start();

// Si no hay sesión activa, redirigir al login
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php?action=form");
    exit;
}

$desdeRouter = true;
include __DIR__ . "/views/acceso_denegado.php";
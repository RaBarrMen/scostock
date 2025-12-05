<?php
require_once "../models/sistema.php";

$sys = new Sistema();
$sys->connect();

$email    = "21031439@itcelaya.edu.mx";
$password = "123";

$hash = password_hash($password, PASSWORD_BCRYPT);

$sql = "UPDATE usuario
        SET hash_password = :hash, activo = 1
        WHERE email = :email";

$sth = $sys->_DB->prepare($sql);
$sth->bindParam(":hash", $hash);
$sth->bindParam(":email", $email);
$sth->execute();

echo "Listo, intenta entrar con la contraseña: $password";

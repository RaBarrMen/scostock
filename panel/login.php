<?php
session_start();
require_once "../models/usuario.php";

$usuario = new Usuario();
$action  = $_GET['action'] ?? 'form';   // valor por defecto

switch ($action) {

    /* ============================
       MOSTRAR FORM LOGIN
    ============================ */
    case 'form':
    default:
        // Bandera para saber que la vista se carga DESDE el router
        $desdeRouter = true;
        include __DIR__ . "/views/login/login.php";
        break;

    /* ============================
       LOGIN
    ============================ */
    case 'login':

    $email    = $_POST['email']    ?? '';
    $password = $_POST['password'] ?? '';

    $user = $usuario->login($email, $password);

    if ($user) {
        // Guardar info en sesión
        $_SESSION['id_usuario']  = $user['id_usuario'];
        $_SESSION['nombre']      = $user['nombre'];
        $_SESSION['email']       = $user['email'];
        $_SESSION['roles']       = $user['roles'];
        $_SESSION['privilegios'] = $user['privilegios'];

        // REDIRIGIR AL ENRUTADOR DE CATEGORÍA (no a la vista)
        header("Location: categoria.php?action=listar");
        exit;
    }

    // Si no coincide el login -> regresar al router de login
    header("Location: login.php?action=form&error=1");
    exit;


    /* ============================
       ENVIAR TOKEN RECUPERACIÓN
    ============================ */
    case 'enviar_token':

        $email = $_POST['email'] ?? '';
        $user  = $usuario->buscarPorEmail($email);

        if ($user) {
            $token = bin2hex(random_bytes(50));
            $usuario->guardarToken($user['id_usuario'], $token);

            require __DIR__ . "/../vendor/autoload.php";

            $mail = new PHPMailer\PHPMailer\PHPMailer();
            $mail->isSMTP();
            $mail->Host       = "smtp.gmail.com";
            $mail->SMTPAuth   = true;
            $mail->Username   = "21031439@itcelaya.edu.mx";
            $mail->Password   = "movoxflazguaitig";
            $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = 465;

            $mail->setFrom("21031439@itcelaya.edu.mx", "SCOSTOCK");
            $mail->addAddress($email);

            $mail->Subject = "Recuperación de contraseña";
            $mail->msgHTML("
                <h2>Recuperación de contraseña</h2>
                <p>Haz clic en el enlace:</p>
                <a href='http://localhost:8080/scostock/panel/login.php?action=token&token=$token'>
                    Recuperar contraseña
                </a>
            ");

            $mail->send();
        }

        header("Location: ./login.php?action=recuperar&enviado=1");
        exit;


    /* ============================
       MOSTRAR FORM RECUPERAR
    ============================ */
    case 'recuperar':
        $desdeRouter = true;
        include __DIR__ . "/views/login/recuperar.php";
        break;


    /* ============================
       MOSTRAR FORM TOKEN
    ============================ */
    case 'token':
        $desdeRouter = true;
        include __DIR__ . "/views/login/token.php";
        break;


    /* ============================
       RESET PASSWORD
    ============================ */
    case 'reset_password':

        $token    = $_POST['token']    ?? '';
        $password = $_POST['password'] ?? '';

        $user = $usuario->validarToken($token);

        if ($user) {
            $usuario->actualizarPassword($user['id_usuario'], $password);
            header("Location: ./login.php?reset=1");
        } else {
            header("Location: ./login.php?error=1");
        }
        exit;


    /* ============================
       LOGOUT
    ============================ */
    case "logout":
        session_destroy();
        header("Location: ./login.php");
        exit;
}

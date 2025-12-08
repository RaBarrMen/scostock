<?php
session_start();
require_once "../models/usuario.php";

$usuario = new Usuario();
$action  = $_GET['action'] ?? 'form';

switch ($action) {

    case 'form':
    default:
        $desdeRouter = true;
        include __DIR__ . "/views/login/login.php";
        break;

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

        //  REDIRIGIR SEGÚN EL ROL DEL USUARIO
        $roles = array_map('strtoupper', $user['roles']);

        // Si es ADMIN, OPERADOR o PROPIETARIO → Panel de administración
        if (in_array('ADMIN', $roles) || in_array('OPERADOR', $roles) || in_array('PROPIETARIO', $roles)) {
            header("Location: categoria.php?action=listar");
            exit;
        }

        // Si es VENDEDOR → Catálogo de productos
        if (in_array('VENDEDOR', $roles)) {
            header("Location: catalogo.php?action=listar");
            exit;
        }

        // Si tiene otro rol desconocido → Catálogo por defecto
        header("Location: catalogo.php?action=listar");
        exit;
    }

    // Si no coincide el login → regresar al router de login
    header("Location: login.php?action=form&error=1");
    exit;

    case 'enviar_token':
        $email = $_POST['email'] ?? '';
        $user  = $usuario->buscarPorEmail($email);

        if ($user) {
            $token = bin2hex(random_bytes(50));
            $usuario->guardarToken($user['id_usuario'], $token);

            // Generar URL dinámica
            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
            $host = $_SERVER['HTTP_HOST'];
            $baseUrl = $protocol . $host . dirname($_SERVER['PHP_SELF']);
            $resetUrl = $baseUrl . "/login.php?action=token&token=" . $token;

            require __DIR__ . "/../vendor/autoload.php";

            $mail = new PHPMailer\PHPMailer\PHPMailer();
            
            try {
                $mail->isSMTP();
                $mail->Host       = "smtp.gmail.com";
                $mail->SMTPAuth   = true;
                $mail->Username   = "21031439@itcelaya.edu.mx";
                $mail->Password   = "movoxflazguaitig";
                $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
                $mail->Port       = 465;
                $mail->CharSet    = 'UTF-8';

                $mail->setFrom("21031439@itcelaya.edu.mx", "SCOSTOCK");
                $mail->addAddress($email);

                $mail->isHTML(true);
                $mail->Subject = "Recuperación de contraseña - SCOSTOCK";
                
                $mail->Body = "
                <!DOCTYPE html>
                <html>
                <head>
                    <meta charset='UTF-8'>
                    <style>
                        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
                        .content { background: #f8f9fa; padding: 30px; border-radius: 0 0 10px 10px; }
                        .button { display: inline-block; padding: 15px 30px; background: #667eea; color: white; text-decoration: none; border-radius: 5px; font-weight: bold; margin: 20px 0; }
                        .footer { text-align: center; margin-top: 20px; font-size: 12px; color: #666; }
                        .warning { background: #fff3cd; border-left: 4px solid #ffc107; padding: 10px; margin: 15px 0; }
                    </style>
                </head>
                <body>
                    <div class='container'>
                        <div class='header'>
                            <h1>🔐 SCOSTOCK</h1>
                            <p>Recuperación de Contraseña</p>
                        </div>
                        <div class='content'>
                            <h2>Hola,</h2>
                            <p>Recibimos una solicitud para restablecer la contraseña de tu cuenta en SCOSTOCK.</p>
                            <p>Haz clic en el siguiente botón para crear una nueva contraseña:</p>
                            
                            <div style='text-align: center;'>
                                <a href='$resetUrl' class='button'>Restablecer Contraseña</a>
                            </div>
                            
                            <div class='warning'>
                                <strong>⏰ Este enlace expira en 15 minutos</strong>
                            </div>
                            
                            <p>Si no solicitaste este cambio, puedes ignorar este correo.</p>
                            
                            <p style='margin-top: 20px; font-size: 12px; color: #666;'>
                                Si el botón no funciona, copia y pega este enlace en tu navegador:<br>
                                <a href='$resetUrl' style='color: #667eea;'>$resetUrl</a>
                            </p>
                        </div>
                        <div class='footer'>
                            <p>Este es un correo automático, por favor no respondas.</p>
                            <p>&copy; " . date('Y') . " SCOSTOCK - Sistema de Control de Stock</p>
                        </div>
                    </div>
                </body>
                </html>
                ";

                $mail->AltBody = "Recuperación de contraseña - SCOSTOCK\n\nHaz clic en el siguiente enlace: $resetUrl\n\nEste enlace expira en 15 minutos.";

                $mail->send();
                
            } catch (Exception $e) {
                error_log("Error al enviar correo: " . $mail->ErrorInfo);
            }
        }

        header("Location: login.php?action=recuperar&enviado=1");
        exit;

    case 'recuperar':
        $desdeRouter = true;
        include __DIR__ . "/views/login/recuperar.php";
        break;

    case 'token':
        $token = $_GET['token'] ?? '';
        $user = $usuario->validarToken($token);
        
        if (!$user) {
            header("Location: login.php?action=form&error=token_invalido");
            exit;
        }
        
        $desdeRouter = true;
        include __DIR__ . "/views/login/token.php";
        break;

    case 'reset_password':
        $token    = $_POST['token']    ?? '';
        $password = $_POST['password'] ?? '';
        $password_confirm = $_POST['password_confirm'] ?? '';

        if ($password !== $password_confirm) {
            header("Location: login.php?action=token&token=$token&error=no_coinciden");
            exit;
        }

        if (strlen($password) < 6) {
            header("Location: login.php?action=token&token=$token&error=muy_corta");
            exit;
        }

        $user = $usuario->validarToken($token);

        if ($user) {
            $resultado = $usuario->actualizarPassword($user['id_usuario'], $password);
            
            if ($resultado) {
                header("Location: login.php?action=form&reset=1");
            } else {
                header("Location: login.php?action=form&error=1");
            }
        } else {
            header("Location: login.php?action=form&error=token_invalido");
        }
        exit;

    case "logout":
        session_destroy();
        header("Location: login.php?action=form");
        exit;
}
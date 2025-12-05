<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

require __DIR__ . '/../vendor/autoload.php';

class correo {

    public static function enviar($para, $asunto, $mensaje, $nombre = null) {
        $mail = new PHPMailer();

        $mail->isSMTP();
        $mail->SMTPDebug = SMTP::DEBUG_OFF; 
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 465;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->SMTPAuth = true;
        $mail->Username = '21031439@itcelaya.edu.mx';
        $mail->Password = 'movoxflazguaitig';

        $mail->setFrom('21031439@itcelaya.edu.mx', 'SCOSTOCK');
        $mail->addAddress($para, $nombre ? $nombre : $para);
        $mail->Subject = $asunto;
        $mail->msgHTML($mensaje);

        return $mail->send();
    }
}

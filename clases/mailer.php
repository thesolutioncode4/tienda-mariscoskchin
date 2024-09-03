<?php



use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Mailer {
    
    function enviarEmail($email, $asunto, $cuerpo){
        require_once __DIR__ . '/../config/config.php';
        require __DIR__ .'/../phpmailer/src/PHPMailer.php';
        require __DIR__ .'/../phpmailer/src/SMTP.php';
        require __DIR__ .'/../phpmailer/src/Exception.php';

        $password = descifrar(MAIL_PASS);
        
        //Create an instance; passing `true` enables exceptions
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->isSMTP();
            $mail->Host = MAIL_HOST;
            $mail->SMTPAuth = true;
            $mail->Username = MAIL_USER;

            // Pass dado por Google especial para esta App
            $mail->Password = $password;
            $mail->SMTPSecure = 'ssl';
            $mail->Port = MAIL_PORT;
            
            //Recipients
            $mail->setFrom('coneccionescalle@gmail.com', 'Mariscos Kchin');
            $mail->addAddress($email);     //Add a recipient

            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = $asunto;
            $mail->Body    = mb_convert_encoding($cuerpo, 'ISO-8859-1', 'UTF-8');
            $mail->setLanguage('es', __DIR__ . '/../phpmailer/language/phpmailer.lang-es.php');

            if($mail->send()){
                return true;
            } else {
                return false;
            };
        } catch (Exception $e) {
            echo "Error al enviar el correo electronico de la compra: {$mail->ErrorInfo}";
        }
    }
}
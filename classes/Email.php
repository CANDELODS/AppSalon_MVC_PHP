<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email
{
    public $email, $nombre, $token;
    public function __construct($email, $nombre, $token)
    {
        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
    }

    public function enviarConfirmacion()
    {
        //Crear EL Objeto De Mail
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $_ENV['EMAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV['EMAIL_PORT'];
        $mail->Username = $_ENV['EMAIL_USER'];
        $mail->Password = $_ENV['EMAIL_PASS'];

        $mail->setFrom('cuentas@appsalon.com'); //Quien Lo Envía?
        $mail->addAddress('cuentas@appsalon.com', 'AppSalon.com');
        $mail->Subject = 'Confirma Tu Cuenta';

        //Set HTML
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';

        $contenido = "<html>";
        $contenido .= "<p><strong>Hola " . $this->nombre . " </strong>Has Creado Tu Cuenta En Appsalon Solo Debes Confirmarla Presionando El Siquiente Enlace</p>";
        $contenido .= "<p>Presiona Aquí: <a href='" .  $_ENV['APP_URL']  ."/confirmar-cuenta?token="
            . $this->token . "'>Confirmar Cuenta</a> </p>";
        $contenido .= "<p>Si Tú No Solicitaste Este Cambio, Puedes Ignorar El Mensaje </p>";
        $contenido .= "</html>";
        $mail->Body = $contenido;

        //Enviar El Email
        $mail->send();
    }

    public function enviarInstrucciones()
    {
        //Crear EL Objeto De Mail
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $_ENV['EMAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV['EMAIL_PORT'];
        $mail->Username = $_ENV['EMAIL_USER'];
        $mail->Password = $_ENV['EMAIL_PASS'];

        $mail->setFrom('cuentas@appsalon.com'); //Quien Lo Envía?
        $mail->addAddress('cuentas@appsalon.com', 'AppSalon.com');
        $mail->Subject = 'Reestablece Tu Password';

        //Set HTML
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';

        $contenido = "<html>";
        $contenido .= "<p><strong>Hola " . $this->nombre . " </strong>Has Solicitado Reestablecer Tu Password, Sigue El Siguiente Enlace Para Hacerlo.</p>";
        $contenido .= "<p>Presiona Aquí: <a href='" .  $_ENV['APP_URL']  ."/recuperar?token="
            . $this->token . "'>Reestablecer Password</a> </p>";
        $contenido .= "<p>Si Tú No Solicitaste Este Cambio, Puedes Ignorar El Mensaje </p>";
        $contenido .= "</html>";
        $mail->Body = $contenido;

        //Enviar El Email
        $mail->send();
    }
}

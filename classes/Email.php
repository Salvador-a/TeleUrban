<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email {

    public $email;
    public $nombre;
    public $a_paterno;
    public $a_materno;
    public $fecha_hora;
    public $area;

    public function __construct($email, $nombre, $a_paterno, $a_materno, $fecha_hora, $area)
    {
        $this->email = $email;
        $this->nombre = $nombre;
        $this->a_paterno = $a_paterno;
        $this->a_materno = $a_materno;
        $this->fecha_hora = $fecha_hora;
        $this->area = $area;
    }

    public function enviarConfirmacionEntrevista() {

         // create a new object
         $mail = new PHPMailer();
         $mail->isSMTP();
         $mail->Host = $_ENV['EMAIL_HOST'];
         $mail->SMTPAuth = true;
         $mail->Port = $_ENV['EMAIL_PORT'];
         $mail->Username = $_ENV['EMAIL_USER'];
         $mail->Password = $_ENV['EMAIL_PASS'];
     
         $mail->setFrom('citas@TeleUrban.com');
         $mail->addAddress($this->email, $this->nombre);
         $mail->Subject = 'Confirmación de Cita de Entrevista';

         // Set HTML
         $mail->isHTML(TRUE);
         $mail->CharSet = 'UTF-8';

         $contenido = '<html>';
         $contenido .= "<p><strong>Hola " . $this->nombre . ' ' . $this->a_paterno . ' ' . $this->a_materno . "</strong>,</p>";
         $contenido .= "<p>Hemos recibido tu solicitud de cita de entrevista. A continuación, te proporcionamos los detalles:</p>";
         $contenido .= "<p>Fecha y Hora: " . $this->fecha_hora . "</p>";
         $contenido .= "<p>Área: " . $this->area . "</p>";
         $contenido .= "<p>Por favor espera la confirmación del encargado del área.</p>";
         $contenido .= "<p>Si tienes alguna pregunta, no dudes en contactarnos.</p>";
         $contenido .= "<p>Gracias,</p>";
         $contenido .= "<p>TeleUrban</p>";
         $contenido .= '</html>';
         $mail->Body = $contenido;

         // Enviar el mail
         $mail->send();
    }
}

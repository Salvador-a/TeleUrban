<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class EmailCita {
    public $email;
    public $nombre;

    public function __construct($email, $nombre) {
        $this->email = $email;
        $this->nombre = $nombre;
    }

    public function enviarConfirmacionEntrevista($entrevista) {
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $_ENV['EMAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV['EMAIL_PORT'];
        $mail->Username = $_ENV['EMAIL_USER'];
        $mail->Password = $_ENV['EMAIL_PASS'];

        $mail->setFrom('citas@TeleUrban.com', 'TeleUrban');
        $mail->addAddress($this->email, $this->nombre);
        $mail->Subject = 'Confirmación de Entrevista';

        $mail->isHTML(TRUE);
        $mail->CharSet = 'UTF-8';

        $contenido = '<html>';
        $contenido .= "<p><strong>Hola " . $this->nombre .  "</strong> Has agendado una entrevista en TeleUrban.</p>";
        $contenido .= "<p>Detalles de la entrevista:</p>";
        $contenido .= "<ul>";
        $contenido .= "<li><strong>Fecha y Hora:</strong> " . $entrevista->fecha_hora . "</li>";
        $contenido .= "<li><strong>Área:</strong> " . $entrevista->area_id . "</li>";
        $contenido .= "<li><strong>Modalidad:</strong> " . $entrevista->modalidad_id . "</li>";
        $contenido .= "</ul>";
        $contenido .= "<p>Tu token para editar la cita es: <strong>" . $entrevista->token . "</strong></p>";
        $contenido .= "<p>Este token es válido por 24 horas.</p>";
        $contenido .= "<p>Nos pondremos en contacto contigo para más detalles.</p>";
        $contenido .= '</html>';
        $mail->Body = $contenido;

        $mail->send();
    }
}


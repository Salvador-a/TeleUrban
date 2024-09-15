<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;
use Model\Departamento;
use Model\Descripcion;

class EmailModificarCita {
    public $email;
    public $nombre;
    public $entrevista;

    public function __construct($email, $nombre, $entrevista) {
        $this->email = $email;
        $this->nombre = $nombre;
        $this->entrevista = $entrevista;
    }

    public function enviarConfirmacionEdicion() {
        // Obtener los nombres correspondientes a los IDs
        $departamento = Departamento::find($this->entrevista->departamento_id);
        $modalidad = Descripcion::find($this->entrevista->modalidad_id);

        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $_ENV['EMAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV['EMAIL_PORT'];
        $mail->Username = $_ENV['EMAIL_USER'];
        $mail->Password = $_ENV['EMAIL_PASS'];

        $mail->setFrom('citas@TeleUrban.com', 'TeleUrban');
        $mail->addAddress($this->email, $this->nombre);
        $mail->Subject = 'Confirmación de Edición de Cita';

        $mail->isHTML(TRUE);
        $mail->CharSet = 'UTF-8';

        $contenido = '<!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background-color: #f4f4f4;
                    margin: 0;
                    padding: 0;
                }
                .container {
                    width: 80%;
                    margin: 0 auto;
                    background-color: #ffffff;
                    padding: 20px;
                    border-radius: 10px;
                    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                }
                .header {
                    background-color: #84c441;
                    color: white;
                    padding: 10px;
                    border-radius: 10px 10px 0 0;
                    text-align: center;
                }
                .content {
                    padding: 20px;
                }
                .content h2 {
                    color: #333333;
                }
                .content p {
                    color: #666666;
                }
                .details {
                    list-style-type: none;
                    padding: 0;
                }
                .details li {
                    margin-bottom: 10px;
                }
                .details li strong {
                    color: #333333;
                }
                .footer {
                    background-color: #84c441;
                    color: white;
                    padding: 10px;
                    border-radius: 0 0 10px 10px;
                    text-align: center;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>TeleUrban</h1>
                </div>
                <div class="content">
                    <h2>Confirmación de Edición de Cita</h2>
                    <p>Hola ' . $this->nombre . ',</p>
                    <p>Has editado una cita en TeleUrban.</p>
                    <p><strong>Detalles de la cita editada:</strong></p>
                    <ul class="details">
                        <li><strong>ID de la cita:</strong> ' . $this->entrevista->id . '</li>
                        <li><strong>Fecha y Hora:</strong> ' . $this->entrevista->fecha_hora . '</li>
                        <li><strong>Área:</strong> ' . $departamento->nombre_departamento . '</li>
                        <li><strong>Modalidad:</strong> ' . $modalidad->nombre . '</li>
                        <li><strong>Habilidades:</strong> ' . $this->entrevista->habilidades . '</li>
                    </ul>
                    <p>Nos pondremos en contacto contigo para más detalles.</p>
                </div>
                <div class="footer">
                    <p>&copy; 2024 TeleUrban. Todos los derechos reservados.</p>
                </div>
            </div>
        </body>
        </html>';

        $mail->Body = $contenido;

        if(!$mail->send()) {
            echo 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo;
        } else {
            echo 'Message has been sent';
        }
    }

    public function enviarNotificacionJefeDepartamento($email, $nombre) {
        $departamento = Departamento::find($this->entrevista->departamento_id);
        $modalidad = Descripcion::find($this->entrevista->modalidad_id);

        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $_ENV['EMAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV['EMAIL_PORT'];
        $mail->Username = $_ENV['EMAIL_USER'];
        $mail->Password = $_ENV['EMAIL_PASS'];

        $mail->setFrom('citas@TeleUrban.com', 'TeleUrban');
        $mail->addAddress($email, $nombre);
        $mail->Subject = 'Notificación de Edición de Cita';

        $mail->isHTML(TRUE);
        $mail->CharSet = 'UTF-8';

        $contenido = '<!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background-color: #f4f4f4;
                    margin: 0;
                    padding: 0;
                }
                .container {
                    width: 80%;
                    margin: 0 auto;
                    background-color: #ffffff;
                    padding: 20px;
                    border-radius: 10px;
                    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                }
                .header {
                    background-color: #84c441;
                    color: white;
                    padding: 10px;
                    border-radius: 10px 10px 0 0;
                    text-align: center;
                }
                .content {
                    padding: 20px;
                }
                .content h2 {
                    color: #333333;
                }
                .content p {
                    color: #666666;
                }
                .details {
                    list-style-type: none;
                    padding: 0;
                }
                .details li {
                    margin-bottom: 10px;
                }
                .details li strong {
                    color: #333333;
                }
                .footer {
                    background-color: #84c441;
                    color: white;
                    padding: 10px;
                    border-radius: 0 0 10px 10px;
                    text-align: center;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>TeleUrban</h1>
                </div>
                <div class="content">
                    <h2>Notificación de Edición de Cita</h2>
                    <p>Hola ' . $nombre . ',</p>
                    <p>El postulante ' . $this->entrevista->nombre . ' ha editado una cita en TeleUrban.</p>
                    <p><strong>Detalles de la cita editada:</strong></p>
                    <ul class="details">
                        <li><strong>ID de la cita:</strong> ' . $this->entrevista->id . '</li>
                        <li><strong>Fecha y Hora:</strong> ' . $this->entrevista->fecha_hora . '</li>
                        <li><strong>Área:</strong> ' . $departamento->nombre_departamento . '</li>
                        <li><strong>Modalidad:</strong> ' . $modalidad->nombre . '</li>
                        <li><strong>Habilidades:</strong> ' . $this->entrevista->habilidades . '</li>
                    </ul>
                    <p>Por favor, revisa el sistema para más detalles.</p>
                </div>
                <div class="footer">
                    <p>&copy; 2024 TeleUrban. Todos los derechos reservados.</p>
                </div>
            </div>
        </body>
        </html>';

        $mail->Body = $contenido;

        if(!$mail->send()) {
            echo 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo;
        } else {
            echo 'Message has been sent';
        }
    }

    
}

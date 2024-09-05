<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;
use Model\Departamento;
use Model\Descripcion;

class EmailCita {
    public $email;
    public $nombre;

    public function __construct($email, $nombre) {
        $this->email = $email;
        $this->nombre = $nombre;
    }

    /**
     * Envía un correo de confirmación de entrevista al usuario.
     * 
     * @param Entrevista $entrevista
     */
    public function enviarConfirmacionEntrevista($entrevista) {
        $departamento = Departamento::find($entrevista->departamento_id); // Obtiene el departamento de la entrevista
        $modalidad = Descripcion::find($entrevista->modalidad_id); // Obtiene la modalidad de la entrevista

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
                    <h2>Confirmación de Entrevista</h2>
                    <p>Hola ' . $this->nombre . ',</p>
                    <p>Has agendado una entrevista en TeleUrban.</p>
                    <p><strong>Detalles de la entrevista:</strong></p>
                    <ul class="details">
                        <li><strong>Fecha y Hora:</strong> ' . $entrevista->fecha_hora . '</li>
                        <li><strong>Área:</strong> ' . $departamento->nombre_departamento . '</li>
                        <li><strong>Modalidad:</strong> ' . $modalidad->nombre . '</li>
                        <li><strong>Habilidades:</strong> ' . $entrevista->habilidades . '</li>
                    </ul>
                    <p>Tu token para editar la cita es: <strong>' . $entrevista->token . '</strong></p>
                    <p>Este token solo se puede usar un maximo de 2 veces.</p>
                    <p>Puedes editar tu cita usando el siguiente enlace: <a href="https://tusitio.com/login-cita">Editar Cita</a></p>
                    <p>Nos pondremos en contacto contigo para más detalles.</p>
                </div>
                <div class="footer">
                    <p>&copy; 2024 TeleUrban. Todos los derechos reservados.</p>
                </div>
            </div>
        </body>
        </html>';

        $mail->Body = $contenido;
        $mail->send();
    }

    /**
     * Envía una notificación al jefe del departamento cuando se agenda una nueva cita.
     * 
     * @param string $email
     * @param string $nombre
     * @param Entrevista $entrevista
     */
    public function enviarNotificacionJefeDepartamento($email, $nombre, $entrevista) {
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $_ENV['EMAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV['EMAIL_PORT'];
        $mail->Username = $_ENV['EMAIL_USER'];
        $mail->Password = $_ENV['EMAIL_PASS'];

        $mail->setFrom('citas@TeleUrban.com', 'TeleUrban');
        $mail->addAddress($email, $nombre);
        $mail->Subject = 'Nueva Cita Agendada en tu Departamento';

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
                    <h2>Nueva Cita Agendada</h2>
                    <p>Hola ' . $nombre . ',</p>
                    <p>Se ha agendado una nueva cita en tu departamento.</p>
                    <p><strong>Detalles de la cita:</strong></p>
                    <ul class="details">
                        <li><strong>Nombre del Postulante:</strong> ' . $entrevista->nombre . ' ' . $entrevista->a_paterno . ' ' . $entrevista->a_materno . '</li>
                        <li><strong>Fecha y Hora:</strong> ' . $entrevista->fecha_hora . '</li>
                        <li><strong>Email del Postulante:</strong> ' . $entrevista->email . '</li>
                        <li><strong>Teléfono del Postulante:</strong> ' . $entrevista->telefono . '</li>
                        <li><strong>Habilidades:</strong> ' . $entrevista->habilidades . '</li>
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
        $mail->send();
    }
}

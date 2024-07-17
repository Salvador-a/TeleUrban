<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class EmailModificarCita {
    public $email;
    public $nombre;
    public $id;

    public function __construct($email, $nombre, $id) {
        $this->email = $email;
        $this->nombre = $nombre;
        $this->id = $id;
    }

    public function enviarConfirmacionEdicion() {
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
                    <ul>
                        <li><strong>ID de la cita:</strong> ' . $this->id . '</li>
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
}

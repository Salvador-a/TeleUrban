<?php

namespace Controllers;

use MVC\Router;
use Model\Entrevista;
use Model\Discapacidad;
use Model\Genero;
use Model\Semestre;
use Model\Universidad;
use Model\Area;
use Model\Descripcion;
use Classes\EmailCita;
use DateTime;

class CitasController {
    public static function crear(Router $router) {
        $entrevista = new Entrevista;
        $alertas = [];

        $discapacidades = Discapacidad::all();
        $generos = Genero::all();
        $semestres = Semestre::all();
        $universidades = Universidad::all();
        $areas = Area::all();
        $modalidades = Descripcion::all();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $entrevista->sincronizar($_POST);

            // Validar que no haya una cita con el mismo correo
            $existe = Entrevista::where('email', $entrevista->email);
            if ($existe) {
                header('Content-Type: application/json');
                echo json_encode([
                    'error' => true,
                    'mensaje' => 'El correo ya tiene una cita pendiente',
                    'redirect' => '/citas'
                ]);
                return;
            }

            // Validaciones
            $alertas = $entrevista->validar();

            // Validar el archivo PDF
            if (isset($_FILES['curriculum']) && $_FILES['curriculum']['error'] === UPLOAD_ERR_OK) {
                $fileType = mime_content_type($_FILES['curriculum']['tmp_name']);
                if ($fileType !== 'application/pdf') {
                    $alertas['error'][] = 'Solo se permiten archivos PDF.';
                }
            } else {
                $alertas['error'][] = 'El archivo del curriculum es obligatorio.';
            }

            if (empty($alertas)) {
                if (isset($_FILES['curriculum']) && $_FILES['curriculum']['error'] === UPLOAD_ERR_OK) {
                    $carpeta_cv = '../public/cv';

                    if (!is_dir($carpeta_cv)) {
                        mkdir($carpeta_cv, 0755, true);
                    }

                    $nombre_archivo = md5(uniqid(rand(), true)) . '.pdf';
                    move_uploaded_file($_FILES['curriculum']['tmp_name'], $carpeta_cv . '/' . $nombre_archivo);
                    $entrevista->curriculum = $nombre_archivo;
                }

                // Generar token con la estructura personalizada
                $prefijo = 'CITA';
                $identificador = $entrevista->id; // O algún identificador único de usuario o entrevista
                $fechaHoraCreacion = date('Ymd\THis');
                $codigoAleatorio = bin2hex(random_bytes(6)); // Código aleatorio seguro de 12 caracteres
                $entrevista->token = "$prefijo-$identificador-$fechaHoraCreacion-$codigoAleatorio";
                
                // Verificar longitud del token
                if (strlen($entrevista->token) > 64) {
                    $entrevista->token = substr($entrevista->token, 0, 64);
                }
                
                $fechaExpiracion = new DateTime();
                $fechaExpiracion->modify('+1 day');
                $entrevista->token_expiracion = $fechaExpiracion->format('Y-m-d H:i:s');

                // Guardar la nueva entrevista en la base de datos
                $resultado = $entrevista->guardar();
                if ($resultado) {
                    $email = new EmailCita($entrevista->email, $entrevista->nombre);
                    $email->enviarConfirmacionEntrevista($entrevista);

                    header('Content-Type: application/json');
                    echo json_encode([
                        'error' => false,
                        'mensaje' => 'Registro exitoso',
                        'redirect' => '/citas'
                    ]);
                    return;
                }
            } else {
                header('Content-Type: application/json');
                echo json_encode([
                    'error' => true,
                    'alertas' => $alertas
                ]);
                return;
            }
        }

        // Eliminar tokens expirados al cargar la página
        Entrevista::eliminarTokensExpirados();

        $router->render('paginas/citas', [
            'titulo' => 'Nueva Cita de Entrevista',
            'entrevista' => $entrevista,
            'alertas' => $alertas,
            'discapacidades' => $discapacidades,
            'generos' => $generos,
            'semestres' => $semestres,
            'universidades' => $universidades,
            'areas' => $areas,
            'modalidades' => $modalidades
        ]);
    }

    public static function editar(Router $router) {
        $token = $_GET['token'] ?? null;

        if (!$token) {
            header('Location: /error');
            return;
        }

        $entrevista = Entrevista::where('token', $token);

        if (!$entrevista || new DateTime() > new DateTime($entrevista->token_expiracion)) {
            header('Location: /token-expirado');
            return;
        }

        $router->render('paginas/editar-cita', [
            'entrevista' => $entrevista
        ]);
    }

    public static function guardarEdicion(Router $router) {
        $token = $_POST['token'] ?? null;

        if (!$token) {
            header('Location: /error');
            return;
        }

        $entrevista = Entrevista::where('token', $token);

        if (!$entrevista || new DateTime() > new DateTime($entrevista->token_expiracion)) {
            header('Location: /token-expirado');
            return;
        }

        $entrevista->sincronizar($_POST);
        $resultado = $entrevista->guardar();

        if ($resultado) {
            header('Location: /exito');
        } else {
            header('Location: /error');
        }
    }
}

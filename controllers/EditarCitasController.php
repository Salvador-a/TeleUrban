<?php

namespace Controllers;

use DateTime;
use MVC\Router;
use Model\Genero;
use Model\Semestre;
use Model\Entrevista;
use Model\Descripcion;
use Model\Universidad;
use Model\Departamento;
use Model\Discapacidad;
use Classes\EmailModificarCita;

class EditarCitasController {

    public static function login(Router $router) {
        session_start();
        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['token'] ?? null;

            if (!$token) {
                Entrevista::setAlerta('error', 'El token es obligatorio');
            } else {
                $entrevista = Entrevista::where('token', $token);
                $entrevista = is_array($entrevista) ? array_shift($entrevista) : $entrevista;

                if ($entrevista) {
                    $_SESSION['token'] = $token;
                    header('Location: /modificar-cita?id=' . $entrevista->id);
                    return;
                } else {
                    Entrevista::setAlerta('error', 'Token inválido');
                }
            }

            $alertas = Entrevista::getAlertas();
        }

        $router->render('paginas/citas/login', [
            'titulo' => 'Acceder para Editar Cita',
            'alertas' => $alertas,
        ]);
    }

    public static function editar(Router $router) {
        session_start();
        $alertas = [];
        $id = $_GET['id'];
        $id = filter_var($id, FILTER_VALIDATE_INT);

        if (!$id) {
            header('Location: /login-cita');
            return;
        }

        $entrevista = Entrevista::find($id);
        if (!$entrevista) {
            header('Location: /login-cita');
            return;
        }

        $discapacidades = Discapacidad::all();
        $generos = Genero::all();
        $semestres = Semestre::all();
        $universidades = Universidad::all();
        $departamentos = Departamento::where('disponible', '1');
        $modalidades = Descripcion::all();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $entrevista->sincronizar($_POST);

            // Formatea la fecha y hora
            $fecha_hora = DateTime::createFromFormat('Y-m-d h:i A', $_POST['fecha_hora']);
            if ($fecha_hora) {
                $entrevista->fecha_hora = $fecha_hora->format('Y-m-d H:i:s');
            }

            // Maneja el archivo del curriculum si se subió uno nuevo
            if (isset($_FILES['curriculum']) && $_FILES['curriculum']['error'] === UPLOAD_ERR_OK) {
                $carpeta_cv = '../public/cv';
                if (!is_dir($carpeta_cv)) {
                    mkdir($carpeta_cv, 0755, true);
                }
                $nombre_archivo = md5(uniqid(rand(), true)) . '.pdf';
                move_uploaded_file($_FILES['curriculum']['tmp_name'], $carpeta_cv . '/' . $nombre_archivo);
                $entrevista->curriculum = $nombre_archivo;
            }

            $alertas = $entrevista->validar();

            if (empty($alertas)) {
                $resultado = $entrevista->guardar();
                if ($resultado) {
                    // Enviar el correo de confirmación de edición
                    $email = new EmailModificarCita($entrevista->email, $entrevista->nombre, $entrevista);
                    $email->enviarConfirmacionEdicion();

                    $_SESSION['token'] = null;
                    header('Location: /cita-exito');
                    return;
                }
            }

            $alertas = Entrevista::getAlertas();
        }

        $router->render('paginas/citas/editar', [
            'titulo' => 'Editar Cita',
            'entrevista' => $entrevista,
            'alertas' => $alertas,
            'discapacidades' => $discapacidades,
            'generos' => $generos,
            'semestres' => $semestres,
            'universidades' => $universidades,
            'departamentos' => $departamentos,
            'modalidades' => $modalidades
        ]);
    }

    public static function exito(Router $router) {
        $router->render('paginas/citas/exito', [
            'titulo' => 'Cita Actualizada'
        ]);
    }
}

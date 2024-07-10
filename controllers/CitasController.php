<?php

namespace Controllers;

use DateTime;
use MVC\Router;
use Model\Genero;
use Model\Semestre;
use Model\Entrevista;
use Classes\EmailCita;
use Model\Descripcion;
use Model\Universidad;
use Model\Departamento;
use Model\Discapacidad;

class CitasController {
    public static function crear(Router $router) {
        $entrevista = new Entrevista;
        $alertas = [];

        $discapacidades = Discapacidad::all();
        $generos = Genero::all();
        $semestres = Semestre::all();
        $universidades = Universidad::all();
        $departamentos = Departamento::where('disponible', '1');
        $modalidades = Descripcion::all();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($_POST['confirmado'] === 'false') {
                $entrevista->sincronizar($_POST);

                $fecha_hora = DateTime::createFromFormat('Y-m-d h:i A', $_POST['fecha_hora']);
                if ($fecha_hora) {
                    $entrevista->fecha_hora = $fecha_hora->format('Y-m-d H:i:s');
                } else {
                    header('Content-Type: application/json');
                    echo json_encode([
                        'error' => true,
                        'mensaje' => 'Formato de fecha y hora inválido',
                    ]);
                    return;
                }

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

                $existeFechaHora = Entrevista::findWhere([
                    'fecha_hora' => $entrevista->fecha_hora,
                    'departamento_id' => $entrevista->departamento_id
                ]);
                if ($existeFechaHora) {
                    header('Content-Type: application/json');
                    echo json_encode([
                        'error' => true,
                        'mensaje' => 'La fecha y hora seleccionadas ya están ocupadas para este departamento.'
                    ]);
                    return;
                }

                $alertas = $entrevista->validar();

                if (isset($_FILES['curriculum']) && $_FILES['curriculum']['error'] === UPLOAD_ERR_OK) {
                    $fileType = mime_content_type($_FILES['curriculum']['tmp_name']);
                    if ($fileType !== 'application/pdf') {
                        $alertas['error'][] = 'Solo se permiten archivos PDF.';
                    } elseif ($_FILES['curriculum']['size'] > 1.5 * 1024 * 1024) {
                        $alertas['error'][] = 'El archivo no debe exceder los 1.5MB.';
                    }
                } else {
                    $alertas['error'][] = 'El archivo del curriculum es obligatorio.';
                }

                if (empty($alertas)) {
                    header('Content-Type: application/json');
                    echo json_encode([
                        'error' => false,
                        'mensaje' => 'Formulario válido',
                    ]);
                    return;
                } else {
                    header('Content-Type: application/json');
                    echo json_encode([
                        'error' => true,
                        'alertas' => $alertas,
                        'form_data' => $_POST
                    ]);
                    return;
                }
            } else {
                $entrevista->sincronizar($_POST);

                $fecha_hora = DateTime::createFromFormat('Y-m-d h:i A', $_POST['fecha_hora']);
                if ($fecha_hora) {
                    $entrevista->fecha_hora = $fecha_hora->format('Y-m-d H:i:s');
                } else {
                    header('Content-Type: application/json');
                    echo json_encode([
                        'error' => true,
                        'mensaje' => 'Formato de fecha y hora inválido',
                    ]);
                    return;
                }

                if (isset($_FILES['curriculum']) && $_FILES['curriculum']['error'] === UPLOAD_ERR_OK) {
                    $carpeta_cv = '../public/cv';

                    if (!is_dir($carpeta_cv)) {
                        mkdir($carpeta_cv, 0755, true);
                    }

                    $nombre_archivo = md5(uniqid(rand(), true)) . '.pdf';
                    move_uploaded_file($_FILES['curriculum']['tmp_name'], $carpeta_cv . '/' . $nombre_archivo);
                    $entrevista->curriculum = $nombre_archivo;
                }

                $entrevista->token = 'CITA-' . $entrevista->id . '-' . date('YmdHis') . '-' . bin2hex(random_bytes(6));
                $fechaExpiracion = new DateTime();
                $fechaExpiracion->modify('+1 day');
                $entrevista->token_expiracion = $fechaExpiracion->format('Y-m-d H:i:s');
                $entrevista->estatus_id = 1;

                $existeFechaHora = Entrevista::findWhere([
                    'fecha_hora' => $entrevista->fecha_hora,
                    'departamento_id' => $entrevista->departamento_id
                ]);
                if ($existeFechaHora) {
                    header('Content-Type: application/json');
                    echo json_encode([
                        'error' => true,
                        'mensaje' => 'La fecha y hora seleccionadas ya están ocupadas para este departamento.'
                    ]);
                    return;
                }

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
                } else {
                    header('Content-Type: application/json');
                    echo json_encode([
                        'error' => true,
                        'mensaje' => 'Error al guardar en la base de datos',
                    ]);
                    return;
                }
            }
        }

        Entrevista::eliminarTokensExpirados();

        $router->render('paginas/citas', [
            'titulo' => 'Nueva Cita de Entrevista',
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

    public static function validarFechaHora() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = json_decode(file_get_contents("php://input"), true);
            $fecha_hora = $data['fecha_hora'] ?? null;
            $departamento_id = $data['departamento_id'] ?? null;

            if ($fecha_hora && $departamento_id) {
                $fecha_hora = DateTime::createFromFormat('Y-m-d h:i A', $fecha_hora);
                if ($fecha_hora) {
                    $fecha_hora = $fecha_hora->format('Y-m-d H:i:s');
                } else {
                    header('Content-Type: application/json');
                    echo json_encode([
                        'error' => true,
                        'mensaje' => 'Formato de fecha y hora inválido',
                    ]);
                    return;
                }

                $existe = Entrevista::findWhere([
                    'fecha_hora' => $fecha_hora,
                    'departamento_id' => $departamento_id
                ]);
                if ($existe) {
                    header('Content-Type: application/json');
                    echo json_encode([
                        'error' => true,
                        'mensaje' => 'La fecha y hora seleccionadas ya están ocupadas para este departamento.'
                    ]);
                    return;
                }
            }

            header('Content-Type: application/json');
            echo json_encode(['error' => false]);
        }
    }

    

    // public static function editar(Router $router) {
        
    //     $alertas = [];
    //     //validar el ID

    //     $ide = $_GRT['id'];
    //     $ide = filter_var($ide, FILTER_VALIDATE_INT);

    //     if(!$ide) {
    //         header('Location: /paginas/citas/editar');
    //     }

    //     // Obtener postulante a editar
    //     $entrevista = Entrevista::find($id);

    //     if(!$ide) {
    //         header('Location: /paginas/citas/editar');
    //     }

               

    //     $router->render('paginas/citas/editar', [
    //         'titulo' => 'Editar Cita de Entrevista',
    //         'entrevista' => $entrevista,
    //         'alertas' => $alertas,
    //         'entrevista' => $entrevista
    //     ]);
    // }

}


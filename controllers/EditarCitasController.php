<?php

namespace Controllers;

use DateTime;
use MVC\Router;
use Model\Genero;
use Model\Semestre;
use Model\Entrevista;
use Classes\EmailModificarCita;
use Model\Descripcion;
use Model\Universidad;
use Model\Departamento;
use Model\Discapacidad;

class EditarCitasController {

    public static function login(Router $router) {
        session_start(); // Asegúrate de que la sesión esté iniciada
        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['token'] ?? null;

            if (!$token) {
                $alertas['error'][] = 'El token es obligatorio';
            } else {
                $entrevista = Entrevista::where('token', $token);
                $entrevista = is_array($entrevista) ? array_shift($entrevista) : $entrevista;

                if ($entrevista) {
                    $_SESSION['token'] = $token; // Guarda el token en la sesión
                    $_SESSION['entrevista'] = $entrevista; // Guarda la información de la entrevista en la sesión
                    header('Location: /modificar-cita?token=' . $token);
                    return;
                } else {
                    $alertas['error'][] = 'Token inválido';
                }
            }
        }

        $router->render('paginas/citas/login', [
            'titulo' => 'Acceder para Editar Cita',
            'alertas' => $alertas,
        ]);
    }

    public static function editar(Router $router) {
        session_start(); // Asegúrate de que la sesión esté iniciada

        // Verifica si hay un token en la sesión
        $token = $_SESSION['token'] ?? '';

        if (!$token) {
            header('Location: /login-cita');
            return;
        }

        // Obtén la entrevista usando el token
        $entrevista = $_SESSION['entrevista'] ?? null;

        if (empty($entrevista)) {
            header('Location: /login-cita');
            return;
        }

        $alertas = []; // Inicializa un array para alertas

        // Obtiene datos necesarios para el formulario de edición de citas
        $discapacidades = Discapacidad::all(); // Obtiene todas las discapacidades
        $generos = Genero::all(); // Obtiene todos los géneros
        $semestres = Semestre::all(); // Obtiene todos los semestres
        $universidades = Universidad::all(); // Obtiene todas las universidades
        $departamentos = Departamento::where('disponible', '1'); // Obtiene todos los departamentos disponibles
        $modalidades = Descripcion::all(); // Obtiene todas las modalidades

        // Verifica si el método de solicitud es POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $entrevista->sincronizar($_POST); // Sincroniza los datos del formulario con la instancia de Entrevista

            $original_email = $_POST['original_email'];
            $original_fecha_hora = $_POST['original_fecha_hora'];

            // Validar el correo solo si ha cambiado
            if ($entrevista->email !== $original_email) {
                // Validar que no exista un correo duplicado
                $existeCorreo = Entrevista::where('email', $entrevista->email);
                if ($existeCorreo) {
                    $alertas['error'][] = 'El correo ya está registrado';
                }
            }

            // Validar la fecha y hora solo si ha cambiado
            if ($entrevista->fecha_hora !== $original_fecha_hora) {
                $fecha_hora = DateTime::createFromFormat('Y-m-d h:i A', $_POST['fecha_hora']);
                if ($fecha_hora) {
                    $entrevista->fecha_hora = $fecha_hora->format('Y-m-d H:i:s'); // Formatea la fecha y hora a 'Y-m-d H:i:s'
                } else {
                    header('Content-Type: application/json');
                    echo json_encode([
                        'error' => true,
                        'mensaje' => 'Formato de fecha y hora inválido', // Mensaje de error si el formato de fecha y hora es inválido
                    ]);
                    return;
                }

                // Verifica la disponibilidad de la fecha y hora
                $existe = Entrevista::findWhere([
                    'fecha_hora' => $entrevista->fecha_hora,
                    'departamento_id' => $entrevista->departamento_id
                ]);
                if ($existe) {
                    $alertas['error'][] = 'La fecha y hora seleccionadas ya están ocupadas para este departamento.';
                }
            }

            // Maneja el archivo del curriculum si se subió uno nuevo
            if (isset($_FILES['curriculum']) && $_FILES['curriculum']['error'] === UPLOAD_ERR_OK) {
                $carpeta_cv = '../public/cv'; // Directorio para guardar los archivos de curriculum

                if (!is_dir($carpeta_cv)) {
                    mkdir($carpeta_cv, 0755, true); // Crea el directorio si no existe
                }

                $nombre_archivo = md5(uniqid(rand(), true)) . '.pdf'; // Genera un nombre único para el archivo
                move_uploaded_file($_FILES['curriculum']['tmp_name'], $carpeta_cv . '/' . $nombre_archivo); // Mueve el archivo al directorio
                $entrevista->curriculum = $nombre_archivo; // Guarda el nombre del archivo en la instancia de Entrevista
            }

            // Valida el formulario
            $alertas = $entrevista->validar();

            // Si no hay alertas, el formulario es válido
            if (empty($alertas)) {
                $resultado = $entrevista->guardar(); // Guarda la entrevista en la base de datos
                if ($resultado) {
                    // Enviar correo de confirmación
                    $email = new EmailModificarCita($entrevista->email, $entrevista->nombre, $entrevista->id);
                    $email->enviarConfirmacionEdicion();

                    $_SESSION['token'] = null;
                    $_SESSION['entrevista'] = null;
                    header('Content-Type: application/json');
                    echo json_encode([
                        'error' => false,
                        'mensaje' => 'Cita actualizada exitosamente',
                        'redirect' => '/login-cita'
                    ]);
                    return;
                } else {
                    $alertas['error'][] = 'Error al guardar en la base de datos';
                }
            } else {
                header('Content-Type: application/json');
                echo json_encode([
                    'error' => true,
                    'alertas' => $alertas, // Muestra las alertas
                    'form_data' => $_POST // Muestra los datos del formulario
                ]);
                return;
            }
        }

        // Renderiza la vista de edición de citas
        $router->render('paginas/citas/editar', [
            'titulo' => 'Editar Cita', // Título de la página
            'entrevista' => $entrevista, // Entrevista para el formulario
            'alertas' => $alertas, // Alertas para mostrar
            'discapacidades' => $discapacidades, // Discapacidades para el formulario
            'generos' => $generos, // Géneros para el formulario
            'semestres' => $semestres, // Semestres para el formulario
            'universidades' => $universidades, // Universidades para el formulario
            'departamentos' => $departamentos, // Departamentos para el formulario
            'modalidades' => $modalidades, // Modalidades para el formulario
            'original_email' => $entrevista->email, // Email original
            'original_fecha_hora' => $entrevista->fecha_hora // Fecha y hora original
        ]);
    }

    // Método para validar la fecha y hora de una cita
    public static function validarFechaHora() {
        // Verifica si el método de solicitud es POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = json_decode(file_get_contents("php://input"), true); // Obtiene los datos del cuerpo de la solicitud
            $fecha_hora = $data['fecha_hora'] ?? null; // Obtiene la fecha y hora
            $departamento_id = $data['departamento_id'] ?? null; // Obtiene el ID del departamento

            // Si se proporcionan fecha_hora y departamento_id
            if ($fecha_hora && $departamento_id) {
                $fecha_hora = DateTime::createFromFormat('Y-m-d h:i A', $fecha_hora); // Formatea la fecha y hora
                if ($fecha_hora) {
                    $fecha_hora = $fecha_hora->format('Y-m-d H:i:s'); // Formatea la fecha y hora a 'Y-m-d H:i:s'
                } else {
                    header('Content-Type: application/json');
                    echo json_encode([
                        'error' => true,
                        'mensaje' => 'Formato de fecha y hora inválido', // Mensaje de error si el formato de fecha y hora es inválido
                    ]);
                    return;
                }

                // Verifica la disponibilidad de la fecha y hora
                $existe = Entrevista::findWhere([
                    'fecha_hora' => $fecha_hora,
                    'departamento_id' => $departamento_id
                ]);
                if ($existe) {
                    header('Content-Type: application/json');
                    echo json_encode([
                        'error' => true,
                        'mensaje' => 'La fecha y hora seleccionadas ya están ocupadas para este departamento.' // Mensaje de error si la fecha y hora ya están ocupadas
                    ]);
                    return;
                }
            }

            header('Content-Type: application/json');
            echo json_encode(['error' => false]); // Respuesta de éxito
        }
    }
}

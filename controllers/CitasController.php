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
use Model\Empleado;

class CitasController {

    /**
     * Maneja la creación de nuevas citas de entrevistas.
     * 
     * @param Router $router
     */
    public static function crear(Router $router) {
        $entrevista = new Entrevista; // Crea una nueva instancia de Entrevista
        $alertas = []; // Inicializa un array para alertas

        // Obtiene datos necesarios para el formulario de creación de citas
        $discapacidades = Discapacidad::all(); // Obtiene todas las discapacidades
        $generos = Genero::all(); // Obtiene todos los géneros
        $semestres = Semestre::all(); // Obtiene todos los semestres
        $universidades = Universidad::all(); // Obtiene todas las universidades
        $departamentos = Departamento::where('disponible', '1'); // Obtiene todos los departamentos disponibles
        $modalidades = Descripcion::all(); // Obtiene todas las modalidades

        // Verifica si el método de solicitud es POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Verifica si el campo 'confirmado' es 'false'
            if ($_POST['confirmado'] === 'false') {
                $entrevista->sincronizar($_POST); // Sincroniza los datos del formulario con la instancia de Entrevista

                // Formatea la fecha y hora
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

                // Verifica si el correo ya tiene una cita pendiente
                $existe = Entrevista::where('email', $entrevista->email);
                if ($existe) {
                    header('Content-Type: application/json');
                    echo json_encode([
                        'error' => true,
                        'mensaje' => 'El correo ya tiene una cita pendiente', // Mensaje de error si el correo ya tiene una cita pendiente
                        'redirect' => '/citas' // Redirecciona a la página de citas
                    ]);
                    return;
                }

                // Verifica la disponibilidad de la fecha y hora
                $existeFechaHora = Entrevista::findWhere([
                    'fecha_hora' => $entrevista->fecha_hora,
                    'departamento_id' => $entrevista->departamento_id
                ]);
                if ($existeFechaHora) {
                    header('Content-Type: application/json');
                    echo json_encode([
                        'error' => true,
                        'mensaje' => 'La fecha y hora seleccionadas ya están ocupadas para este departamento.' // Mensaje de error si la fecha y hora ya están ocupadas
                    ]);
                    return;
                }

                // Valida el formulario
                $alertas = $entrevista->validar();

                // Valida el archivo del curriculum
                if (isset($_FILES['curriculum']) && $_FILES['curriculum']['error'] === UPLOAD_ERR_OK) {
                    $fileType = mime_content_type($_FILES['curriculum']['tmp_name']); // Obtiene el tipo de archivo
                    if ($fileType !== 'application/pdf') {
                        $alertas['error'][] = 'Solo se permiten archivos PDF.'; // Mensaje de error si el archivo no es PDF
                    } elseif ($_FILES['curriculum']['size'] > 1.5 * 1024 * 1024) {
                        $alertas['error'][] = 'El archivo no debe exceder los 1.5MB.'; // Mensaje de error si el archivo excede el tamaño permitido
                    }
                } else {
                    $alertas['error'][] = 'El archivo del curriculum es obligatorio.'; // Mensaje de error si el archivo del curriculum es obligatorio
                }

                // Si no hay alertas, el formulario es válido
                if (empty($alertas)) {
                    header('Content-Type: application/json');
                    echo json_encode([
                        'error' => false,
                        'mensaje' => 'Formulario válido', // Mensaje de éxito si el formulario es válido
                    ]);
                    return;
                } else {
                    header('Content-Type: application/json');
                    echo json_encode([
                        'error' => true,
                        'alertas' => $alertas, // Muestra las alertas
                        'form_data' => $_POST // Muestra los datos del formulario
                    ]);
                    return;
                }
            } else {
                $entrevista->sincronizar($_POST); // Sincroniza los datos del formulario con la instancia de Entrevista

                // Formatea la fecha y hora
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

                // Maneja el archivo del curriculum
                if (isset($_FILES['curriculum']) && $_FILES['curriculum']['error'] === UPLOAD_ERR_OK) {
                    $carpeta_cv = '../public/cv'; // Directorio para guardar los archivos de curriculum

                    if (!is_dir($carpeta_cv)) {
                        mkdir($carpeta_cv, 0755, true); // Crea el directorio si no existe
                    }

                    $nombre_archivo = md5(uniqid(rand(), true)) . '.pdf'; // Genera un nombre único para el archivo
                    move_uploaded_file($_FILES['curriculum']['tmp_name'], $carpeta_cv . '/' . $nombre_archivo); // Mueve el archivo al directorio
                    $entrevista->curriculum = $nombre_archivo; // Guarda el nombre del archivo en la instancia de Entrevista
                }

                // Genera un token para la cita
                $entrevista->token = 'CITA-' . $entrevista->id . '-' . date('YmdHis') . '-' . bin2hex(random_bytes(6));
                $entrevista->usos_token = 0; // Inicializa el contador de usos del token

                // Verifica la disponibilidad de la fecha y hora
                $existeFechaHora = Entrevista::findWhere([
                    'fecha_hora' => $entrevista->fecha_hora,
                    'departamento_id' => $entrevista->departamento_id
                ]);
                if ($existeFechaHora) {
                    header('Content-Type: application/json');
                    echo json_encode([
                        'error' => true,
                        'mensaje' => 'La fecha y hora seleccionadas ya están ocupadas para este departamento.' // Mensaje de error si la fecha y hora ya están ocupadas
                    ]);
                    return;
                }

                // Guarda la entrevista en la base de datos
                $resultado = $entrevista->guardar();
                if ($resultado) {
                    $email = new EmailCita($entrevista->email, $entrevista->nombre); // Crea una nueva instancia de EmailCita
                    $email->enviarConfirmacionEntrevista($entrevista); // Envía el email de confirmación

                    // Obtener datos del jefe de departamento
                    $departamento = Departamento::find($entrevista->departamento_id);
                    $jefeDepartamento = Empleado::find($departamento->id_encargado);

                    // Enviar notificación al jefe de departamento
                    if ($jefeDepartamento) {
                        $email->enviarNotificacionJefeDepartamento($jefeDepartamento->email, $jefeDepartamento->nombre, $entrevista);
                    }

                    header('Content-Type: application/json');
                    echo json_encode([
                        'error' => false,
                        'mensaje' => 'Registro exitoso', // Mensaje de éxito si el registro es exitoso
                        'redirect' => '/citas' // Redirecciona a la página de citas
                    ]);
                    return;
                } else {
                    header('Content-Type: application/json');
                    echo json_encode([
                        'error' => true,
                        'mensaje' => 'Error al guardar en la base de datos' // Mensaje de error si hay un problema al guardar en la base de datos
                    ]);
                    return;
                }
            }
        }

        // Renderiza la vista de creación de citas
        $router->render('paginas/citas', [
            'titulo' => 'Programar Entrevista', // Título de la página
            'entrevista' => $entrevista, // Instancia de Entrevista
            'alertas' => $alertas, // Alertas
            'discapacidades' => $discapacidades, // Discapacidades
            'generos' => $generos, // Géneros
            'semestres' => $semestres, // Semestres
            'universidades' => $universidades, // Universidades
            'departamentos' => $departamentos, // Departamentos
            'modalidades' => $modalidades // Modalidades
        ]);
    }

    /**
     * Valida la disponibilidad de fecha y hora para una cita de entrevista.
     */
    public static function validarFechaHora() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = json_decode(file_get_contents("php://input"), true); // Obtiene los datos de la solicitud
            $fecha_hora = $data['fecha_hora'] ?? null; // Obtiene la fecha y hora
            $departamento_id = $data['departamento_id'] ?? null; // Obtiene el ID del departamento

            // Verifica si la fecha y hora y el ID del departamento están disponibles
            if ($fecha_hora && $departamento_id) {
                $fecha_hora = DateTime::createFromFormat('Y-m-d h:i A', $fecha_hora); // Formatea la fecha y hora
                if ($fecha_hora) {
                    $fecha_hora = $fecha_hora->format('Y-m-d H:i:s'); // Formatea la fecha y hora a 'Y-m-d H:i:s'
                } else {
                    header('Content-Type: application/json');
                    echo json_encode([
                        'error' => true,
                        'mensaje' => 'Formato de fecha y hora inválido' // Mensaje de error si el formato de fecha y hora es inválido
                    ]);
                    return;
                }

                // Verifica si la fecha y hora ya están ocupadas para el departamento
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
            echo json_encode(['error' => false]); // Mensaje de éxito si la fecha y hora están disponibles
        }
    }
}

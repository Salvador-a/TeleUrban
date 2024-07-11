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

class EntrevistaController {
    /**
     * Maneja la vista de entrevistas.
     * 
     * @param Router $router
     */
    public static function index(Router $router) {
        session_start(); // Inicia la sesión

        // Verifica si el usuario está autenticado
        if (!is_auth()) {
            header('Location: /login'); // Redirecciona a la página de login si no está autenticado
            return;
        }

        $entrevistas = [];

        // Verifica el rol del usuario y obtiene las entrevistas en consecuencia
        if (is_admin()) {
            $entrevistas = Entrevista::all(); // Obtiene todas las entrevistas
        } elseif (is_jefe() || is_trabajador()) {
            $user_departamento_id = $_SESSION['departamento_id']; // Obtiene el ID del departamento del usuario
            $entrevistas = Entrevista::where('departamento_id', $user_departamento_id); // Obtiene las entrevistas del departamento del usuario
        } else {
            header('Location: /403'); // Redirecciona a la página 403 si el rol no es válido
            return;
        }

        // Obtiene los nombres correspondientes a los IDs
        foreach ($entrevistas as $entrevista) {
            $entrevista->universidad_nombre = $entrevista->obtenerUniversidad(); // Nombre de la universidad
            $entrevista->semestre_nombre = $entrevista->obtenerSemestre(); // Nombre del semestre
            $entrevista->departamento_nombre = $entrevista->obtenerDepartamento(); // Nombre del departamento
            $entrevista->modalidad_nombre = $entrevista->obtenerModalidad(); // Nombre de la modalidad
            $entrevista->discapacidad_nombre = $entrevista->obtenerDiscapacidad(); // Nombre de la discapacidad
            $entrevista->genero_nombre = $entrevista->obtenerGenero(); // Nombre del género
            $entrevista->estatus_nombre = $entrevista->obtenerEstatus(); // Nombre del estatus
        }

        // Renderiza la vista de entrevistas
        $router->render('admin/registrados/index', [
            'titulo' => 'Citas de Entrevista', // Título de la página
            'entrevistas' => $entrevistas, // Entrevistas para mostrar
            'mostrarAcciones' => true // Muestra acciones en la vista
        ]);
    }

    /**
     * Maneja la creación de una nueva entrevista.
     * 
     * @param Router $router
     */
    public static function crear(Router $router) {
        session_start(); // Inicia la sesión

        // Verifica si el usuario está autenticado y es administrador
        if (!is_auth() || !is_admin()) {
            header('Location: /login'); // Redirecciona a la página de login si no está autenticado
            return;
        }

        $alertas = []; // Inicializa un array para alertas
        $entrevista = new Entrevista; // Crea una nueva instancia de Entrevista

        // Obtiene datos para el formulario
        $discapacidades = Discapacidad::all(); // Obtiene todas las discapacidades
        $generos = Genero::all(); // Obtiene todos los géneros
        $semestres = Semestre::all(); // Obtiene todos los semestres
        $universidades = Universidad::all(); // Obtiene todas las universidades
        $areas = Area::all(); // Obtiene todas las áreas
        $modalidades = Descripcion::all(); // Obtiene todas las modalidades

        // Verifica si el método de solicitud es POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $entrevista->sincronizar($_POST); // Sincroniza los datos del formulario con la instancia de Entrevista
            $entrevista->estatus_id = 1; // Estado predeterminado en "Pendiente"
            $alertas = $entrevista->validar(); // Valida el formulario

            // Si no hay alertas, guarda la entrevista
            if (empty($alertas)) {
                if (isset($_FILES['curriculum']) && $_FILES['curriculum']['error'] === UPLOAD_ERR_OK) {
                    $carpeta_cv = '../public/cv'; // Carpeta para curriculums

                    // Crea la carpeta si no existe
                    if (!is_dir($carpeta_cv)) {
                        mkdir($carpeta_cv, 0755, true);
                    }

                    $nombre_archivo = md5(uniqid(rand(), true)) . '.pdf'; // Genera un nombre único para el archivo
                    move_uploaded_file($_FILES['curriculum']['tmp_name'], $carpeta_cv . '/' . $nombre_archivo); // Mueve el archivo a la carpeta
                    $entrevista->curriculum = $nombre_archivo; // Guarda el nombre del archivo en la instancia de Entrevista
                }

                $resultado = $entrevista->guardar(); // Guarda la entrevista
                if ($resultado) {
                    header('Location: /admin/registrados'); // Redirecciona a la página de entrevistas
                }
            }
        }

        // Renderiza la vista de creación de entrevista
        $router->render('admin/registrados/crear', [
            'titulo' => 'Nueva Cita de Entrevista', // Título de la página
            'entrevista' => $entrevista, // Entrevista para el formulario
            'alertas' => $alertas, // Alertas para mostrar
            'discapacidades' => $discapacidades, // Discapacidades para el formulario
            'generos' => $generos, // Géneros para el formulario
            'semestres' => $semestres, // Semestres para el formulario
            'universidades' => $universidades, // Universidades para el formulario
            'areas' => $areas, // Áreas para el formulario
            'modalidades' => $modalidades // Modalidades para el formulario
        ]);
    }

    /**
     * Maneja la vista de detalles de una entrevista.
     * 
     * @param Router $router
     */
    public static function ver(Router $router) {
        session_start(); // Inicia la sesión

        // Verifica si el usuario está autenticado
        if (!is_auth() || !is_admin() && !is_jefe() && !is_trabajador()) {
            header('Location: /login'); // Redirecciona a la página de login si no está autenticado
            return;
        }

        $id = $_GET['id'];
        $id = filter_var($id, FILTER_VALIDATE_INT); // Valida el ID de la entrevista

        // Si el ID no es válido, redirecciona a la página de entrevistas
        if (!$id) {
            header('Location: /admin/registrados');
            return;
        }

        $entrevista = Entrevista::find($id); // Busca la entrevista por ID

        // Si no encuentra la entrevista, redirecciona a la página de entrevistas
        if (!$entrevista) {
            header('Location: /admin/registrados');
            return;
        }

        // Obtiene los nombres correspondientes a los IDs
        $entrevista->universidad_nombre = $entrevista->obtenerUniversidad(); // Nombre de la universidad
        $entrevista->semestre_nombre = $entrevista->obtenerSemestre(); // Nombre del semestre
        $entrevista->departamento_nombre = $entrevista->obtenerDepartamento(); // Nombre del departamento
        $entrevista->modalidad_nombre = $entrevista->obtenerModalidad(); // Nombre de la modalidad
        $entrevista->discapacidad_nombre = $entrevista->obtenerDiscapacidad(); // Nombre de la discapacidad
        $entrevista->genero_nombre = $entrevista->obtenerGenero(); // Nombre del género

        // Renderiza la vista de detalles de entrevista
        $router->render('admin/registrados/ver', [
            'titulo' => 'Detalles del Postulante', // Título de la página
            'entrevista' => $entrevista // Entrevista para mostrar
        ]);
    }

    /**
     * Maneja la eliminación de una entrevista.
     */
    public static function eliminar() {
        session_start(); // Inicia la sesión

        // Verifica si el usuario está autenticado
        if (!is_auth() || !is_admin() && !is_jefe()) {
            header('Location: /login'); // Redirecciona a la página de login si no está autenticado
            return;
        }

        // Verifica si el método de solicitud es POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $id = filter_var($id, FILTER_VALIDATE_INT); // Valida el ID de la entrevista

            // Si el ID no es válido, redirecciona a la página de entrevistas
            if (!$id) {
                header('Location: /admin/registrados');
                return;
            }

            $entrevista = Entrevista::find($id); // Busca la entrevista por ID

            // Si no encuentra la entrevista, redirecciona a la página de entrevistas
            if (!$entrevista) {
                header('Location: /admin/registrados');
                return;
            }

            $resultado = $entrevista->eliminar(); // Elimina la entrevista

            if ($resultado) {
                header('Location: /admin/registrados'); // Redirecciona a la página de entrevistas
            }
        }
    }

    /**
     * Maneja la aceptación de una entrevista.
     */
    public static function aceptar() {
        session_start(); // Inicia la sesión

        // Verifica si el usuario está autenticado
        if (!is_auth() || !is_admin() && !is_jefe()) {
            header('Location: /login'); // Redirecciona a la página de login si no está autenticado
            return;
        }

        // Verifica si el método de solicitud es POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $id = filter_var($id, FILTER_VALIDATE_INT); // Valida el ID de la entrevista

            // Si el ID no es válido, redirecciona a la página de entrevistas
            if (!$id) {
                header('Location: /admin/registrados');
                return;
            }

            $entrevista = Entrevista::find($id); // Busca la entrevista por ID

            // Si no encuentra la entrevista, redirecciona a la página de entrevistas
            if (!$entrevista) {
                header('Location: /admin/registrados');
                return;
            }

            $entrevista->estatus_id = 2; // Aceptado
            $resultado = $entrevista->guardar(); // Guarda la entrevista

            if ($resultado) {
                // Envía correo de aceptación
                $email = new EmailCita($entrevista->email, $entrevista->nombre);
                $email->enviarConfirmacionAceptacion($entrevista);
            }

            header('Location: /admin/registrados'); // Redirecciona a la página de entrevistas
        }
    }

    /**
     * Maneja el rechazo de una entrevista.
     */
    public static function rechazar() {
        session_start(); // Inicia la sesión

        // Verifica si el usuario está autenticado
        if (!is_auth() || !is_admin() && !is_jefe()) {
            header('Location: /login'); // Redirecciona a la página de login si no está autenticado
            return;
        }

        // Verifica si el método de solicitud es POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $id = filter_var($id, FILTER_VALIDATE_INT); // Valida el ID de la entrevista

            // Si el ID no es válido, redirecciona a la página de entrevistas
            if (!$id) {
                header('Location: /admin/registrados');
                return;
            }

            $entrevista = Entrevista::find($id); // Busca la entrevista por ID

            // Si no encuentra la entrevista, redirecciona a la página de entrevistas
            if (!$entrevista) {
                header('Location: /admin/registrados');
                return;
            }

            $entrevista->estatus_id = 3; // Rechazado
            $resultado = $entrevista->guardar(); // Guarda la entrevista

            if ($resultado) {
                // Envía correo de rechazo
                $email = new EmailCita($entrevista->email, $entrevista->nombre);
                $email->enviarConfirmacionRechazo($entrevista);
            }

            header('Location: /admin/registrados'); // Redirecciona a la página de entrevistas
        }
    }

    /**
     * Muestra el curriculum vitae de una entrevista.
     */
    public static function cv() {
        session_start(); // Inicia la sesión

        // Verifica si el usuario está autenticado
        if (!is_auth() || !is_admin() && !is_jefe() && !is_trabajador()) {
            header('Location: /login'); // Redirecciona a la página de login si no está autenticado
            return;
        }

        $id = $_GET['id'];
        $id = filter_var($id, FILTER_VALIDATE_INT); // Valida el ID de la entrevista

        // Si el ID no es válido, redirecciona a la página de entrevistas
        if (!$id) {
            header('Location: /admin/registrados');
            return;
        }

        $entrevista = Entrevista::find($id); // Busca la entrevista por ID

        // Si no encuentra la entrevista, redirecciona a la página de entrevistas
        if (!$entrevista) {
            header('Location: /admin/registrados');
            return;
        }

        $cvPath = '../public/cv/' . $entrevista->curriculum; // Ruta del curriculum
        if (file_exists($cvPath)) {
            header('Content-Type: application/pdf'); // Establece el tipo de contenido a PDF
            readfile($cvPath); // Lee y muestra el archivo PDF
        } else {
            header('Location: /admin/registrados'); // Redirecciona a la página de entrevistas si el archivo no existe
        }
    }

    /**
     * Maneja la vista de más información de una entrevista.
     * 
     * @param Router $router
     */
    public static function verMasInformacion(Router $router) {
        session_start(); // Inicia la sesión

        // Verifica si el usuario está autenticado
        if (!is_auth() || !is_admin() && !is_jefe() && !is_trabajador()) {
            header('Location: /login'); // Redirecciona a la página de login si no está autenticado
            return;
        }

        $id = $_GET['id'];
        $id = filter_var($id, FILTER_VALIDATE_INT); // Valida el ID de la entrevista

        // Si el ID no es válido, redirecciona a la página de entrevistas
        if (!$id) {
            header('Location: /admin/registrados');
            return;
        }

        $entrevista = Entrevista::find($id); // Busca la entrevista por ID

        // Si no encuentra la entrevista, redirecciona a la página de entrevistas
        if (!$entrevista) {
            header('Location: /admin/registrados');
            return;
        }

        // Obtiene los nombres correspondientes a los IDs
        $entrevista->universidad_nombre = $entrevista->obtenerUniversidad(); // Nombre de la universidad
        $entrevista->semestre_nombre = $entrevista->obtenerSemestre(); // Nombre del semestre
        $entrevista->departamento_nombre = $entrevista->obtenerDepartamento(); // Nombre del departamento
        $entrevista->modalidad_nombre = $entrevista->obtenerModalidad(); // Nombre de la modalidad
        $entrevista->discapacidad_nombre = $entrevista->obtenerDiscapacidad(); // Nombre de la discapacidad
        $entrevista->genero_nombre = $entrevista->obtenerGenero(); // Nombre del género

        // Renderiza la vista de más información de entrevista
        $router->render('admin/registrados/ver', [
            'titulo' => 'Detalles del Postulante', // Título de la página
            'entrevista' => $entrevista // Entrevista para mostrar
        ]);
    }

    /**
     * Maneja la vista de entrevistas aceptadas.
     * 
     * @param Router $router
     */
    public static function aceptados(Router $router) {
        session_start(); // Inicia la sesión

        // Verifica si el usuario está autenticado
        if (!is_auth() || !is_admin() && !is_jefe() && !is_trabajador()) {
            header('Location: /login'); // Redirecciona a la página de login si no está autenticado
            return;
        }

        // Obtiene entrevistas con estatus "Aceptado"
        if (is_admin()) {
            $entrevistas = Entrevista::where('estatus_id', 2); // Obtiene las entrevistas aceptadas
        } elseif (is_jefe() || is_trabajador()) {
            $user_departamento_id = $_SESSION['departamento_id']; // Obtiene el ID del departamento del usuario
            $entrevistas = Entrevista::findWhere(['estatus_id' => 2, 'departamento_id' => $user_departamento_id]); // Obtiene las entrevistas aceptadas del departamento del usuario
        } else {
            header('Location: /403'); // Redirecciona a la página 403 si el rol no es válido
            return;
        }

        // Obtiene los nombres correspondientes a los IDs
        foreach ($entrevistas as $entrevista) {
            $entrevista->universidad_nombre = $entrevista->obtenerUniversidad(); // Nombre de la universidad
            $entrevista->semestre_nombre = $entrevista->obtenerSemestre(); // Nombre del semestre
            $entrevista->departamento_nombre = $entrevista->obtenerDepartamento(); // Nombre del departamento
            $entrevista->modalidad_nombre = $entrevista->obtenerModalidad(); // Nombre de la modalidad
            $entrevista->discapacidad_nombre = $entrevista->obtenerDiscapacidad(); // Nombre de la discapacidad
            $entrevista->genero_nombre = $entrevista->obtenerGenero(); // Nombre del género
            $entrevista->estatus_nombre = $entrevista->obtenerEstatus(); // Nombre del estatus
        }

        // Renderiza la vista de entrevistas aceptadas
        $router->render('admin/registrados/index', [
            'titulo' => 'Entrevistas Aceptadas', // Título de la página
            'entrevistas' => $entrevistas, // Entrevistas para mostrar
            'mostrarAcciones' => false // No muestra acciones en la vista
        ]);
    }

    /**
     * Maneja la vista de entrevistas rechazadas.
     * 
     * @param Router $router
     */
    public static function rechazados(Router $router) {
        session_start(); // Inicia la sesión

        // Verifica si el usuario está autenticado
        if (!is_auth() || !is_admin() && !is_jefe() && !is_trabajador()) {
            header('Location: /login'); // Redirecciona a la página de login si no está autenticado
            return;
        }

        // Obtiene entrevistas con estatus "Rechazado"
        if (is_admin()) {
            $entrevistas = Entrevista::where('estatus_id', 3); // Obtiene las entrevistas rechazadas
        } elseif (is_jefe() || is_trabajador()) {
            $user_departamento_id = $_SESSION['departamento_id']; // Obtiene el ID del departamento del usuario
            $entrevistas = Entrevista::findWhere(['estatus_id' => 3, 'departamento_id' => $user_departamento_id]); // Obtiene las entrevistas rechazadas del departamento del usuario
        } else {
            header('Location: /403'); // Redirecciona a la página 403 si el rol no es válido
            return;
        }

        // Obtiene los nombres correspondientes a los IDs
        foreach ($entrevistas as $entrevista) {
            $entrevista->universidad_nombre = $entrevista->obtenerUniversidad(); // Nombre de la universidad
            $entrevista->semestre_nombre = $entrevista->obtenerSemestre(); // Nombre del semestre
            $entrevista->departamento_nombre = $entrevista->obtenerDepartamento(); // Nombre del departamento
            $entrevista->modalidad_nombre = $entrevista->obtenerModalidad(); // Nombre de la modalidad
            $entrevista->discapacidad_nombre = $entrevista->obtenerDiscapacidad(); // Nombre de la discapacidad
            $entrevista->genero_nombre = $entrevista->obtenerGenero(); // Nombre del género
            $entrevista->estatus_nombre = $entrevista->obtenerEstatus(); // Nombre del estatus
        }

        // Renderiza la vista de entrevistas rechazadas
        $router->render('admin/registrados/index', [
            'titulo' => 'Entrevistas Rechazadas', // Título de la página
            'entrevistas' => $entrevistas, // Entrevistas para mostrar
            'mostrarAcciones' => false // No muestra acciones en la vista
        ]);
    }
}

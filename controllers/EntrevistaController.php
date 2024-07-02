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
    public static function index(Router $router) {
        session_start();

        if (!is_auth()) {
            header('Location: /login');
            return;
        }

        $entrevistas = [];

        if (is_admin()) {
            $entrevistas = Entrevista::all();
        } elseif (is_jefe() || is_trabajador()) {
            $user_departamento_id = $_SESSION['departamento_id'];
            $entrevistas = Entrevista::where('departamento_id', $user_departamento_id);
        } else {
            header('Location: /403');
            return;
        }

        foreach ($entrevistas as $entrevista) {
            $entrevista->universidad_nombre = $entrevista->obtenerUniversidad();
            $entrevista->semestre_nombre = $entrevista->obtenerSemestre();
            $entrevista->departamento_nombre = $entrevista->obtenerDepartamento();
            $entrevista->modalidad_nombre = $entrevista->obtenerModalidad();
            $entrevista->discapacidad_nombre = $entrevista->obtenerDiscapacidad();
            $entrevista->genero_nombre = $entrevista->obtenerGenero();
            $entrevista->estatus_nombre = $entrevista->obtenerEstatus();
        }

        $router->render('admin/registrados/index', [
            'titulo' => 'Citas de Entrevista',
            'entrevistas' => $entrevistas,
            'mostrarAcciones' => true
        ]);
    }

    public static function crear(Router $router) {
        session_start();

        if (!is_auth() || !is_admin()) {
            header('Location: /login');
            return;
        }

        $alertas = [];
        $entrevista = new Entrevista;

        $discapacidades = Discapacidad::all();
        $generos = Genero::all();
        $semestres = Semestre::all();
        $universidades = Universidad::all();
        $areas = Area::all();
        $modalidades = Descripcion::all();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $entrevista->sincronizar($_POST);
            $entrevista->estatus_id = 1; // Estado predeterminado en "Pendiente"
            $alertas = $entrevista->validar();

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

                $resultado = $entrevista->guardar();
                if ($resultado) {
                    header('Location: /admin/registrados');
                }
            }
        }

        $router->render('admin/registrados/crear', [
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

    public static function ver(Router $router) {
        session_start();

        if (!is_auth() || !is_admin() && !is_jefe() && !is_trabajador()) {
            header('Location: /login');
            return;
        }

        $id = $_GET['id'];
        $id = filter_var($id, FILTER_VALIDATE_INT);

        if (!$id) {
            header('Location: /admin/registrados');
            return;
        }

        $entrevista = Entrevista::find($id);

        if (!$entrevista) {
            header('Location: /admin/registrados');
            return;
        }

        // Obtener nombres correspondientes a los IDs
        $entrevista->universidad_nombre = $entrevista->obtenerUniversidad();
        $entrevista->semestre_nombre = $entrevista->obtenerSemestre();
        $entrevista->departamento_nombre = $entrevista->obtenerDepartamento();
        $entrevista->modalidad_nombre = $entrevista->obtenerModalidad();
        $entrevista->discapacidad_nombre = $entrevista->obtenerDiscapacidad();
        $entrevista->genero_nombre = $entrevista->obtenerGenero();

        $router->render('admin/registrados/ver', [
            'titulo' => 'Detalles del Postulante',
            'entrevista' => $entrevista
        ]);
    }

    public static function eliminar() {
        session_start();

        if (!is_auth() || !is_admin() && !is_jefe()) {
            header('Location: /login');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $id = filter_var($id, FILTER_VALIDATE_INT);

            if (!$id) {
                header('Location: /admin/registrados');
                return;
            }

            $entrevista = Entrevista::find($id);

            if (!$entrevista) {
                header('Location: /admin/registrados');
                return;
            }

            $resultado = $entrevista->eliminar();

            if ($resultado) {
                header('Location: /admin/registrados');
            }
        }
    }

    public static function aceptar() {
        session_start();

        if (!is_auth() || !is_admin() && !is_jefe()) {
            header('Location: /login');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $id = filter_var($id, FILTER_VALIDATE_INT);

            if (!$id) {
                header('Location: /admin/registrados');
                return;
            }

            $entrevista = Entrevista::find($id);

            if (!$entrevista) {
                header('Location: /admin/registrados');
                return;
            }

            $entrevista->estatus_id = 2; // Aceptado
            $resultado = $entrevista->guardar();

            if ($resultado) {
                // Enviar correo de aceptación
                $email = new EmailCita($entrevista->email, $entrevista->nombre);
                $email->enviarConfirmacionAceptacion($entrevista);
            }

            header('Location: /admin/registrados');
        }
    }

    public static function rechazar() {
        session_start();

        if (!is_auth() || !is_admin() && !is_jefe()) {
            header('Location: /login');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $id = filter_var($id, FILTER_VALIDATE_INT);

            if (!$id) {
                header('Location: /admin/registrados');
                return;
            }

            $entrevista = Entrevista::find($id);

            if (!$entrevista) {
                header('Location: /admin/registrados');
                return;
            }

            $entrevista->estatus_id = 3; // Rechazado
            $resultado = $entrevista->guardar();

            if ($resultado) {
                // Enviar correo de rechazo
                $email = new EmailCita($entrevista->email, $entrevista->nombre);
                $email->enviarConfirmacionRechazo($entrevista);
            }

            header('Location: /admin/registrados');
        }
    }

    public static function cv() {
        session_start();

        if (!is_auth() || !is_admin() && !is_jefe() && !is_trabajador()) {
            header('Location: /login');
            return;
        }

        $id = $_GET['id'];
        $id = filter_var($id, FILTER_VALIDATE_INT);

        if (!$id) {
            header('Location: /admin/registrados');
            return;
        }

        $entrevista = Entrevista::find($id);

        if (!$entrevista) {
            header('Location: /admin/registrados');
            return;
        }

        $cvPath = '../public/cv/' . $entrevista->curriculum;
        if (file_exists($cvPath)) {
            header('Content-Type: application/pdf');
            readfile($cvPath);
        } else {
            header('Location: /admin/registrados');
        }
    }

    public static function verMasInformacion(Router $router) {
        session_start();

        if (!is_auth() || !is_admin() && !is_jefe() && !is_trabajador()) {
            header('Location: /login');
            return;
        }

        $id = $_GET['id'];
        $id = filter_var($id, FILTER_VALIDATE_INT);

        if (!$id) {
            header('Location: /admin/registrados');
            return;
        }

        $entrevista = Entrevista::find($id);

        if (!$entrevista) {
            header('Location: /admin/registrados');
            return;
        }

        // Obtener nombres correspondientes a los IDs
        $entrevista->universidad_nombre = $entrevista->obtenerUniversidad();
        $entrevista->semestre_nombre = $entrevista->obtenerSemestre();
        $entrevista->departamento_nombre = $entrevista->obtenerDepartamento();
        $entrevista->modalidad_nombre = $entrevista->obtenerModalidad();
        $entrevista->discapacidad_nombre = $entrevista->obtenerDiscapacidad();
        $entrevista->genero_nombre = $entrevista->obtenerGenero();

        $router->render('admin/registrados/ver', [
            'titulo' => 'Detalles del Postulante',
            'entrevista' => $entrevista
        ]);
    }

    public static function aceptados(Router $router) {
        session_start();

        if (!is_auth() || !is_admin() && !is_jefe() && !is_trabajador()) {
            header('Location: /login');
            return;
        }

        // Obtener entrevistas con estatus "Aceptado"
        if (is_admin()) {
            $entrevistas = Entrevista::where('estatus_id', 2);
        } elseif (is_jefe() || is_trabajador()) {
            $user_departamento_id = $_SESSION['departamento_id'];
            $entrevistas = Entrevista::findWhere(['estatus_id' => 2, 'departamento_id' => $user_departamento_id]);
        } else {
            header('Location: /403');
            return;
        }

        foreach ($entrevistas as $entrevista) {
            $entrevista->universidad_nombre = $entrevista->obtenerUniversidad();
            $entrevista->semestre_nombre = $entrevista->obtenerSemestre();
            $entrevista->departamento_nombre = $entrevista->obtenerDepartamento();
            $entrevista->modalidad_nombre = $entrevista->obtenerModalidad();
            $entrevista->discapacidad_nombre = $entrevista->obtenerDiscapacidad();
            $entrevista->genero_nombre = $entrevista->obtenerGenero();
            $entrevista->estatus_nombre = $entrevista->obtenerEstatus();
        }

        $router->render('admin/registrados/index', [
            'titulo' => 'Entrevistas Aceptadas',
            'entrevistas' => $entrevistas,
            'mostrarAcciones' => false // No mostrar acciones para esta vista
        ]);
    }

    public static function rechazados(Router $router) {
        session_start();

        if (!is_auth() || !is_admin() && !is_jefe() && !is_trabajador()) {
            header('Location: /login');
            return;
        }

        // Obtener entrevistas con estatus "Rechazado"
        if (is_admin()) {
            $entrevistas = Entrevista::where('estatus_id', 3);
        } elseif (is_jefe() || is_trabajador()) {
            $user_departamento_id = $_SESSION['departamento_id'];
            $entrevistas = Entrevista::findWhere(['estatus_id' => 3, 'departamento_id' => $user_departamento_id]);
        } else {
            header('Location: /403');
            return;
        }

        foreach ($entrevistas as $entrevista) {
            $entrevista->universidad_nombre = $entrevista->obtenerUniversidad();
            $entrevista->semestre_nombre = $entrevista->obtenerSemestre();
            $entrevista->departamento_nombre = $entrevista->obtenerDepartamento();
            $entrevista->modalidad_nombre = $entrevista->obtenerModalidad();
            $entrevista->discapacidad_nombre = $entrevista->obtenerDiscapacidad();
            $entrevista->genero_nombre = $entrevista->obtenerGenero();
            $entrevista->estatus_nombre = $entrevista->obtenerEstatus();
        }

        $router->render('admin/registrados/index', [
            'titulo' => 'Entrevistas Rechazadas',
            'entrevistas' => $entrevistas,
            'mostrarAcciones' => false // No mostrar acciones para esta vista
        ]);
    }
}

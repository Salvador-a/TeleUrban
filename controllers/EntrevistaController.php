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

class EntrevistaController {
    public static function index(Router $router) {
        if (!is_admin()) {
            header('Location: /login');
            return;
        }

        $entrevistas = Entrevista::all();

        foreach ($entrevistas as $entrevista) {
            $entrevista->universidad_nombre = $entrevista->obtenerUniversidad();
            $entrevista->semestre_nombre = $entrevista->obtenerSemestre();
            $entrevista->departamento_nombre = $entrevista->obtenerDepartamento();
            $entrevista->modalidad_nombre = $entrevista->obtenerModalidad();
            $entrevista->discapacidad_nombre = $entrevista->obtenerDiscapacidad();
            $entrevista->genero_nombre = $entrevista->obtenerGenero();
        }

        $router->render('admin/registrados/index', [
            'titulo' => 'Citas de Entrevista',
            'entrevistas' => $entrevistas
        ]);
    }

    public static function crear(Router $router) {
        if (!is_admin()) {
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

    public static function editar(Router $router) {
        if (!is_admin()) {
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

        $alertas = [];

        $discapacidades = Discapacidad::all();
        $generos = Genero::all();
        $semestres = Semestre::all();
        $universidades = Universidad::all();
        $areas = Area::all();
        $modalidades = Descripcion::all();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $entrevista->sincronizar($_POST);
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
                } else {
                    $entrevista->curriculum = $entrevista->curriculum_actual;
                }

                $resultado = $entrevista->guardar();
                if ($resultado) {
                    header('Location: /admin/registrados');
                }
            }
        }

        $router->render('admin/registrados/editar', [
            'titulo' => 'Editar Cita de Entrevista',
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

    public static function eliminar() {
        if (!is_admin()) {
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
}

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
            // Sincronizar con los datos del POST
            $entrevista->sincronizar($_POST);

            // Validar
            $alertas = $entrevista->validar();

            // Manejar la subida del archivo de currículum
            if (empty($alertas)) {
                if (isset($_FILES['curriculum']) && $_FILES['curriculum']['error'] === UPLOAD_ERR_OK) {
                    $carpeta_cv = '../public/cv';

                    // Crear la carpeta si no existe
                    if (!is_dir($carpeta_cv)) {
                        mkdir($carpeta_cv, 0755, true);
                    }

                    // Nombre del archivo
                    $nombre_archivo = md5(uniqid(rand(), true)) . '.pdf';

                    // Mover el archivo a la carpeta
                    move_uploaded_file($_FILES['curriculum']['tmp_name'], $carpeta_cv . '/' . $nombre_archivo);

                    // Asignar el nombre del archivo al modelo
                    $entrevista->curriculum = $nombre_archivo;
                }

                // Guardar en la base de datos
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
            // Sincronizar con los datos del POST
            $entrevista->sincronizar($_POST);

            // Validar
            $alertas = $entrevista->validar();

            // Manejar la subida del archivo de currículum
            if (empty($alertas)) {
                if (isset($_FILES['curriculum']) && $_FILES['curriculum']['error'] === UPLOAD_ERR_OK) {
                    $carpeta_cv = '../public/cv';

                    // Crear la carpeta si no existe
                    if (!is_dir($carpeta_cv)) {
                        mkdir($carpeta_cv, 0755, true);
                    }

                    // Nombre del archivo
                    $nombre_archivo = md5(uniqid(rand(), true)) . '.pdf';

                    // Mover el archivo a la carpeta
                    move_uploaded_file($_FILES['curriculum']['tmp_name'], $carpeta_cv . '/' . $nombre_archivo);

                    // Asignar el nombre del archivo al modelo
                    $entrevista->curriculum = $nombre_archivo;
                } else {
                    $entrevista->curriculum = $entrevista->curriculum_actual;
                }

                // Guardar en la base de datos
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

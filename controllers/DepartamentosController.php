<?php

namespace Controllers;

use MVC\Router;
use Model\Departamento;
use Model\Empleado;
use Intervention\Image\ImageManagerStatic as Image;
use Classes\Paginacion;

class DepartamentosController {
    public static function index(Router $router) {
        session_start();

        if (!is_auth()) {
            header('Location: /login');
            return;
        }

        $pagina_actual = $_GET['page'] ?? 1;
        $pagina_actual = filter_var($pagina_actual, FILTER_VALIDATE_INT);

        if (!$pagina_actual || $pagina_actual < 1) {
            header('Location: /admin/departamentos?page=1');
            return;
        }

        $registros_por_pagina = 4;
        $total = Departamento::total();
        $paginacion = new Paginacion($pagina_actual, $registros_por_pagina, $total);

        if ($paginacion->total_paginas() < $pagina_actual) {
            header('Location: /admin/departamentos?page=1');
            return;
        }

        if (is_admin()) {
            $departamentos = Departamento::paginar($registros_por_pagina, $paginacion->offset());
        } elseif (is_jefe() || is_trabajador()) {
            $user_departamento_id = $_SESSION['departamento_id'];
            $departamentos = Departamento::where('id', $user_departamento_id);
        } else {
            header('Location: /403');
            return;
        }

        foreach ($departamentos as $departamento) {
            $encargado = Empleado::find($departamento->id_encargado);
            $departamento->encargado_nombre = $encargado->nombre ?? '';
            $departamento->encargado_apellido = $encargado->apellido ?? '';
            $departamento->encargado_imagen = $encargado->imagen ?? '';
            $departamento->encargado_puesto = $encargado->puesto_trabajo ?? '';
        }

        $router->render('admin/departamentos/index', [
            'titulo' => 'Departamentos',
            'departamentos' => $departamentos,
            'paginacion' => $paginacion->paginacion()
        ]);
    }

    public static function crear(Router $router) {
        session_start();

        if (!is_auth() || !is_admin()) {
            header('Location: /login');
            return;
        }

        $alertas = [];
        $empleados = Empleado::all();
        $departamento = new Departamento;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $departamento->sincronizar($_POST);

            $alertas = $departamento->validar();

            if (empty($alertas)) {
                if (!empty($_FILES['imagen']['tmp_name'])) {
                    $nombre_imagen = md5(uniqid(rand(), true)) . ".jpg";
                    $image = Image::make($_FILES['imagen']['tmp_name'])->fit(800, 600);
                    $departamento->setImagen($nombre_imagen);
                }

                $resultado = $departamento->guardar();

                if ($resultado) {
                    if (isset($image)) {
                        $image->save(CARPETA_IMAGENES . $nombre_imagen);
                    }
                    header('Location: /admin/departamentos');
                }
            }
        }

        $router->render('admin/departamentos/crear', [
            'titulo' => 'Crear Departamento',
            'alertas' => $alertas,
            'departamento' => $departamento,
            'empleados' => $empleados
        ]);
    }

    public static function actualizar(Router $router) {
        session_start();

        if (!is_auth() || !is_admin()) {
            header('Location: /login');
            return;
        }

        $id = validarORedireccionar('/admin/departamentos');

        $departamento = Departamento::find($id);
        $empleados = Empleado::all();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $departamento->sincronizar($_POST);

            $alertas = $departamento->validar();

            if (empty($alertas)) {
                if (!empty($_FILES['imagen']['tmp_name'])) {
                    $nombre_imagen = md5(uniqid(rand(), true)) . ".jpg";
                    $image = Image::make($_FILES['imagen']['tmp_name'])->fit(800, 600);
                    $departamento->setImagen($nombre_imagen);
                }

                $resultado = $departamento->guardar();

                if ($resultado) {
                    if (isset($image)) {
                        $image->save(CARPETA_IMAGENES . $nombre_imagen);
                    }
                    header('Location: /admin/departamentos');
                }
            }
        }

        $alertas = Departamento::getAlertas();

        $router->render('admin/departamentos/actualizar', [
            'titulo' => 'Actualizar Departamento',
            'alertas' => $alertas,
            'departamento' => $departamento,
            'empleados' => $empleados
        ]);
    }

    public static function eliminar() {
        session_start();

        if (!is_auth() || !is_admin()) {
            header('Location: /login');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $departamento = Departamento::find($id);

            if ($departamento) {
                $resultado = $departamento->eliminar();
                if ($resultado) {
                    header('Location: /admin/departamentos');
                }
            }
        }
    }

    public static function detalle(Router $router) {
        session_start();

        if (!is_auth()) {
            header('Location: /login');
            return;
        }

        $id = $_GET['id'];
        $id = filter_var($id, FILTER_VALIDATE_INT);

        if (!$id) {
            header('Location: /departamentos');
            return;
        }

        $departamento = Departamento::find($id);

        if (!$departamento) {
            header('Location: /departamentos');
            return;
        }

        $departamento->encargado = Empleado::find($departamento->id_encargado);

        $router->render('paginas/detalle-departamento', [
            'titulo' => 'Detalle Departamento',
            'departamento' => $departamento
        ]);
    }
}

?>

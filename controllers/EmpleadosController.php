<?php

namespace Controllers;

use MVC\Router;
use Model\Empleado;
use Model\Departamento;
use Model\PuestoTrabajo;
use Classes\Paginacion;
use Intervention\Image\ImageManagerStatic as Image;

class EmpleadosController {

    public static function index(Router $router) {
        session_start();

        if (!is_auth()) {
            header('Location: /login');
            return;
        }

        if (!is_admin() && !is_jefe() && !is_trabajador()) {
            header('Location: /403');
            return;
        }

        $user_role = '';
        if (is_admin()) {
            $user_role = 'admin';
        } elseif (is_jefe()) {
            $user_role = 'jefe';
        } elseif (is_trabajador()) {
            $user_role = 'trabajador';
        }

        $pagina_actual = $_GET['page'] ?? 1;
        $pagina_actual = filter_var($pagina_actual, FILTER_VALIDATE_INT);

        if (!$pagina_actual || $pagina_actual < 1) {
            header('Location: /admin/empleados?page=1');
            return;
        }

        $registros_por_pagina = 4;
        $total = Empleado::total();
        $paginacion = new Paginacion($pagina_actual, $registros_por_pagina, $total);

        if ($paginacion->total_paginas() < $pagina_actual) {
            header('Location: /admin/empleados?page=1');
            return;
        }

        $empleados = Empleado::paginar($registros_por_pagina, $paginacion->offset());

        foreach ($empleados as $empleado) {
            $empleado->puesto_trabajo_nombre = $empleado->obtenerNombrePuestoTrabajo();
            $empleado->departamento_nombre = $empleado->obtenerNombreDepartamento();
        }

        $router->render('admin/empleados/index', [
            'titulo' => 'Empleados',
            'empleados' => $empleados,
            'paginacion' => $paginacion->paginacion(),
            'user_role' => $user_role
        ]);
    }

    public static function crear(Router $router) {
        session_start();

        if (!is_auth() || !is_admin()) {
            header('Location: /login');
            return;
        }

        $alertas = [];
        $empleado = new Empleado;

        // Obtener departamentos y puestos de trabajo
        $departamentos = Departamento::all();
        $puestos_trabajo = PuestoTrabajo::all();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $empleado->sincronizar($_POST);

            $alertas = $empleado->validar();

            if (empty($alertas)) {
                if (!empty($_FILES['imagen']['tmp_name'])) {
                    $carpeta_imagenes = '../public/img/galeria';

                    if (!is_dir($carpeta_imagenes)) {
                        mkdir($carpeta_imagenes, 0755, true);
                    }

                    $imagen_png = Image::make($_FILES['imagen']['tmp_name'])->fit(800, 800)->encode('png', 80);
                    $imagen_webp = Image::make($_FILES['imagen']['tmp_name'])->fit(800, 800)->encode('webp', 80);

                    $nombre_imagen = md5(uniqid(rand(), true));
                    $imagen_png->save($carpeta_imagenes . '/' . $nombre_imagen . ".png");
                    $imagen_webp->save($carpeta_imagenes . '/' . $nombre_imagen . ".webp");

                    $empleado->imagen = $nombre_imagen;
                }

                $resultado = $empleado->guardar();

                if ($resultado) {
                    header('Location: /admin/empleados');
                }
            }
        }

        $router->render('admin/empleados/crear', [
            'titulo' => 'Registrar Empleado',
            'alertas' => $alertas,
            'empleado' => $empleado,
            'departamentos' => $departamentos,
            'puestos_trabajo' => $puestos_trabajo,
            'redes_sociales' => json_decode($empleado->redes_sociales)
        ]);
    }

    public static function editar(Router $router) {
        session_start();

        if (!is_auth() || !is_admin()) {
            header('Location: /login');
            return;
        }

        $alertas = [];
        $id = $_GET['id'];
        $id = filter_var($id, FILTER_VALIDATE_INT);

        if (!$id) {
            header('Location: /admin/empleados');
            return;
        }

        $empleado = Empleado::find($id);

        if (!$empleado) {
            header('Location: /admin/empleados');
            return;
        }

        $empleado->imagen_actual = $empleado->imagen;

        // Obtener departamentos y puestos de trabajo
        $departamentos = Departamento::all();
        $puestos_trabajo = PuestoTrabajo::all();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $empleado->sincronizar($_POST);

            $alertas = $empleado->validar();

            if (empty($alertas)) {
                if (!empty($_FILES['imagen']['tmp_name'])) {
                    $carpeta_imagenes = '../public/img/galeria';

                    if (!is_dir($carpeta_imagenes)) {
                        mkdir($carpeta_imagenes, 0755, true);
                    }

                    $imagen_png = Image::make($_FILES['imagen']['tmp_name'])->fit(800, 800)->encode('png', 80);
                    $imagen_webp = Image::make($_FILES['imagen']['tmp_name'])->fit(800, 800)->encode('webp', 80);

                    $nombre_imagen = md5(uniqid(rand(), true));
                    $imagen_png->save($carpeta_imagenes . '/' . $nombre_imagen . ".png");
                    $imagen_webp->save($carpeta_imagenes . '/' . $nombre_imagen . ".webp");

                    $empleado->imagen = $nombre_imagen;
                } else {
                    $empleado->imagen = $empleado->imagen_actual;
                }

                $resultado = $empleado->guardar();
                if ($resultado) {
                    header('Location: /admin/empleados');
                }
            }
        }

        $router->render('admin/empleados/editar', [
            'titulo' => 'Actualizar Empleado',
            'alertas' => $alertas,
            'empleado' => $empleado,
            'departamentos' => $departamentos,
            'puestos_trabajo' => $puestos_trabajo,
            'redes_sociales' => json_decode($empleado->redes_sociales)
        ]);
    }

    public static function eliminar() {
        session_start();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!is_auth() || !is_admin()) {
                header('Location: /login');
                return;
            }

            $id = $_POST['id'];
            $empleado = Empleado::find($id);
            if (!isset($empleado)) {
                header('Location: /admin/empleados');
                return;
            }
            $resultado = $empleado->eliminar();
            if ($resultado) {
                header('Location: /admin/empleados');
            }
        }
    }
}

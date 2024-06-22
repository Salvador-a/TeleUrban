<?php

namespace Controllers;

use MVC\Router;
use Model\Empleado;
use Classes\Paginacion;
use Model\Departamento;
use Intervention\Image\ImageManagerStatic as Image;

class DepartamentosController {

    public static function index(Router $router) {
        if (!is_admin()) {
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

        $departamentos = Departamento::paginar($registros_por_pagina, $paginacion->offset());

        // Obtener los datos del encargado para cada departamento
        foreach ($departamentos as $departamento) {
            $departamento->encargado = Empleado::find($departamento->id_encargado);
        }

        $router->render('admin/departamentos/index', [
            'titulo' => 'Departamento',
            'departamentos' => $departamentos,
            'paginacion' => $paginacion->paginacion()
        ]);
    }

    public static function crear(Router $router) {
        if (!is_admin()) {
            header('Location: /login');
            return;
        }

        $alertas = [];
        $empleados = Empleado::all();
        $departamento = new Departamento;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!is_admin()) {
                header('Location: /login');
                return;
            }

            // Leer imagen
            if (!empty($_FILES['imagen']['tmp_name'])) {
                $carpeta_imagenes = '../public/img/galeria';

                // Crear la carpeta si no existe
                if (!is_dir($carpeta_imagenes)) {
                    mkdir($carpeta_imagenes, 0755, true);
                }

                $imagen_png = Image::make($_FILES['imagen']['tmp_name'])->fit(800, 800)->encode('png', 80);
                $imagen_webp = Image::make($_FILES['imagen']['tmp_name'])->fit(800, 800)->encode('webp', 80);

                $nombre_imagen = md5(uniqid(rand(), true));

                $_POST['imagen'] = $nombre_imagen;
            } 

            // Sincronizar con los datos del POST
            $departamento->sincronizar($_POST);

            // validar
            $alertas = $departamento->validar();

            // Guardar el registro
            if (empty($alertas)) {
                // Guardar las imagenes
                if (isset($imagen_png) && isset($imagen_webp)) {
                    $imagen_png->save($carpeta_imagenes . '/' . $nombre_imagen . ".png");
                    $imagen_webp->save($carpeta_imagenes . '/' . $nombre_imagen . ".webp");
                }

                // Guardar en la BD
                $resultado = $departamento->guardar();

                if ($resultado) {
                    header('Location: /admin/departamentos');
                    return;
                }
            }
        }

        $router->render('admin/departamentos/crear', [
            'titulo' => 'Registrar Departamentos',
            'alertas' => $alertas,
            'departamento' => $departamento,
            'empleados' => $empleados,
        ]);
    }

    public static function editar(Router $router) {
        if (!is_admin()) {
            header('Location: /login');
            return;
        }

        $alertas = [];
        // Validar el ID
        $id = $_GET['id'];
        $id = filter_var($id, FILTER_VALIDATE_INT);

        if (!$id) {
            header('Location: /admin/departamentos');
            return;
        }

        // Obtener departamento a Editar
        $departamento = Departamento::find($id);
        $empleados = Empleado::all();

        if (!$departamento) {
            header('Location: /admin/departamentos');
            return;
        }

        $departamento->imagen_actual = $departamento->imagen;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!is_admin()) {
                header('Location: /login');
                return;
            }

            $carpeta_imagenes = '../public/img/galeria';

            // Crear la carpeta si no existe
            if (!is_dir($carpeta_imagenes)) {
                mkdir($carpeta_imagenes, 0755, true);
            }

            if (!empty($_FILES['imagen']['tmp_name'])) {
                $imagen_png = Image::make($_FILES['imagen']['tmp_name'])->fit(800, 800)->encode('png', 80);
                $imagen_webp = Image::make($_FILES['imagen']['tmp_name'])->fit(800, 800)->encode('webp', 80);

                $nombre_imagen = md5(uniqid(rand(), true));

                $_POST['imagen'] = $nombre_imagen;
            } else {
                $_POST['imagen'] = $departamento->imagen_actual;
            }

            // Sincronizar con los datos del POST
            $departamento->sincronizar($_POST);

            $alertas = $departamento->validar();

            if (empty($alertas)) {
                if (isset($imagen_png) && isset($imagen_webp)) {
                    $imagen_png->save($carpeta_imagenes . '/' . $nombre_imagen . ".png");
                    $imagen_webp->save($carpeta_imagenes . '/' . $nombre_imagen . ".webp");
                }
                $resultado = $departamento->guardar();
                if ($resultado) {
                    header('Location: /admin/departamentos');
                    return;
                }
            }
        }

        $router->render('admin/departamentos/editar', [
            'titulo' => 'Actualizar Departamento',
            'alertas' => $alertas,
            'departamento' => $departamento,
            'empleados' => $empleados,
        ]);
    }

    public static function eliminar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!is_admin()) {
                header('Location: /login');
                return;
            }
            
            $id = $_POST['id'];
            $departamento = Departamento::find($id);
            if (!isset($departamento)) {
                header('Location: /admin/departamentos');
                return;
            }
            $resultado = $departamento->eliminar();
            if ($resultado) {
                header('Location: /admin/departamentos');
                return;
            }
        }
    }

    public static function publicar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!is_admin()) {
                header('Location: /login');
                return;
            }

            $id = $_POST['id'];
            $departamento = Departamento::find($id);

            if (!isset($departamento)) {
                header('Location: /admin/departamentos');
                return;
            }

            // Leer el archivo JSON
            $file = __DIR__ . '/../config/departamentos_publicados.json';
            if (file_exists($file)) {
                $data = json_decode(file_get_contents($file), true);
            } else {
                $data = ['publicados' => []];
            }

            // Agregar el ID del departamento a la lista de publicados si no está ya en la lista
            if (!in_array($id, $data['publicados'])) {
                $data['publicados'][] = $id;
                file_put_contents($file, json_encode($data));
            }

            // Copiar las imágenes del departamento al directorio público si es necesario
            $imagen_src = __DIR__ . '/../public/img/galeria/' . $departamento->imagen . '.png';
            $imagen_dest = __DIR__ . '/../../public/img/galeria/' . $departamento->imagen . '.png';

            if (file_exists($imagen_src) && !file_exists($imagen_dest)) {
                copy($imagen_src, $imagen_dest);
            }

            $imagen_src_webp = __DIR__ . '/../public/img/galeria/' . $departamento->imagen . '.webp';
            $imagen_dest_webp = __DIR__ . '/../../public/img/galeria/' . $departamento->imagen . '.webp';

            if (file_exists($imagen_src_webp) && !file_exists($imagen_dest_webp)) {
                copy($imagen_src_webp, $imagen_dest_webp);
            }

            // Copiar las imágenes del encargado al directorio público si es necesario
            $encargado_imagen_src = __DIR__ . '/../public/img/galeria/' . $departamento->encargado->imagen . '.png';
            $encargado_imagen_dest = __DIR__ . '/../../public/img/galeria/' . $departamento->encargado->imagen . '.png';

            if (file_exists($encargado_imagen_src) && !file_exists($encargado_imagen_dest)) {
                copy($encargado_imagen_src, $encargado_imagen_dest);
            }

            $encargado_imagen_src_webp = __DIR__ . '/../public/img/galeria/' . $departamento->encargado->imagen . '.webp';
            $encargado_imagen_dest_webp = __DIR__ . '/../../public/img/galeria/' . $departamento->encargado->imagen . '.webp';

            if (file_exists($encargado_imagen_src_webp) && !file_exists($encargado_imagen_dest_webp)) {
                copy($encargado_imagen_src_webp, $encargado_imagen_dest_webp);
            }

            header('Location: /admin/departamentos');
            return;
        }
    }

    public static function detalle(Router $router) {
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
            'departamento' => $departamento
        ]);
    }
}


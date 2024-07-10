<?php

// controllers/DepartamentosController.php

namespace Controllers;

use MVC\Router;
use Model\Departamento;
use Model\Empleado;
use Intervention\Image\ImageManagerStatic as Image;
use Classes\Paginacion;

class DepartamentosController {
    private static function validarORedireccionar($url) {
        $id = $_GET['id'] ?? null;
        $id = filter_var($id, FILTER_VALIDATE_INT);
        if (!$id) {
            header("Location: {$url}");
            exit;
        }
        return $id;
    }

    public static function index(Router $router) {
        session_start();

        if (!is_auth()) {
            header('Location: /login');
            exit;
        }

        $pagina_actual = $_GET['page'] ?? 1;
        $pagina_actual = filter_var($pagina_actual, FILTER_VALIDATE_INT);

        if (!$pagina_actual || $pagina_actual < 1) {
            header('Location: /admin/departamentos?page=1');
            exit;
        }

        $registros_por_pagina = 4;
        $total = Departamento::total();
        $paginacion = new Paginacion($pagina_actual, $registros_por_pagina, $total);

        if ($paginacion->total_paginas() < $pagina_actual) {
            header('Location: /admin/departamentos?page=1');
            exit;
        }

        $departamentos = [];

        if (is_admin()) {
            $departamentos = Departamento::paginar($registros_por_pagina, $paginacion->offset());
        } elseif (is_jefe() || is_trabajador()) {
            $user_departamento_id = $_SESSION['departamento_id'];
            $departamentos = Departamento::whereArray(['id' => $user_departamento_id]);
        } else {
            header('Location: /403');
            exit;
        }

        foreach ($departamentos as $departamento) {
            $encargado = Empleado::find($departamento->id_encargado);
            if ($encargado) {
                $departamento->encargado_nombre = $encargado->nombre ?? '';
                $departamento->encargado_apellido = $encargado->apellido ?? '';
                $departamento->encargado_imagen = $encargado->imagen ?? '';
                $departamento->encargado_puesto = $encargado->obtenerNombrePuestoTrabajo() ?? '';
            } else {
                $departamento->encargado_nombre = '';
                $departamento->encargado_apellido = '';
                $departamento->encargado_imagen = '';
                $departamento->encargado_puesto = '';
            }
        }

        $user_nombre = $_SESSION['nombre'] ?? '';
        $user_apellido = $_SESSION['apellido'] ?? '';
        $user_puesto_trabajo = $_SESSION['puesto_trabajo'] ?? '';
        $user_departamento_nombre = Empleado::obtenerNombrePorId('departamentos', $_SESSION['departamento_id'], 'nombre_departamento');
        $user_role = '';

        if (is_admin()) {
            $user_role = 'admin';
        } elseif (is_jefe()) {
            $user_role = 'jefe';
        } elseif (is_trabajador()) {
            $user_role = 'trabajador';
        }

        $router->render('admin/departamentos/index', [
            'titulo' => 'Departamentos',
            'departamentos' => $departamentos,
            'paginacion' => $paginacion->paginacion(),
            'user_nombre' => $user_nombre,
            'user_apellido' => $user_apellido,
            'user_puesto_trabajo' => $user_puesto_trabajo,
            'user_departamento_nombre' => $user_departamento_nombre,
            'user_role' => $user_role
        ]);
    }

    public static function crear(Router $router) {
        session_start();

        if (!is_auth() || !is_admin()) {
            header('Location: /login');
            exit;
        }

        $alertas = [];
        $empleados = Empleado::all();
        $departamento = new Departamento;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!is_admin()) {
                header('Location: /login');
            }

            $departamento->sincronizar($_POST);

            // Validar imagen
            if (!empty($_FILES['imagen']['tmp_name'])) {
                $carpeta_imagenes = CARPETA_IMAGENES;

                // Crear la carpeta si no existe
                if (!is_dir($carpeta_imagenes)) {
                    mkdir($carpeta_imagenes, 0755, true);
                }

                $imagen_png = Image::make($_FILES['imagen']['tmp_name'])->fit(800, 600)->encode('png', 80);
                $imagen_webp = Image::make($_FILES['imagen']['tmp_name'])->fit(800, 600)->encode('webp', 80);

                $nombre_imagen = md5(uniqid(rand(), true));

                $_POST['imagen'] = $nombre_imagen;

                // Guardar las imágenes
                $imagen_png->save($carpeta_imagenes . '/' . $nombre_imagen . ".png");
                $imagen_webp->save($carpeta_imagenes . '/' . $nombre_imagen . ".webp");

                // Asignar la imagen al objeto departamento
                $departamento->imagen = $nombre_imagen;
            }

            $alertas = $departamento->validar();

            if (empty($alertas)) {
                // Asegurarse de que el campo 'disponible' tenga un valor válido
                $departamento->disponible = isset($departamento->disponible) ? (int)$departamento->disponible : 1;

                // Guardar en la base de datos
                $resultado = $departamento->guardar();

                if ($resultado) {
                    header('Location: /admin/departamentos');
                    exit;
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

    public static function editar(Router $router) {
        session_start();

        if (!is_auth() || !is_admin()) {
            header('Location: /login');
            exit;
        }

        $id = self::validarORedireccionar('/admin/departamentos');

        $departamento = Departamento::find($id);
        $empleados = Empleado::all();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!is_admin()) {
                header('Location: /login');
            }

            $departamento->sincronizar($_POST);

            // Validar imagen
            if (!empty($_FILES['imagen']['tmp_name'])) {
                $carpeta_imagenes = CARPETA_IMAGENES;

                // Crear la carpeta si no existe
                if (!is_dir($carpeta_imagenes)) {
                    mkdir($carpeta_imagenes, 0755, true);
                }

                $imagen_png = Image::make($_FILES['imagen']['tmp_name'])->fit(800, 600)->encode('png', 80);
                $imagen_webp = Image::make($_FILES['imagen']['tmp_name'])->fit(800, 600)->encode('webp', 80);

                $nombre_imagen = md5(uniqid(rand(), true));

                $_POST['imagen'] = $nombre_imagen;

                // Guardar las imágenes
                $imagen_png->save($carpeta_imagenes . '/' . $nombre_imagen . ".png");
                $imagen_webp->save($carpeta_imagenes . '/' . $nombre_imagen . ".webp");

                // Asignar la imagen al objeto departamento
                $departamento->imagen = $nombre_imagen;
            } else {
                $_POST['imagen'] = $departamento->imagen;
            }

            $alertas = $departamento->validar();

            if (empty($alertas)) {
                // Asegurarse de que el campo 'disponible' tenga un valor válido
                $departamento->disponible = isset($departamento->disponible) ? (int)$departamento->disponible : 1;

                // Guardar en la base de datos
                $resultado = $departamento->guardar();

                if ($resultado) {
                    header('Location: /admin/departamentos');
                    exit;
                }
            }
        }

        $alertas = Departamento::getAlertas();

        $router->render('admin/departamentos/editar', [
            'titulo' => 'Editar Departamento',
            'alertas' => $alertas,
            'departamento' => $departamento,
            'empleados' => $empleados
        ]);
    }

    public static function eliminar() {
        session_start();

        if (!is_auth() || !is_admin()) {
            header('Location: /login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!is_admin()) {
                header('Location: /login');
            }

            $id = $_POST['id'];
            $id = filter_var($id, FILTER_VALIDATE_INT);

            if ($id) {
                $departamento = Departamento::find($id);

                if ($departamento) {
                    $resultado = $departamento->eliminar();
                    if ($resultado) {
                        header('Location: /admin/departamentos');
                        exit;
                    }
                }
            }
        }
    }

    public static function publicar() {
        session_start();

        if (!is_auth() || !is_admin()) {
            header('Location: /login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $id = filter_var($id, FILTER_VALIDATE_INT);

            if ($id) {
                $departamento = Departamento::find($id);

                if ($departamento) {
                    // Marcar como publicado
                    $departamento->publicado = 1;
                    $resultado = $departamento->guardar();
                    if ($resultado) {
                        header('Location: /admin/departamentos');
                        exit;
                    }
                }
            }
        }
    }

    public static function despublicar() {
        session_start();

        if (!is_auth() || !is_admin()) {
            header('Location: /login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $id = filter_var($id, FILTER_VALIDATE_INT);

            if ($id) {
                $departamento = Departamento::find($id);

                if ($departamento) {
                    // Marcar como no publicado
                    $departamento->publicado = 0;
                    $resultado = $departamento->guardar();
                    if ($resultado) {
                        header('Location: /admin/departamentos');
                        exit;
                    }
                }
            }
        }
    }

    public static function toggleDisponibilidad() {
        session_start();

        if (!is_auth() || !is_admin()) {
            header('Location: /login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $id = filter_var($id, FILTER_VALIDATE_INT);

            if ($id) {
                $departamento = Departamento::find($id);

                if ($departamento) {
                    // Alternar la disponibilidad
                    $departamento->disponible = !$departamento->disponible ? 1 : 0;
                    $resultado = $departamento->guardar();
                    if ($resultado) {
                        header('Location: /admin/departamentos');
                        exit;
                    }
                }
            }
        }
    }

    public static function detalle(Router $router) {
        
        $id = $_GET['id'] ?? null;
        $id = filter_var($id, FILTER_VALIDATE_INT);

        if (!$id) {
            header('Location: /departamentos');
            exit;
        }

        $departamento = Departamento::find($id);

        if (!$departamento) {
            header('Location: /departamentos');
            exit;
        }

        $departamento->encargado = Empleado::find($departamento->id_encargado);

        $router->render('paginas/detalle-departamento', [
            'titulo' => 'Detalle Departamento',
            'departamento' => $departamento
        ]);
    }
}

<?php

namespace Controllers;

use MVC\Router;
use Model\Departamento;
use Model\Empleado;
use Intervention\Image\ImageManagerStatic as Image;
use Classes\Paginacion;

class DepartamentosController {
    
    /**
     * Valida la existencia de un ID y redirige si no es válido.
     * 
     * @param string $url URL de redireccionamiento en caso de ID no válido
     * @return int ID validado
     */
    private static function validarORedireccionar($url) {
        $id = $_GET['id'] ?? null; // Obtiene el ID de la URL
        $id = filter_var($id, FILTER_VALIDATE_INT); // Valida que el ID sea un entero
        if (!$id) {
            header("Location: {$url}"); // Redirecciona si el ID no es válido
            exit;
        }
        return $id; // Devuelve el ID validado
    }

    /**
     * Maneja la vista de departamentos.
     * 
     * @param Router $router
     */
    public static function index(Router $router) {
        session_start(); // Inicia la sesión

        // Verifica si el usuario está autenticado
        if (!is_auth()) {
            header('Location: /login'); // Redirecciona a la página de login si no está autenticado
            exit;
        }

        $pagina_actual = $_GET['page'] ?? 1; // Obtiene el número de página de la URL
        $pagina_actual = filter_var($pagina_actual, FILTER_VALIDATE_INT); // Valida que la página sea un entero

        // Redirecciona si la página no es válida
        if (!$pagina_actual || $pagina_actual < 1) {
            header('Location: /admin/departamentos?page=1');
            exit;
        }

        $registros_por_pagina = 4; // Número de registros por página
        $total = Departamento::total(); // Obtiene el total de departamentos
        $paginacion = new Paginacion($pagina_actual, $registros_por_pagina, $total); // Crea una instancia de Paginacion

        // Redirecciona si la página actual es mayor que el total de páginas
        if ($paginacion->total_paginas() < $pagina_actual) {
            header('Location: /admin/departamentos?page=1');
            exit;
        }

        $departamentos = [];

        // Verifica el rol del usuario y obtiene los departamentos en consecuencia
        if (is_admin()) {
            $departamentos = Departamento::paginar($registros_por_pagina, $paginacion->offset()); // Obtiene los departamentos paginados
        } elseif (is_jefe() || is_trabajador()) {
            $user_departamento_id = $_SESSION['departamento_id']; // Obtiene el ID del departamento del usuario
            $departamentos = Departamento::whereArray(['id' => $user_departamento_id]); // Obtiene los departamentos del usuario
        } else {
            header('Location: /403'); // Redirecciona a la página 403 si el rol no es válido
            exit;
        }

        // Obtiene el encargado de cada departamento
        foreach ($departamentos as $departamento) {
            $encargado = Empleado::find($departamento->id_encargado); // Busca al encargado por ID
            if ($encargado) {
                $departamento->encargado_nombre = $encargado->nombre ?? ''; // Nombre del encargado
                $departamento->encargado_apellido = $encargado->apellido ?? ''; // Apellido del encargado
                $departamento->encargado_imagen = $encargado->imagen ?? ''; // Imagen del encargado
                $departamento->encargado_puesto = $encargado->obtenerNombrePuestoTrabajo() ?? ''; // Puesto del encargado
            } else {
                $departamento->encargado_nombre = '';
                $departamento->encargado_apellido = '';
                $departamento->encargado_imagen = '';
                $departamento->encargado_puesto = '';
            }
        }

        // Datos del usuario para la vista
        $user_nombre = $_SESSION['nombre'] ?? ''; // Nombre del usuario
        $user_apellido = $_SESSION['apellido'] ?? ''; // Apellido del usuario
        $user_puesto_trabajo = $_SESSION['puesto_trabajo'] ?? ''; // Puesto del usuario
        $user_departamento_nombre = Empleado::obtenerNombrePorId('departamentos', $_SESSION['departamento_id'], 'nombre_departamento'); // Nombre del departamento del usuario
        $user_role = '';

        // Determina el rol del usuario
        if (is_admin()) {
            $user_role = 'admin';
        } elseif (is_jefe()) {
            $user_role = 'jefe';
        } elseif (is_trabajador()) {
            $user_role = 'trabajador';
        }

        // Renderiza la vista de departamentos
        $router->render('admin/departamentos/index', [
            'titulo' => 'Departamentos', // Título de la página
            'departamentos' => $departamentos, // Departamentos para mostrar
            'paginacion' => $paginacion->paginacion(), // Datos de paginación
            'user_nombre' => $user_nombre, // Nombre del usuario
            'user_apellido' => $user_apellido, // Apellido del usuario
            'user_puesto_trabajo' => $user_puesto_trabajo, // Puesto del usuario
            'user_departamento_nombre' => $user_departamento_nombre, // Nombre del departamento del usuario
            'user_role' => $user_role // Rol del usuario
        ]);
    }

    /**
     * Maneja la creación de un nuevo departamento.
     * 
     * @param Router $router
     */
    public static function crear(Router $router) {
        session_start(); // Inicia la sesión

        // Verifica si el usuario está autenticado y es administrador
        if (!is_auth() || !is_admin()) {
            header('Location: /login'); // Redirecciona a la página de login si no está autenticado
            exit;
        }

        $alertas = []; // Inicializa un array para alertas
        $empleados = Empleado::all(); // Obtiene todos los empleados
        $departamento = new Departamento; // Crea una nueva instancia de Departamento

        // Verifica si el método de solicitud es POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!is_admin()) {
                header('Location: /login'); // Redirecciona a la página de login si no es administrador
            }

            $departamento->sincronizar($_POST); // Sincroniza los datos del formulario con la instancia de Departamento

            // Valida la imagen
            if (!empty($_FILES['imagen']['tmp_name'])) {
                $carpeta_imagenes = CARPETA_IMAGENES; // Carpeta de imágenes

                // Crea la carpeta si no existe
                if (!is_dir($carpeta_imagenes)) {
                    mkdir($carpeta_imagenes, 0755, true);
                }

                // Procesa las imágenes
                $imagen_png = Image::make($_FILES['imagen']['tmp_name'])->fit(800, 600)->encode('png', 80);
                $imagen_webp = Image::make($_FILES['imagen']['tmp_name'])->fit(800, 600)->encode('webp', 80);

                $nombre_imagen = md5(uniqid(rand(), true)); // Genera un nombre único para la imagen

                $_POST['imagen'] = $nombre_imagen;

                // Guarda las imágenes
                $imagen_png->save($carpeta_imagenes . '/' . $nombre_imagen . ".png");
                $imagen_webp->save($carpeta_imagenes . '/' . $nombre_imagen . ".webp");

                $departamento->imagen = $nombre_imagen; // Guarda el nombre de la imagen en la instancia de Departamento
            }

            $alertas = $departamento->validar(); // Valida el formulario

            // Si no hay alertas, guarda el departamento
            if (empty($alertas)) {
                $departamento->disponible = isset($departamento->disponible) ? (int)$departamento->disponible : 1; // Asegura que el campo 'disponible' tenga un valor válido

                $resultado = $departamento->guardar(); // Guarda el departamento

                if ($resultado) {
                    header('Location: /admin/departamentos'); // Redirecciona a la página de departamentos
                    exit;
                }
            }
        }

        // Renderiza la vista de creación de departamento
        $router->render('admin/departamentos/crear', [
            'titulo' => 'Crear Departamento', // Título de la página
            'alertas' => $alertas, // Alertas para mostrar
            'departamento' => $departamento, // Departamento para el formulario
            'empleados' => $empleados // Empleados para el formulario
        ]);
    }

    /**
     * Maneja la edición de un departamento existente.
     * 
     * @param Router $router
     */
    public static function editar(Router $router) {
        session_start(); // Inicia la sesión

        // Verifica si el usuario está autenticado y es administrador
        if (!is_auth() || !is_admin()) {
            header('Location: /login'); // Redirecciona a la página de login si no está autenticado
            exit;
        }

        $id = self::validarORedireccionar('/admin/departamentos'); // Valida el ID del departamento

        $departamento = Departamento::find($id); // Busca el departamento por ID
        $empleados = Empleado::all(); // Obtiene todos los empleados

        // Verifica si el método de solicitud es POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!is_admin()) {
                header('Location: /login'); // Redirecciona a la página de login si no es administrador
            }

            $departamento->sincronizar($_POST); // Sincroniza los datos del formulario con la instancia de Departamento

            // Valida la imagen
            if (!empty($_FILES['imagen']['tmp_name'])) {
                $carpeta_imagenes = CARPETA_IMAGENES; // Carpeta de imágenes

                // Crea la carpeta si no existe
                if (!is_dir($carpeta_imagenes)) {
                    mkdir($carpeta_imagenes, 0755, true);
                }

                // Procesa las imágenes
                $imagen_png = Image::make($_FILES['imagen']['tmp_name'])->fit(800, 600)->encode('png', 80);
                $imagen_webp = Image::make($_FILES['imagen']['tmp_name'])->fit(800, 600)->encode('webp', 80);

                $nombre_imagen = md5(uniqid(rand(), true)); // Genera un nombre único para la imagen

                $_POST['imagen'] = $nombre_imagen;

                // Guarda las imágenes
                $imagen_png->save($carpeta_imagenes . '/' . $nombre_imagen . ".png");
                $imagen_webp->save($carpeta_imagenes . '/' . $nombre_imagen . ".webp");

                $departamento->imagen = $nombre_imagen; // Guarda el nombre de la imagen en la instancia de Departamento
            } else {
                $_POST['imagen'] = $departamento->imagen; // Mantiene la imagen actual si no se sube una nueva
            }

            $alertas = $departamento->validar(); // Valida el formulario

            // Si no hay alertas, guarda el departamento
            if (empty($alertas)) {
                $departamento->disponible = isset($departamento->disponible) ? (int)$departamento->disponible : 1; // Asegura que el campo 'disponible' tenga un valor válido

                $resultado = $departamento->guardar(); // Guarda el departamento

                if ($resultado) {
                    header('Location: /admin/departamentos'); // Redirecciona a la página de departamentos
                    exit;
                }
            }
        }

        $alertas = Departamento::getAlertas(); // Obtiene todas las alertas

        // Renderiza la vista de edición de departamento
        $router->render('admin/departamentos/editar', [
            'titulo' => 'Editar Departamento', // Título de la página
            'alertas' => $alertas, // Alertas para mostrar
            'departamento' => $departamento, // Departamento para el formulario
            'empleados' => $empleados // Empleados para el formulario
        ]);
    }

    /**
     * Maneja la eliminación de un departamento.
     */
    public static function eliminar() {
        session_start(); // Inicia la sesión

        // Verifica si el usuario está autenticado y es administrador
        if (!is_auth() || !is_admin()) {
            header('Location: /login'); // Redirecciona a la página de login si no está autenticado
            exit;
        }

        // Verifica si el método de solicitud es POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!is_admin()) {
                header('Location: /login'); // Redirecciona a la página de login si no es administrador
            }

            $id = $_POST['id'];
            $id = filter_var($id, FILTER_VALIDATE_INT); // Valida el ID del departamento

            // Si el ID es válido, elimina el departamento
            if ($id) {
                $departamento = Departamento::find($id);

                if ($departamento) {
                    $resultado = $departamento->eliminar(); // Elimina el departamento
                    if ($resultado) {
                        header('Location: /admin/departamentos'); // Redirecciona a la página de departamentos
                        exit;
                    }
                }
            }
        }
    }

    /**
     * Maneja la publicación de un departamento.
     */
    public static function publicar() {
        session_start(); // Inicia la sesión

        // Verifica si el usuario está autenticado y es administrador
        if (!is_auth() || !is_admin()) {
            header('Location: /login'); // Redirecciona a la página de login si no está autenticado
            exit;
        }

        // Verifica si el método de solicitud es POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $id = filter_var($id, FILTER_VALIDATE_INT); // Valida el ID del departamento

            // Si el ID es válido, publica el departamento
            if ($id) {
                $departamento = Departamento::find($id);

                if ($departamento) {
                    $departamento->publicado = 1; // Marca el departamento como publicado
                    $resultado = $departamento->guardar(); // Guarda el departamento
                    if ($resultado) {
                        header('Location: /admin/departamentos'); // Redirecciona a la página de departamentos
                        exit;
                    }
                }
            }
        }
    }

    /**
     * Maneja la despublicación de un departamento.
     */
    public static function despublicar() {
        session_start(); // Inicia la sesión

        // Verifica si el usuario está autenticado y es administrador
        if (!is_auth() || !is_admin()) {
            header('Location: /login'); // Redirecciona a la página de login si no está autenticado
            exit;
        }

        // Verifica si el método de solicitud es POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $id = filter_var($id, FILTER_VALIDATE_INT); // Valida el ID del departamento

            // Si el ID es válido, despublica el departamento
            if ($id) {
                $departamento = Departamento::find($id);

                if ($departamento) {
                    $departamento->publicado = 0; // Marca el departamento como no publicado
                    $resultado = $departamento->guardar(); // Guarda el departamento
                    if ($resultado) {
                        header('Location: /admin/departamentos'); // Redirecciona a la página de departamentos
                        exit;
                    }
                }
            }
        }
    }

    /**
     * Alterna la disponibilidad de un departamento.
     */
    public static function toggleDisponibilidad() {
        session_start(); // Inicia la sesión

        // Verifica si el usuario está autenticado y es administrador
        if (!is_auth() || !is_admin()) {
            header('Location: /login'); // Redirecciona a la página de login si no está autenticado
            exit;
        }

        // Verifica si el método de solicitud es POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $id = filter_var($id, FILTER_VALIDATE_INT); // Valida el ID del departamento

            // Si el ID es válido, alterna la disponibilidad del departamento
            if ($id) {
                $departamento = Departamento::find($id);

                if ($departamento) {
                    $departamento->disponible = !$departamento->disponible ? 1 : 0; // Alterna la disponibilidad
                    $resultado = $departamento->guardar(); // Guarda el departamento
                    if ($resultado) {
                        header('Location: /admin/departamentos'); // Redirecciona a la página de departamentos
                        exit;
                    }
                }
            }
        }
    }

    /**
     * Maneja la vista de detalle de un departamento.
     * 
     * @param Router $router
     */
    public static function detalle(Router $router) {
        $id = $_GET['id'] ?? null; // Obtiene el ID del departamento de la URL
        $id = filter_var($id, FILTER_VALIDATE_INT); // Valida el ID

        // Si el ID no es válido, redirecciona a la página de departamentos
        if (!$id) {
            header('Location: /departamentos');
            exit;
        }

        $departamento = Departamento::find($id); // Busca el departamento por ID

        // Si no encuentra el departamento, redirecciona a la página de departamentos
        if (!$departamento) {
            header('Location: /departamentos');
            exit;
        }

        $departamento->encargado = Empleado::find($departamento->id_encargado); // Busca al encargado del departamento

        // Renderiza la vista de detalle de departamento
        $router->render('paginas/detalle-departamento', [
            'titulo' => 'Detalle Departamento', // Título de la página
            'departamento' => $departamento // Departamento para mostrar
        ]);
    }
}

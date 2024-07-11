<?php

namespace Controllers;

use MVC\Router;
use Model\Empleado;
use Model\Departamento;
use Model\PuestoTrabajo;
use Classes\Paginacion;
use Intervention\Image\ImageManagerStatic as Image;

class EmpleadosController {

    /**
     * Maneja la vista de empleados.
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

        // Verifica si el usuario tiene permisos para ver la página
        if (!is_admin() && !is_jefe() && !is_trabajador()) {
            header('Location: /403'); // Redirecciona a la página 403 si no tiene permisos
            return;
        }

        $user_role = '';
        $departamento_id = $_SESSION['departamento_id'] ?? null; // Obtiene el ID del departamento del usuario
        $user_nombre = $_SESSION['nombre'] ?? ''; // Nombre del usuario
        $user_apellido = $_SESSION['apellido'] ?? ''; // Apellido del usuario
        $user_puesto_trabajo = $_SESSION['puesto_trabajo'] ?? ''; // Puesto del usuario
        $user_departamento_nombre = Empleado::obtenerNombrePorId('departamentos', $departamento_id, 'nombre_departamento'); // Nombre del departamento del usuario

        // Determina el rol del usuario
        if (is_admin()) {
            $user_role = 'admin';
        } elseif (is_jefe()) {
            $user_role = 'jefe';
        } elseif (is_trabajador()) {
            $user_role = 'trabajador';
        }

        $pagina_actual = $_GET['page'] ?? 1; // Obtiene el número de página de la URL
        $pagina_actual = filter_var($pagina_actual, FILTER_VALIDATE_INT); // Valida que la página sea un entero

        // Redirecciona si la página no es válida
        if (!$pagina_actual || $pagina_actual < 1) {
            header('Location: /admin/empleados?page=1');
            return;
        }

        $registros_por_pagina = 4; // Número de registros por página
        if (is_admin()) {
            $total = Empleado::total(); // Obtiene el total de empleados
        } else {
            $total = Empleado::total('departamento_id', $departamento_id); // Obtiene el total de empleados del departamento del usuario
        }

        $paginacion = new Paginacion($pagina_actual, $registros_por_pagina, $total); // Crea una instancia de Paginacion

        // Redirecciona si la página actual es mayor que el total de páginas
        if ($paginacion->total_paginas() < $pagina_actual) {
            header('Location: /admin/empleados?page=1');
            return;
        }

        if (is_admin()) {
            $empleados = Empleado::paginar($registros_por_pagina, $paginacion->offset()); // Obtiene los empleados paginados
        } else {
            $empleados = Empleado::whereArray(['departamento_id' => $departamento_id]); // Obtiene los empleados del departamento del usuario
        }

        // Obtiene el nombre del puesto de trabajo y del departamento de cada empleado
        foreach ($empleados as $empleado) {
            $empleado->puesto_trabajo_nombre = $empleado->obtenerNombrePuestoTrabajo(); // Nombre del puesto de trabajo
            $empleado->departamento_nombre = $empleado->obtenerNombreDepartamento(); // Nombre del departamento
        }

        // Renderiza la vista de empleados
        $router->render('admin/empleados/index', [
            'titulo' => 'Empleados', // Título de la página
            'empleados' => $empleados, // Empleados para mostrar
            'paginacion' => $paginacion->paginacion(), // Datos de paginación
            'user_role' => $user_role, // Rol del usuario
            'user_nombre' => $user_nombre, // Nombre del usuario
            'user_apellido' => $user_apellido, // Apellido del usuario
            'user_puesto_trabajo' => $user_puesto_trabajo, // Puesto del usuario
            'user_departamento_nombre' => $user_departamento_nombre // Nombre del departamento del usuario
        ]);
    }

    /**
     * Maneja la creación de un nuevo empleado.
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
        $empleado = new Empleado; // Crea una nueva instancia de Empleado

        // Obtiene departamentos y puestos de trabajo
        $departamentos = Departamento::all(); // Obtiene todos los departamentos
        $puestos_trabajo = PuestoTrabajo::all(); // Obtiene todos los puestos de trabajo

        // Verifica si el método de solicitud es POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $empleado->sincronizar($_POST); // Sincroniza los datos del formulario con la instancia de Empleado

            // Valida imagen
            if (!empty($_FILES['imagen']['tmp_name'])) {
                $carpeta_imagenes = CARPETA_IMAGENES; // Carpeta de imágenes

                // Crea la carpeta si no existe
                if (!is_dir($carpeta_imagenes)) {
                    mkdir($carpeta_imagenes, 0755, true);
                }

                // Procesa las imágenes
                $imagen_png = Image::make($_FILES['imagen']['tmp_name'])->fit(800, 800)->encode('png', 80);
                $imagen_webp = Image::make($_FILES['imagen']['tmp_name'])->fit(800, 800)->encode('webp', 80);

                $nombre_imagen = md5(uniqid(rand(), true)); // Genera un nombre único para la imagen
                $imagen_png->save($carpeta_imagenes . '/' . $nombre_imagen . ".png"); // Guarda la imagen en formato PNG
                $imagen_webp->save($carpeta_imagenes . '/' . $nombre_imagen . ".webp"); // Guarda la imagen en formato WebP

                $empleado->imagen = $nombre_imagen; // Guarda el nombre de la imagen en la instancia de Empleado
            } else {
                $alertas['error'][] = 'La imagen es obligatoria'; // Añade una alerta si la imagen es obligatoria
            }

            $alertas = $empleado->validar(); // Valida el formulario

            // Si no hay alertas, guarda el empleado
            if (empty($alertas)) {
                $resultado = $empleado->guardar(); // Guarda el empleado

                if ($resultado) {
                    header('Location: /admin/empleados'); // Redirecciona a la página de empleados
                }
            }
        }

        // Renderiza la vista de creación de empleado
        $router->render('admin/empleados/crear', [
            'titulo' => 'Registrar Empleado', // Título de la página
            'alertas' => $alertas, // Alertas para mostrar
            'empleado' => $empleado, // Empleado para el formulario
            'departamentos' => $departamentos, // Departamentos para el formulario
            'puestos_trabajo' => $puestos_trabajo, // Puestos de trabajo para el formulario
            'redes_sociales' => json_decode($empleado->redes_sociales) // Redes sociales del empleado
        ]);
    }

    /**
     * Maneja la edición de un empleado existente.
     * 
     * @param Router $router
     */
    public static function editar(Router $router) {
        session_start(); // Inicia la sesión

        // Verifica si el usuario está autenticado y es administrador
        if (!is_auth() || !is_admin()) {
            header('Location: /login'); // Redirecciona a la página de login si no está autenticado
            return;
        }

        $alertas = []; // Inicializa un array para alertas
        $id = $_GET['id'];
        $id = filter_var($id, FILTER_VALIDATE_INT); // Valida el ID del empleado

        // Si el ID no es válido, redirecciona a la página de empleados
        if (!$id) {
            header('Location: /admin/empleados');
            return;
        }

        $empleado = Empleado::find($id); // Busca el empleado por ID

        // Si no encuentra el empleado, redirecciona a la página de empleados
        if (!$empleado) {
            header('Location: /admin/empleados');
            return;
        }

        $empleado->imagen_actual = $empleado->imagen; // Guarda la imagen actual del empleado

        // Obtiene departamentos y puestos de trabajo
        $departamentos = Departamento::all(); // Obtiene todos los departamentos
        $puestos_trabajo = PuestoTrabajo::all(); // Obtiene todos los puestos de trabajo

        // Verifica si el método de solicitud es POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $empleado->sincronizar($_POST); // Sincroniza los datos del formulario con la instancia de Empleado

            // Valida la imagen
            if (!empty($_FILES['imagen']['tmp_name'])) {
                $carpeta_imagenes = CARPETA_IMAGENES; // Carpeta de imágenes

                // Crea la carpeta si no existe
                if (!is_dir($carpeta_imagenes)) {
                    mkdir($carpeta_imagenes, 0755, true);
                }

                // Procesa las imágenes
                $imagen_png = Image::make($_FILES['imagen']['tmp_name'])->fit(800, 800)->encode('png', 80);
                $imagen_webp = Image::make($_FILES['imagen']['tmp_name'])->fit(800, 800)->encode('webp', 80);

                $nombre_imagen = md5(uniqid(rand(), true)); // Genera un nombre único para la imagen
                $imagen_png->save($carpeta_imagenes . '/' . $nombre_imagen . ".png"); // Guarda la imagen en formato PNG
                $imagen_webp->save($carpeta_imagenes . '/' . $nombre_imagen . ".webp"); // Guarda la imagen en formato WebP

                $empleado->imagen = $nombre_imagen; // Guarda el nombre de la imagen en la instancia de Empleado
            } else {
                $empleado->imagen = $empleado->imagen_actual; // Mantiene la imagen actual si no se sube una nueva
            }

            $alertas = $empleado->validar(); // Valida el formulario

            // Si no hay alertas, guarda el empleado
            if (empty($alertas)) {
                $resultado = $empleado->guardar(); // Guarda el empleado
                if ($resultado) {
                    header('Location: /admin/empleados'); // Redirecciona a la página de empleados
                }
            }
        }

        // Renderiza la vista de edición de empleado
        $router->render('admin/empleados/editar', [
            'titulo' => 'Actualizar Empleado', // Título de la página
            'alertas' => $alertas, // Alertas para mostrar
            'empleado' => $empleado, // Empleado para el formulario
            'departamentos' => $departamentos, // Departamentos para el formulario
            'puestos_trabajo' => $puestos_trabajo, // Puestos de trabajo para el formulario
            'redes_sociales' => json_decode($empleado->redes_sociales) // Redes sociales del empleado
        ]);
    }

    /**
     * Maneja la eliminación de un empleado.
     */
    public static function eliminar() {
        session_start(); // Inicia la sesión

        // Verifica si el método de solicitud es POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Verifica si el usuario está autenticado y es administrador
            if (!is_auth() || !is_admin()) {
                header('Location: /login'); // Redirecciona a la página de login si no está autenticado
                return;
            }

            $id = $_POST['id'];
            $id = filter_var($id, FILTER_VALIDATE_INT); // Valida el ID del empleado

            // Si el ID es válido, elimina el empleado
            if ($id) {
                $empleado = Empleado::find($id);

                if ($empleado) {
                    $resultado = $empleado->eliminar(); // Elimina el empleado
                    if ($resultado) {
                        header('Location: /admin/empleados'); // Redirecciona a la página de empleados
                        exit;
                    }
                }
            }
        }
    }
}

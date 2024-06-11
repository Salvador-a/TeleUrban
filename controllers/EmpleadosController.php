<?php

namespace Controllers;


use MVC\Router;
use Model\Empleado;
use Classes\Paginacion;
use Intervention\Image\ImageManagerStatic as Image;


class EmpleadosController {

    public static function index(Router $router) {
         if(!is_admin()) {
             header('Location: /login');
         }

         $pagina_actual = $_GET['page'];
         $pagina_actual = filter_var($pagina_actual, FILTER_VALIDATE_INT);

         if(!$pagina_actual || $pagina_actual < 1) {
             header('Location: /admin/empleados?page=1');
         }
         $registros_por_pagina = 4;
         $total = Empleado::total();
         $paginacion = new Paginacion($pagina_actual, $registros_por_pagina, $total);

         if($paginacion->total_paginas() < $pagina_actual) {
             header('Location: /admin/empleados?page=1');
         }

         $empleados = Empleado::paginar($registros_por_pagina, $paginacion->offset());


        // $empleados = Empleado::all();
        
        $router->render('admin/empleados/index', [
            'titulo' => 'Empleados ',
            'empleados' => $empleados,            
            'paginacion' => $paginacion->paginacion()
        ]);
    }

    public static function crear(Router $router) {
        if(!is_admin()) {
            header('Location: /login');
        }

        $alertas = [];
        $empleado = new Empleado;

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            if(!is_admin()) {
                header('Location: /login');
            }

            // Leer imagen
            if(!empty($_FILES['imagen']['tmp_name'])) {
                
                $carpeta_imagenes = '../public/img/galeria';

                // Crear la carpeta si no existe
                if(!is_dir($carpeta_imagenes)) {
                    mkdir($carpeta_imagenes, 0755, true);
                }

                $imagen_png = Image::make($_FILES['imagen']['tmp_name'])->fit(800,800)->encode('png', 80);
                $imagen_webp = Image::make($_FILES['imagen']['tmp_name'])->fit(800,800)->encode('webp', 80);

                $nombre_imagen = md5( uniqid( rand(), true) );


                $_POST['imagen'] = $nombre_imagen;
            } 

            $_POST['redes_sociales'] = json_encode( $_POST['redes_sociales'], JSON_UNESCAPED_SLASHES );        
            $empleado->sincronizar($_POST);

            // validar
            $alertas = $empleado->validar();


            // Guardar el registro
            if(empty($alertas)) {

                // Guardar las imagenes
                $imagen_png->save($carpeta_imagenes . '/' . $nombre_imagen . ".png" );
                $imagen_webp->save($carpeta_imagenes . '/' . $nombre_imagen . ".webp" );

                // Guardar en la BD
                $resultado = $empleado->guardar();

                if($resultado) {
                    header('Location: /admin/empleados');
                }
            }
        }

        $router->render('admin/empleados/crear', [
            
            'titulo' => 'Registrar Empleado',
            'alertas' => $alertas,
            'empleado' => $empleado,
            'redes_sociales' => json_decode($empleado->redes_sociales)
        ]);
    }

    public static function editar(Router $router) {
        if(!is_admin()) {
            header('Location: /login');
        }
        $alertas = [];
        // Validar el ID
        $id = $_GET['id'];
        $id = filter_var($id, FILTER_VALIDATE_INT);

        if(!$id) {
            header('Location: /admin/empleados');
        }

        // Obtener empleado a Editar
        $empleado = Empleado::find($id);

        if(!$empleado) {
            header('Location: /admin/empleados');
        }

        $empleado->imagen_actual = $empleado->imagen;

        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            if(!is_admin()) {
                header('Location: /login');
            }

            if(!empty($_FILES['imagen']['tmp_name'])) {
                
                $carpeta_imagenes = '../public/img/galeria';

                // Crear la carpeta si no existe
                if(!is_dir($carpeta_imagenes)) {
                    mkdir($carpeta_imagenes, 0755, true);
                }

                $imagen_png = Image::make($_FILES['imagen']['tmp_name'])->fit(800,800)->encode('png', 80);
                $imagen_webp = Image::make($_FILES['imagen']['tmp_name'])->fit(800,800)->encode('webp', 80);

                $nombre_imagen = md5( uniqid( rand(), true) );

                $_POST['imagen'] = $nombre_imagen;
            } else {
                $_POST['imagen'] = $empleado->imagen_actual;
            }

            $_POST['redes_sociales'] = json_encode( $_POST['redes_sociales'], JSON_UNESCAPED_SLASHES );     
            $empleado->sincronizar($_POST);

            $alertas = $empleado->validar();

            if(empty($alertas)) {
                if(isset($nombre_imagen)) {
                    $imagen_png->save($carpeta_imagenes . '/' . $nombre_imagen . ".png" );
                    $imagen_webp->save($carpeta_imagenes . '/' . $nombre_imagen . ".webp" );
                }
                $resultado = $empleado->guardar();
                if($resultado) {
                    header('Location: /admin/empleados');
                }
            }

        }

        $router->render('admin/empleados/editar', [
            'titulo' => 'Actualizar Empleado',
            'alertas' => $alertas,
            'empleado' => $empleado,
            'redes_sociales' => json_decode($empleado->redes_sociales)
        ]);

    }

    public static function eliminar() {
 
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            if(!is_admin()) {
                header('Location: /login');
            }
            
            $id = $_POST['id'];
            $empleado = Empleado::find($id);
            if(!isset($empleado) ) {
                header('Location: /admin/empleados');
            }
            $resultado = $empleado->eliminar();
            if($resultado) {
                header('Location: /admin/empleados');
            }
        }

    }

}

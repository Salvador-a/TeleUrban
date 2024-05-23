<?php

namespace Controllers;


use MVC\Router;
use Model\Empleado;
use Intervention\Image\ImageManagerStatic as Image;


class EmpleadosController {

    public static function index(Router $router) {
         if(!is_admin()) {
             header('Location: /login');
         }
        
        $router->render('admin/empleados/index', [
            'titulo' => 'Empleados ',
            // 'ponentes' => $ponentes,
            // 'paginacion' => $paginacion->paginacion()
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
            $_POST['redes'] = json_encode( $_POST['redes'], JSON_UNESCAPED_SLASHES );        
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
            // 'redes' => json_decode($empleado->redes)
        ]);
    }

}

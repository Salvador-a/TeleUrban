<?php

namespace Controllers;

use Model\Dia;
use Model\Hora;
use MVC\Router;
use Model\Evento;
use Model\Ponente;
use Model\Categoria;

class PaginasController {
    public static function index(Router $router) { 

        $router->render('paginas/index', [
            'titulo' => 'Inicio',
            // 'eventos' => $eventos_formateados,	
            // 'ponentes_total' => $ponentes_total,
            // 'conferencias_total' => $conferencias_total,
            // 'workshops_total' => $workshops_total,
            // 'ponentes' => $ponentes
            
        ]);


    }

    public static function nosotros(Router $router) {

        $router->render('paginas/teleurban', [
            'titulo' => 'Sobre Teleurban'
        ]);


    }

    public static function departamentos(Router $router) {

        $router->render('paginas/departamentos', [
            'titulo' => 'Areas de Trbajos'
        ]);


    }
    public static function citas(Router $router) {

               

        $router->render('paginas/citas', [
            'titulo' => 'Genera tu Cita',
            'eventos' => $eventos_formateados,

        ]);


    }

    public static function error(Router $router) {

        $router->render('paginas/error', [
            'titulo' => 'Página no encontrada'
        ]);
    }




}
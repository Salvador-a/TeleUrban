<?php

namespace Controllers;

use MVC\Router;
use Model\Empleado;
use Model\Departamento;

class PaginasController {
    public static function index(Router $router) {
        // Obtener solo los departamentos publicados
        $departamentos = Departamento::where('publicado', 1);

        foreach ($departamentos as $departamento) {
            // Obtener el encargado para cada departamento
            if (is_object($departamento)) {
                $departamento->encargado = Empleado::find($departamento->id_encargado);
            }
        }

        $router->render('paginas/index', [
            'titulo' => 'Inicio',
            'departamentos' => $departamentos
        ]);
    }

    public static function nosotros(Router $router) {
        $router->render('paginas/teleurban', [
            'titulo' => 'Sobre Nosotros'
        ]);
    }

    public static function departamentos(Router $router) {
        // Obtener solo los departamentos publicados
        $departamentos = Departamento::where('publicado', 1);

        foreach ($departamentos as $departamento) {
            // Obtener el encargado para cada departamento
            if (is_object($departamento)) {
                $departamento->encargado = Empleado::find($departamento->id_encargado);
            }
        }

        $router->render('paginas/departamentos', [
            'titulo' => 'Departamentos',
            'departamentos' => $departamentos
        ]);
    }

    public static function error(Router $router) {
        $router->render('paginas/error', [
            'titulo' => 'Página no encontrada'
        ]);
    }
}

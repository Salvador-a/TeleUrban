<?php

namespace Controllers;

use MVC\Router;
use Model\Empleado;
use Model\Departamento;

class PaginasController {
    public static function index(Router $router) {
        $router->render('paginas/index', [
            'titulo' => 'Inicio'
        ]);
    }

    public static function nosotros(Router $router) {
        $router->render('paginas/teleurban', [
            'titulo' => 'Sobre Nosotros'
        ]);
    }

    public static function departamentos(Router $router) {
        // Obtener todos los departamentos
        $departamentos = Departamento::all();
        $empleados = Empleado::all();

        // Leer el archivo JSON
        $file = __DIR__ . '/../config/departamentos_publicados.json';
        if (file_exists($file)) {
            $data = json_decode(file_get_contents($file), true);
        } else {
            $data = ['publicados' => []];
        }

        // Filtrar solo los departamentos publicados
        $departamentos_publicados = [];
        foreach ($departamentos as $departamento) {
            if (in_array($departamento->id, $data['publicados'])) {
                // Obtener el encargado para cada departamento
                $departamento->encargado = Empleado::find($departamento->id_encargado);
                $departamentos_publicados[] = $departamento;
            }
        }

        $router->render('paginas/departamentos', [
            'titulo' => 'Departamentos',
            'departamentos' => $departamentos_publicados,
            'empleados' => $empleados,
        ]);
    }

    
}

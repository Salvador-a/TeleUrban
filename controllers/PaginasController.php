<?php

namespace Controllers;

use MVC\Router;
use Model\Empleado;
use Model\Departamento;

class PaginasController {

    public static function index(Router $router) {
        // Obtener solo los departamentos publicados
        $departamentos = Departamento::where('publicado', 1);

        // Iterar sobre cada departamento para obtener su encargado
        foreach ($departamentos as $departamento) {
            // Verificar si el departamento es un objeto válido
            if (is_object($departamento)) {
                // Obtener el encargado del departamento
                $departamento->encargado = Empleado::find($departamento->id_encargado);
            }
        }

        // Renderizar la vista de la página principal
        $router->render('paginas/index', [
            'titulo' => 'Inicio', // Título de la página
            'departamentos' => $departamentos // Departamentos para mostrar
        ]);
    }

    public static function nosotros(Router $router) {
        // Renderizar la vista de la página "Sobre Nosotros"
        $router->render('paginas/teleurban', [
            'titulo' => 'Sobre Nosotros' // Título de la página
        ]);
    }

    public static function departamentos(Router $router) {
        // Obtener solo los departamentos publicados
        $departamentos = Departamento::where('publicado', 1);

        // Iterar sobre cada departamento para obtener su encargado
        foreach ($departamentos as $departamento) {
            // Verificar si el departamento es un objeto válido
            if (is_object($departamento)) {
                // Obtener el encargado del departamento
                $departamento->encargado = Empleado::find($departamento->id_encargado);
            }
        }

        // Renderizar la vista de la página de departamentos
        $router->render('paginas/departamentos', [
            'titulo' => 'Departamentos', // Título de la página
            'departamentos' => $departamentos // Departamentos para mostrar
        ]);
    }

    public static function error(Router $router) {
        // Renderizar la vista de la página de error
        $router->render('paginas/error', [
            'titulo' => 'Página no encontrada' // Título de la página
        ]);
    }
}

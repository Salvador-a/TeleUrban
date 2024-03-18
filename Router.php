<?php

// Define el espacio de nombres para la clase
namespace MVC;

// Define la clase Router
class Router
{
    // Define una propiedad para almacenar las rutas GET
    public array $getRoutes = [];
    // Define una propiedad para almacenar las rutas POST
    public array $postRoutes = [];

    // Define un método para agregar una ruta GET
    public function get($url, $fn)
    {
         // Asigna la función $fn a la ruta $url en las rutas GET
        $this->getRoutes[$url] = $fn;
    }

    // Define un método para agregar una ruta POST
    public function post($url, $fn)
    {
        // Asigna la función $fn a la ruta $url en las rutas POST
        $this->postRoutes[$url] = $fn;
    }

    // Define un método para comprobar las rutas
    public function comprobarRutas()
    {

        // Obtiene la URL actual
        $url_actual = $_SERVER['PATH_INFO'] ?? '/';
        
        // Obtiene el método de la solicitud HTTP (GET, POST, etc.)
        $method = $_SERVER['REQUEST_METHOD'];

        // Si el método es GET
        if ($method === 'GET') {
            // Busca la función correspondiente en las rutas GET
            $fn = $this->getRoutes[$url_actual] ?? null;
        } else {
            // Si el método no es GET, busca la función correspondiente en las rutas POST
            $fn = $this->postRoutes[$url_actual] ?? null;
        }

        // Si se encontró una función para la ruta
        if ( $fn ) {
            // Llama a la función con $this como argumento
            call_user_func($fn, $this);
        } else {
            // Si no se encontró una función, redirige a la página 404
           header('Location: /404');
        }
    }

    public function render($view, $datos = [])
    {
         // Para cada dato en $datos
        foreach ($datos as $key => $value) {
             // Crea una variable con el nombre de la clave y el valor del dato
            $$key = $value; 
        }

        // Inicia la captura de salida
        ob_start(); 

        // Incluye la vista
        include_once __DIR__ . "/views/$view.php";

         // Obtiene el contenido de la captura de salida y lo limpia
        $contenido = ob_get_clean(); // Limpia el Buffer

        // Utilizar el Layout declarado a la URL
        $url_actual = $_SERVER['PATH_INFO'] ?? '/';

        // Si la URL contiene '/admin'
        if (str_contains($url_actual, '/admin')) {

             // Incluye el layout de administrador
            include_once __DIR__ . '/views/admin-layout.php';
        } else {

            // Si no, incluye el layout normal
            include_once __DIR__ . '/views/layout.php';
        }

        
    }
}

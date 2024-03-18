<?php

// Función para depurar variables
function debuguear($variable) : string {
    echo "<pre>";
    var_dump($variable); // Imprime la variable
    echo "</pre>";
    exit; // Termina la ejecución del script
}

// Función para escapar caracteres especiales en una cadena para usarla en HTML
function s($html) : string {
    $s = htmlspecialchars($html); // Escapa los caracteres especiales
    return $s; // Retorna la cadena escapada
}

// Función para verificar si estamos en una página específica
function pagina_actual($path ) : bool {
    // Comprueba si el path actual contiene el path especificado
    return str_contains( $_SERVER['PATH_INFO'] ?? '/', $path  ) ? true : false;
}

// Función para verificar si el usuario está autenticado
function is_auth() : bool {
    if(!isset($_SESSION)) { // Si la sesión no está iniciada
        session_start(); // Inicia la sesión
    }
    // Comprueba si existe la variable de sesión 'nombre' y si no está vacía
    return isset($_SESSION['nombre']) && !empty($_SESSION);
}

// Función para verificar si el usuario es administrador
function is_admin() : bool {
    if(!isset($_SESSION)) { // Si la sesión no está iniciada
        session_start(); // Inicia la sesión
    }
    // Comprueba si existe la variable de sesión 'admin' y si no está vacía
    return isset($_SESSION['admin']) && !empty($_SESSION['admin']);
}

// Función para generar una animación AOS aleatoria
function aos_animacion() : void	{
   
    $efectos = [
        'fade-up',
        'fade-down',
        'fade-left',
        'fade-right',
        'flip-left',
        'flip-right',
        'flip-up',
        'zoom-in',
        'zoom-in-up',
        'zoom-in-down',
        'zoom-out',
    ];

    $efecto = array_rand($efectos, 1); // Selecciona un efecto aleatorio
    echo ' data-aos="' . $efectos[$efecto] . '" '; // Imprime el atributo data-aos con el efecto seleccionado

}
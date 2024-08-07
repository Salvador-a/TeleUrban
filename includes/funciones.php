<?php

function debuguear($variable) : string {
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}

function s($html) : string {
    return htmlspecialchars($html ?? '', ENT_QUOTES, 'UTF-8');
}


function pagina_actual($path) : bool {
    return str_contains($_SERVER['PATH_INFO'] ?? '/', $path) ? true : false;
}

function is_auth() : bool {
    if (!isset($_SESSION)) {
        session_start();
    }
    return isset($_SESSION['nombre']) && !empty($_SESSION);
}

function is_admin() : bool {
    if (!isset($_SESSION)) {
        session_start();
    }
    return isset($_SESSION['rol']) && $_SESSION['rol'] == 1;
}

function is_jefe() : bool {
    if (!isset($_SESSION)) {
        session_start();
    }
    return isset($_SESSION['rol']) && $_SESSION['rol'] == 2;
}

function is_trabajador() : bool {
    if (!isset($_SESSION)) {
        session_start();
    }
    return isset($_SESSION['rol']) && $_SESSION['rol'] == 0;
}

function aos_animacion() : void {
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

    $efecto = array_rand($efectos, 1);
    echo ' data-aos="' . $efectos[$efecto] . '" ';
}

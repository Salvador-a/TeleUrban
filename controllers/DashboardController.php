<?php

namespace Controllers;

use MVC\Router;
use Model\Entrevista;

class DashboardController {
    /**
     * Maneja la vista del panel de control.
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

        $user_role = ''; // Inicializa la variable del rol del usuario
        $titulo = 'Panel de Usuario'; // Título predeterminado de la página
        $entrevistas = []; // Inicializa el array de entrevistas

        // Verifica el rol del usuario y ajusta la vista y los datos en consecuencia
        if (is_admin()) {
            $user_role = 'admin'; // Rol de administrador
            $titulo = 'Panel de Administración'; // Título para el administrador
            $entrevistas = Entrevista::all(); // Obtiene todas las entrevistas
        } elseif (is_jefe()) {
            $user_role = 'jefe'; // Rol de jefe de área
            $titulo = 'Panel de Jefe de Área'; // Título para el jefe de área
            $departamento_id = $_SESSION['departamento_id']; // Obtiene el ID del departamento del jefe
            $entrevistas = Entrevista::where('departamento_id', $departamento_id); // Obtiene las entrevistas del departamento
        } elseif (is_trabajador()) {
            $user_role = 'trabajador'; // Rol de trabajador
            $titulo = 'Panel de Trabajador'; // Título para el trabajador
            $departamento_id = $_SESSION['departamento_id']; // Obtiene el ID del departamento del trabajador
            $entrevistas = Entrevista::where('departamento_id', $departamento_id); // Obtiene las entrevistas del departamento
        } else {
            header('Location: /404'); // Redirecciona a la página 404 si el rol no es válido
            return;
        }

        // Datos para la gráfica de postulantes por área
        $postulantesPorArea = [];
        foreach ($entrevistas as $entrevista) {
            $area = $entrevista->obtenerDepartamento(); // Obtiene el nombre del departamento
            if (!isset($postulantesPorArea[$area])) {
                $postulantesPorArea[$area] = 0;
            }
            $postulantesPorArea[$area]++;
        }

        // Datos para la gráfica de citas por fecha
        $citasPorFecha = [];
        foreach ($entrevistas as $entrevista) {
            $fecha = date('Y-m-d', strtotime($entrevista->fecha_hora)); // Formatea la fecha de la entrevista
            if (!isset($citasPorFecha[$fecha])) {
                $citasPorFecha[$fecha] = 0;
            }
            $citasPorFecha[$fecha]++;
        }

        // Datos para la gráfica de estado de las entrevistas
        $estadoEntrevistas = [
            'Pendiente' => 0,
            'Aceptado' => 0,
            'Rechazado' => 0
        ];
        foreach ($entrevistas as $entrevista) {
            $estatus = $entrevista->obtenerEstatus(); // Obtiene el estatus de la entrevista
            if (isset($estadoEntrevistas[$estatus])) {
                $estadoEntrevistas[$estatus]++;
            }
        }

        // Renderiza la vista del panel de control
        $router->render('admin/dashboard/index', [
            'titulo' => $titulo, // Título de la página
            'user_role' => $user_role, // Rol del usuario
            'postulantesPorArea' => $postulantesPorArea, // Datos para la gráfica de postulantes por área
            'citasPorFecha' => $citasPorFecha, // Datos para la gráfica de citas por fecha
            'estadoEntrevistas' => $estadoEntrevistas // Datos para la gráfica de estado de las entrevistas
        ]);
    }
}

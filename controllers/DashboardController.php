<?php

namespace Controllers;

use MVC\Router;
use Model\Entrevista;

class DashboardController {
    public static function index(Router $router) {
        session_start();

        if (!is_auth()) {
            header('Location: /login');
            return;
        }

        $user_role = '';
        $titulo = 'Panel de Usuario';
        $entrevistas = [];

        if (is_admin()) {
            $user_role = 'admin';
            $titulo = 'Panel de Administración';
            $entrevistas = Entrevista::all();
        } elseif (is_jefe()) {
            $user_role = 'jefe';
            $titulo = 'Panel de Jefe de Área';
            $departamento_id = $_SESSION['departamento_id'];
            $entrevistas = Entrevista::where('departamento_id', $departamento_id);
        } elseif (is_trabajador()) {
            $user_role = 'trabajador';
            $titulo = 'Panel de Trabajador';
            $departamento_id = $_SESSION['departamento_id'];
            $entrevistas = Entrevista::where('departamento_id', $departamento_id);
        } else {
            header('Location: /404');
            return;
        }

        // Datos para la gráfica de postulantes por área
        $postulantesPorArea = [];
        foreach ($entrevistas as $entrevista) {
            $area = $entrevista->obtenerDepartamento();
            if (!isset($postulantesPorArea[$area])) {
                $postulantesPorArea[$area] = 0;
            }
            $postulantesPorArea[$area]++;
        }

        // Datos para la gráfica de citas por fecha
        $citasPorFecha = [];
        foreach ($entrevistas as $entrevista) {
            $fecha = date('Y-m-d', strtotime($entrevista->fecha_hora));
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
            $estatus = $entrevista->obtenerEstatus();
            if (isset($estadoEntrevistas[$estatus])) {
                $estadoEntrevistas[$estatus]++;
            }
        }

        $router->render('admin/dashboard/index', [
            'titulo' => $titulo,
            'user_role' => $user_role,
            'postulantesPorArea' => $postulantesPorArea,
            'citasPorFecha' => $citasPorFecha,
            'estadoEntrevistas' => $estadoEntrevistas
        ]);
    }
}

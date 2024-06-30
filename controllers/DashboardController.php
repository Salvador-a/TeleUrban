<?php

namespace Controllers;

use MVC\Router;

class DashboardController {
    public static function index(Router $router) {
        session_start();

        if (!is_auth()) {
            header('Location: /login');
            return;
        }

        $user_role = '';
        $titulo = 'Panel de Usuario';

        if (is_admin()) {
            $user_role = 'admin';
            $titulo = 'Panel de Administración';
        } elseif (is_jefe()) {
            $user_role = 'jefe';
            $titulo = 'Panel de Jefe de Área';
        } elseif (is_trabajador()) {
            $user_role = 'trabajador';
            $titulo = 'Panel de Trabajador';
        } else {
            header('Location: /404');
            return;
        }

        $router->render('admin/dashboard/index', [
            'titulo' => $titulo,
            'user_role' => $user_role
        ]);
    }
}

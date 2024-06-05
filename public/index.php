<?php 

require_once __DIR__ . '/../includes/app.php';

use MVC\Router;
use Controllers\AuthController;

use Controllers\EventosController;
use Controllers\RegalosController;
use Controllers\DashboardController;
use Controllers\EmpleadosController;
use Controllers\RegistradosController;

$router = new Router();


// Login
$router->get('/login', [AuthController::class, 'login']);
$router->post('/login', [AuthController::class, 'login']);
$router->post('/logout', [AuthController::class, 'logout']);

// Crear Cuenta
$router->get('/registro', [AuthController::class, 'registro']);
$router->post('/registro', [AuthController::class, 'registro']);

// Formulario de olvide mi password
$router->get('/olvide', [AuthController::class, 'olvide']);
$router->post('/olvide', [AuthController::class, 'olvide']);

// Colocar el nuevo password
$router->get('/reestablecer', [AuthController::class, 'reestablecer']);
$router->post('/reestablecer', [AuthController::class, 'reestablecer']);

// Confirmación de Cuenta
$router->get('/mensaje', [AuthController::class, 'mensaje']);
$router->get('/confirmar-cuenta', [AuthController::class, 'confirmar']);

// Area de Administración
$router->get('/admin/dashboard', [DashboardController::class, 'index']);

// Area de Empleados
$router->get('/admin/empleados', [EmpleadosController::class, 'index']);
$router->get('/admin/empleados/crear', [EmpleadosController::class, 'crear']);
$router->post('/admin/empleados/crear', [EmpleadosController::class, 'crear']);
$router->get('/admin/empleados/editar', [EmpleadosController::class, 'editar']);
$router->post('/admin/empleados/editar', [EmpleadosController::class, 'editar']);
$router->post('/admin/empleados/eliminar', [EmpleadosController::class, 'eliminar']);


// Area de Eventos
$router->get('/admin/eventos', [EventosController::class, 'index']);

// Area de Registrados
$router->get('/admin/registrados', [RegistradosController::class, 'index']);

// Area de Regalos
$router->get('/admin/regalos', [RegalosController::class, 'index']);




$router->comprobarRutas();
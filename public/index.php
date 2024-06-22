<?php 

require_once __DIR__ . '/../includes/app.php';

use MVC\Router;
use Controllers\AuthController;
use Controllers\PaginasController;
use Controllers\DashboardController;
use Controllers\EmpleadosController;
use Controllers\DepartamentosController;
use Controllers\CitasController;

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

// Área de Administración
$router->get('/admin/dashboard', [DashboardController::class, 'index']);

// Área de Empleados
$router->get('/admin/empleados', [EmpleadosController::class, 'index']);
$router->get('/admin/empleados/crear', [EmpleadosController::class, 'crear']);
$router->post('/admin/empleados/crear', [EmpleadosController::class, 'crear']);
$router->get('/admin/empleados/editar', [EmpleadosController::class, 'editar']);
$router->post('/admin/empleados/editar', [EmpleadosController::class, 'editar']);
$router->post('/admin/empleados/eliminar', [EmpleadosController::class, 'eliminar']);

// Área de Departamentos
$router->get('/admin/departamentos', [DepartamentosController::class, 'index']);
$router->get('/admin/departamentos/crear', [DepartamentosController::class, 'crear']);
$router->post('/admin/departamentos/crear', [DepartamentosController::class, 'crear']);
$router->get('/admin/departamentos/editar', [DepartamentosController::class, 'editar']);
$router->post('/admin/departamentos/editar', [DepartamentosController::class, 'editar']);
$router->post('/admin/departamentos/eliminar', [DepartamentosController::class, 'eliminar']);
$router->post('/admin/departamentos/publicar', [DepartamentosController::class, 'publicar']);

// Nueva ruta para ver detalles del departamento
$router->get('/departamento', [DepartamentosController::class, 'detalle']);

// Área Pública 
$router->get('/', [PaginasController::class, 'index']);
$router->get('/teleurban', [PaginasController::class, 'nosotros']);
$router->get('/departamentos', [PaginasController::class, 'departamentos']);
$router->get('/departamento', [DepartamentosController::class, 'detalle']); // Nueva ruta para detalles de departamento
$router->get('/citas', [CitasController::class, 'crear']);
$router->post('/citas', [CitasController::class, 'crear']);
$router->get('/404', [PaginasController::class, 'error']);

// Área de Entrevistas (Admin)
$router->get('/admin/registrados', [EntrevistaController::class, 'index']);
$router->get('/admin/registrados/editar', [EntrevistaController::class, 'editar']);
$router->post('/admin/registrados/editar', [EntrevistaController::class, 'editar']);
$router->post('/admin/registrados/eliminar', [EntrevistaController::class, 'eliminar']);

$router->comprobarRutas();

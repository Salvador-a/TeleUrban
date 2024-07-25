<?php 

// Archivo: index.php en la carpeta public
require_once __DIR__ . '/../includes/app.php';

use MVC\Router;
use Controllers\AuthController;
use Controllers\CitasController;
use Controllers\PaginasController;
use Controllers\DashboardController;
use Controllers\EmpleadosController;
use Controllers\EntrevistaController;
use Controllers\DepartamentosController;
use Controllers\EditarCitasController;

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

// Área de Administración (incluye todos los roles)
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
$router->post('/admin/departamentos/despublicar', [DepartamentosController::class, 'despublicar']);
$router->post('/admin/departamentos/toggle-disponibilidad', [DepartamentosController::class, 'toggleDisponibilidad']); // Nueva ruta

// Área de Entrevistas
$router->post('/admin/registrados/aceptar', [EntrevistaController::class, 'aceptar']);
$router->post('/admin/registrados/rechazar', [EntrevistaController::class, 'rechazar']);
$router->get('/admin/registrados/cv', [EntrevistaController::class, 'cv']);
$router->get('/admin/registrados/ver', [EntrevistaController::class, 'verMasInformacion']); // Nueva ruta para ver información del postulante

// Área Pública 
$router->get('/', [PaginasController::class, 'index']);
$router->get('/teleurban', [PaginasController::class, 'nosotros']);
$router->get('/departamentos', [PaginasController::class, 'departamentos']);
$router->get('/departamento', [DepartamentosController::class, 'detalle']); // Nueva ruta para detalles de departamento
$router->get('/citas', [CitasController::class, 'crear']);
$router->post('/citas', [CitasController::class, 'crear']);
$router->post('/validar-fecha-hora', [CitasController::class, 'validarFechaHora']); // Ruta para validar fecha y hora
$router->get('/404', [PaginasController::class, 'error']);

// Nueva ruta para listar entrevistas con nombres en lugar de IDs
$router->get('/entrevistas', [CitasController::class, 'listar']);


// index.php en la carpeta public
$router->get('/modificar-cita', [EditarCitasController::class, 'editar']);
$router->post('/modificar-cita', [EditarCitasController::class, 'editar']);
$router->get('/cita-exito', [EditarCitasController::class, 'exito']);

// Nueva ruta para el login de citas
$router->get('/login-cita', [EditarCitasController::class, 'login']);
$router->post('/login-cita', [EditarCitasController::class, 'login']);


// Área de Entrevistas (Admin)
$router->get('/admin/registrados', [EntrevistaController::class, 'index']);
$router->get('/admin/registrados/editar', [EntrevistaController::class, 'editar']);
$router->post('/admin/registrados/editar', [EntrevistaController::class, 'editar']);
$router->post('/admin/registrados/eliminar', [EntrevistaController::class, 'eliminar']);

$router->comprobarRutas();

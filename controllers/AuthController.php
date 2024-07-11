<?php

namespace Controllers;

use Model\Usuario;
use Model\Genero;
use Model\Departamento;
use Model\PuestoTrabajo;
use MVC\Router;
use Classes\Email;

class AuthController {
    
    // Método para manejar el inicio de sesión de un usuario
    public static function login(Router $router) {
        $alertas = []; // Inicializa un array para alertas
        $usuario = new Usuario; // Crea una nueva instancia de Usuario

        // Verifica si el método de solicitud es POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = new Usuario($_POST); // Sincroniza los datos del formulario con la instancia de Usuario

            $alertas = $usuario->validarLogin(); // Valida el formulario de inicio de sesión

            // Si no hay alertas, continúa con el proceso de inicio de sesión
            if (empty($alertas)) {
                // Busca al usuario en la base de datos por su email
                $usuario = Usuario::where('email', $usuario->email)[0] ?? null;

                // Verifica si el usuario existe y está confirmado
                if (!$usuario || !$usuario->confirmado) {
                    Usuario::setAlerta('error', 'El Usuario No Existe o no está confirmado');
                } else {
                    // Verifica la contraseña
                    if (password_verify($_POST['password'], $usuario->password)) {
                        // Inicia la sesión
                        session_start();
                        $_SESSION['id'] = $usuario->id; // Guarda el ID del usuario en la sesión
                        $_SESSION['nombre'] = $usuario->nombre; // Guarda el nombre del usuario en la sesión
                        $_SESSION['apellido'] = $usuario->apellido; // Guarda el apellido del usuario en la sesión
                        $_SESSION['email'] = $usuario->email; // Guarda el email del usuario en la sesión
                        $_SESSION['rol'] = $usuario->rol; // Guarda el rol del usuario en la sesión
                        $_SESSION['departamento_id'] = $usuario->departamento_id; // Guarda el ID del departamento del usuario en la sesión

                        // Redirecciona según el rol del usuario
                        if ($usuario->rol == 1) {
                            header('Location: /admin/dashboard'); // Redirige al dashboard de admin
                        } elseif ($usuario->rol == 2) {
                            header('Location: /jefe/dashboard'); // Redirige al dashboard de jefe
                        } else {
                            header('Location: /trabajador/dashboard'); // Redirige al dashboard de trabajador
                        }
                        exit; // Termina el script para asegurarse de que no se ejecuten más líneas
                    } else {
                        Usuario::setAlerta('error', 'Password Incorrecto'); // Establece una alerta si la contraseña es incorrecta
                    }
                }
            }
        }

        $alertas = Usuario::getAlertas(); // Obtiene todas las alertas

        // Renderiza la vista de inicio de sesión
        $router->render('auth/login', [
            'titulo' => 'Iniciar Sesión', // Título de la página
            'usuario' => $usuario, // Usuario para el formulario
            'alertas' => $alertas // Alertas para mostrar
        ]);
    }

    // Método para manejar el cierre de sesión de un usuario
    public static function logout() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            session_start(); // Inicia la sesión
            $_SESSION = []; // Limpia las variables de sesión
            header('Location: /'); // Redirecciona a la página de inicio
        }
    }

    // Método para manejar el registro de un nuevo usuario
    public static function registro(Router $router) {
        $alertas = []; // Inicializa un array para alertas
        $usuario = new Usuario; // Crea una nueva instancia de Usuario

        // Obtiene los datos necesarios para el formulario de registro
        $generos = Genero::all(); // Obtiene todos los géneros
        $departamentos = Departamento::all(); // Obtiene todos los departamentos
        $puestos_trabajo = PuestoTrabajo::all(); // Obtiene todos los puestos de trabajo

        // Verifica si el método de solicitud es POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario->sincronizar($_POST); // Sincroniza los datos del formulario con la instancia de Usuario

            $alertas = $usuario->validarCuenta(); // Valida el formulario de registro

            // Si no hay alertas, continúa con el proceso de registro
            if (empty($alertas)) {
                $existeUsuario = Usuario::where('email', $usuario->email)[0] ?? null; // Busca si ya existe un usuario con ese email

                if ($existeUsuario) {
                    Usuario::setAlerta('error', 'El Usuario ya está registrado'); // Establece una alerta si el usuario ya está registrado
                    $alertas = Usuario::getAlertas(); // Obtiene todas las alertas
                } else {
                    $usuario->hashPassword(); // Hashea el password del usuario

                    unset($usuario->password2); // Elimina el campo password2

                    $usuario->crearToken(); // Genera un token para el usuario

                    // Asigna el rol según el puesto de trabajo
                    switch ($usuario->puesto_trabajo_id) {
                        case 1: // CEO
                            $usuario->rol = 1;
                            break;
                        case 2: // Gerente de área
                            $usuario->rol = 2;
                            break;
                        case 3: // Trabajador
                        default:
                            $usuario->rol = 0;
                            break;
                    }

                    $resultado = $usuario->guardar(); // Guarda el nuevo usuario

                    // Envía el email de confirmación
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarConfirmacion();

                    if ($resultado) {
                        header('Location: /mensaje'); // Redirecciona a la página de mensaje
                    }
                }
            }
        }

        // Renderiza la vista de registro
        $router->render('auth/registro', [
            'titulo' => 'Crea tu cuenta en TeleUrban', // Título de la página
            'usuario' => $usuario, // Usuario para el formulario
            'generos' => $generos, // Géneros para el formulario
            'departamentos' => $departamentos, // Departamentos para el formulario
            'puestos_trabajo' => $puestos_trabajo, // Puestos de trabajo para el formulario
            'alertas' => $alertas // Alertas para mostrar
        ]);
    }

    // Método para manejar el proceso de recuperación de contraseña
    public static function olvide(Router $router) {
        $alertas = []; // Inicializa un array para alertas

        // Verifica si el método de solicitud es POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = new Usuario($_POST); // Sincroniza los datos del formulario con la instancia de Usuario
            $alertas = $usuario->validarEmail(); // Valida el email

            // Si no hay alertas, continúa con el proceso de recuperación
            if (empty($alertas)) {
                $usuario = Usuario::where('email', $usuario->email)[0] ?? null; // Busca al usuario en la base de datos por su email

                if ($usuario && $usuario->confirmado) {
                    $usuario->crearToken(); // Genera un nuevo token
                    unset($usuario->password2); // Elimina el campo password2

                    $usuario->guardar(); // Guarda el usuario con el nuevo token

                    // Envía el email con instrucciones
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarInstrucciones();

                    $alertas['exito'][] = 'Hemos enviado las instrucciones a tu email'; // Establece una alerta de éxito
                } else {
                    $alertas['error'][] = 'El Usuario no existe o no está confirmado'; // Establece una alerta de error
                }
            }
        }

        // Renderiza la vista de recuperación de contraseña
        $router->render('auth/olvide', [
            'titulo' => 'Olvide mi Password', // Título de la página
            'alertas' => $alertas // Alertas para mostrar
        ]);
    }

    // Método para manejar el proceso de restablecimiento de contraseña
    public static function reestablecer(Router $router) {
        $token = s($_GET['token']); // Obtiene el token de la URL

        $token_valido = true; // Variable para determinar si el token es válido

        if (!$token) header('Location: /'); // Si no hay token, redirecciona a la página de inicio

        $usuario = Usuario::where('token', $token)[0] ?? null; // Busca al usuario en la base de datos por su token

        if (empty($usuario)) {
            Usuario::setAlerta('error', 'Token No Válido, intenta de nuevo'); // Establece una alerta de error si el token no es válido
            $token_valido = false; // Marca el token como no válido
        }

        // Verifica si el método de solicitud es POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario->sincronizar($_POST); // Sincroniza los datos del formulario con la instancia de Usuario

            $alertas = $usuario->validarPassword(); // Valida el nuevo password

            // Si no hay alertas, continúa con el proceso de restablecimiento
            if (empty($alertas)) {
                $usuario->hashPassword(); // Hashea el nuevo password

                $usuario->token = null; // Elimina el token

                $resultado = $usuario->guardar(); // Guarda el usuario con el nuevo password

                if ($resultado) {
                    header('Location: /login'); // Redirecciona a la página de login
                }
            }
        }

        $alertas = Usuario::getAlertas(); // Obtiene todas las alertas

        // Renderiza la vista de restablecimiento de contraseña
        $router->render('auth/reestablecer', [
            'titulo' => 'Reestablecer Password', // Título de la página
            'alertas' => $alertas, // Alertas para mostrar
            'token_valido' => $token_valido // Indica si el token es válido
        ]);
    }

    // Método para mostrar un mensaje de confirmación
    public static function mensaje(Router $router) {
        $router->render('auth/mensaje', [
            'titulo' => 'Cuenta Creada Exitosamente' // Título de la página
        ]);
    }

    // Método para confirmar la cuenta de un usuario mediante un token
    public static function confirmar(Router $router) {
        $token = s($_GET['token']); // Obtiene el token de la URL

        if (!$token) header('Location: /'); // Si no hay token, redirecciona a la página de inicio

        $usuario = Usuario::where('token', $token)[0] ?? null; // Busca al usuario en la base de datos por su token

        if (empty($usuario)) {
            Usuario::setAlerta('error', 'Token No Válido, la cuenta no se confirmó'); // Establece una alerta de error si el token no es válido
        } else {
            $usuario->confirmado = 1; // Marca la cuenta como confirmada
            $usuario->token = ''; // Elimina el token
            unset($usuario->password2); // Elimina el campo password2

            $usuario->guardar(); // Guarda el usuario con la cuenta confirmada

            Usuario::setAlerta('exito', 'Cuenta Comprobada éxitosamente'); // Establece una alerta de éxito
        }

        // Renderiza la vista de confirmación de cuenta
        $router->render('auth/confirmar', [
            'titulo' => 'Confirma tu cuenta en TeleUrban', // Título de la página
            'alertas' => Usuario::getAlertas() // Alertas para mostrar
        ]);
    }
}

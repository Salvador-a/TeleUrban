<?php

// Define el espacio de nombres para este controlador
namespace Controllers;

// Importa la clase Email del espacio de nombres Classes
use Classes\Email;

// Importa la clase Usuario del espacio de nombres Model
use Model\Usuario;

// Importa la clase Router del espacio de nombres MVC
use MVC\Router;

class AuthController {
    // Define un método estático llamado login que toma un objeto Router como argumento
    public static function login(Router $router) {

        // Inicializa un array vacío para almacenar las alertas
        $alertas = [];

        // Verifica si la solicitud HTTP es de tipo POST
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
    
            // Crea un nuevo objeto Usuario con los datos enviados en el POST
            $usuario = new Usuario($_POST);

            // Valida los datos de inicio de sesión y almacena las alertas, si las hay
            $alertas = $usuario->validarLogin();
            
            // Si no hay alertas, es decir, si los datos de inicio de sesión son válidos
            if(empty($alertas)) {
                // Busca un usuario en la base de datos que tenga el mismo correo electrónico
                $usuario = Usuario::where('email', $usuario->email);
                // Si el usuario no existe o no está confirmado
                if(!$usuario || !$usuario->confirmado ) {
                        // Establece una alerta de error
                    Usuario::setAlerta('error', 'El Usuario No Existe o no esta confirmado');
                } else {
                    // Si el usuario existe y su contraseña coincide
                    if( password_verify($_POST['password'], $usuario->password) ) {
                        
                        // Iniciar la sesión
                        session_start();    
                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre;
                        $_SESSION['apellido'] = $usuario->apellido;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['admin'] = $usuario->admin ?? null;

                        // Redirige al usuario a la página de administración si es administrador, 
                        // de lo contrario, a la página de finalización de registro
                        if($usuario->admin) {
                            header('Location: /admin/dashboard');
                        } else {
                            header('Location: /finalizar-registro');
                        }
                        
                    } else {
                         // Si la contraseña no coincide, establece una alerta de error
                        Usuario::setAlerta('error', 'Password Incorrecto');
                    }
                }
            }
        }
        // Obtiene las alertas
        $alertas = Usuario::getAlertas();
        
        // Renderiza la vista de inicio de sesión con el título y las alertas
        $router->render('auth/login', [
            'titulo' => 'Iniciar Sesión',
            'alertas' => $alertas
        ]);
    }

    public static function logout() {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            session_start();
            $_SESSION = [];
            header('Location: /');
        }
       
    }

    public static function registro(Router $router) {
        // Inicializa un array vacío para almacenar las alertas
        $alertas = [];
        // Crea un nuevo objeto Usuario
        $usuario = new Usuario;

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Sincroniza los datos del usuario con los datos enviados en el POST
            $usuario->sincronizar($_POST);
            // Valida los datos de la cuenta del usuario y almacena las alertas, si las hay
            $alertas = $usuario->validar_cuenta();

            // Si no hay alertas, es decir, si los datos de la cuenta son válidos
            if(empty($alertas)) {
                // Busca un usuario en la base de datos que tenga el mismo correo electrónico
                $existeUsuario = Usuario::where('email', $usuario->email);

                // Si el usuario ya existe
                if($existeUsuario) {
                    // Establece una alerta de error
                    Usuario::setAlerta('error', 'El Usuario ya esta registrado');
                    // Obtiene las alertas
                    $alertas = Usuario::getAlertas();
                } else {
                    // Hashea la contraseña del usuario
                    $usuario->hashPassword();
                    // Elimina la confirmación de la contraseña
                    unset($usuario->password2);
                    // Genera un token para el usuario
                    $usuario->crearToken();
                    // Crea un nuevo usuario en la base de datos
                    $resultado =  $usuario->guardar();
                    // Envía un correo electrónico de confirmación al usuario
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarConfirmacion();

                    // Si el usuario se creó con éxito
                    if($resultado) {
                        // Redirige al usuario a la página de mensajes
                        header('Location: /mensaje');
                    }
                }
            }
        }

        // Renderiza la vista 'auth/registro' utilizando el objeto $router
        $router->render('auth/registro', [
            // Establece el título de la página a 'Crea tu cuenta en TeleUrban'
            'titulo' => 'Crea tu cuenta en TeleUrban',
            // Pasa el objeto $usuario a la vista. Este objeto contiene los datos del usuario que se está registrando
            'usuario' => $usuario, 
            // Pasa el array $alertas a la vista. Este array contiene cualquier alerta que se haya generado durante el proceso de registro
            'alertas' => $alertas
        ]);
    }

    public static function olvide(Router $router) {
        $alertas = [];
        
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = new Usuario($_POST);
            $alertas = $usuario->validarEmail();

            if(empty($alertas)) {
                // Buscar el usuario
                $usuario = Usuario::where('email', $usuario->email);

                if($usuario && $usuario->confirmado) {

                    // Generar un nuevo token
                    $usuario->crearToken();
                    
                    unset($usuario->password2);

                    // Actualizar el usuario
                    $usuario->guardar();

                    // Enviar el email
                    $email = new Email( $usuario->email, $usuario->nombre, $usuario->token );
                    $email->enviarInstrucciones();


                    // Imprimir la alerta
                    // Usuario::setAlerta('exito', 'Hemos enviado las instrucciones a tu email');

                    $alertas['exito'][] = 'Hemos enviado las instrucciones a tu email';
                } else {
                 
                    // Usuario::setAlerta('error', 'El Usuario no existe o no esta confirmado');

                    $alertas['error'][] = 'El Usuario no existe o no esta confirmado';
                }
            }
        }


        // Muestra la vista
        $router->render('auth/olvide', [
            'titulo' => 'Olvide mi Password',
            'alertas' => $alertas
        ]);
    }

    public static function reestablecer(Router $router) {

        $token = s($_GET['token']);

        $token_valido = true;

        if(!$token) header('Location: /');

        // Identificar el usuario con este token
        $usuario = Usuario::where('token', $token);

        if(empty($usuario)) {
            Usuario::setAlerta('error', 'Token No Válido, intenta de nuevo');
            $token_valido = false;
        }


        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Añadir el nuevo password
            $usuario->sincronizar($_POST);

            // Validar el password
            $alertas = $usuario->validarPassword();

            if(empty($alertas)) {
                // Hashear el nuevo password
                $usuario->hashPassword();

                // Eliminar el Token
                $usuario->token = null;

                // Guardar el usuario en la BD
                $resultado = $usuario->guardar();

                // Redireccionar
                if($resultado) {
                    header('Location: /login');
                }
            }
        }

        $alertas = Usuario::getAlertas();
        
        // Muestra la vista
        $router->render('auth/reestablecer', [
            'titulo' => 'Reestablecer Password',
            'alertas' => $alertas,
            'token_valido' => $token_valido
        ]);
    }

    public static function mensaje(Router $router) {

        $router->render('auth/mensaje', [
            'titulo' => 'Cuenta Creada Exitosamente'
        ]);
    }

    public static function confirmar(Router $router) {
        
        $token = s($_GET['token']);

        if(!$token) header('Location: /');

        // Encontrar al usuario con este token
        $usuario = Usuario::where('token', $token);

        if(empty($usuario)) {
            // No se encontró un usuario con ese token
            Usuario::setAlerta('error', 'Token No Válido, la cuenta no se confirmó');
        } else {
            // Confirmar la cuenta
            $usuario->confirmado = 1;
            $usuario->token = '';
            unset($usuario->password2);
            
            // Guardar en la BD
            $usuario->guardar();

            Usuario::setAlerta('exito', 'Cuenta Comprobada éxitosamente');
        }

     

        $router->render('auth/confirmar', [
            'titulo' => 'Confirma tu cuenta TeleUrban',
            'alertas' => Usuario::getAlertas()
        ]);
    }
}
<?php

namespace Model;

// La clase Usuario extiende de ActiveRecord para heredar sus propiedades y métodos
class Usuario extends ActiveRecord {
    // La tabla en la base de datos que corresponde a esta clase
    protected static $tabla = 'usuarios';
     // Las columnas en la tabla de la base de datos que corresponden a las propiedades de esta clase
    protected static $columnasDB = ['id', 'nombre', 'apellido', 'email', 'password', 'confirmado', 'token', 'admin'];

    // Las propiedades de la clase, que corresponden a las columnas en la tabla de la base de datos
    public $id;
    public $nombre;
    public $apellido;
    public $email;
    public $password;
    public $password2; // Esta propiedad se usa para confirmar el password al registrarse o cambiar el password
    public $confirmado; // Esta propiedad indica si el usuario ha confirmado su email
    public $token; // Este token se usa para confirmar el email o restablecer el password
    public $admin; // Esta propiedad indica si el usuario es administrador

    // Estas propiedades se usan para cambiar el password
    public $password_actual;
    public $password_nuevo;

    
    public function __construct($args = [])
    {
        // Constructor de la clase Usuario. Inicializa las propiedades del objeto Usuario con los valores proporcionados en el array $args.
        // Si no se proporciona un valor para una propiedad en $args, se utiliza un valor predeterminado.

        $this->id = $args['id'] ?? null; // El ID del usuario. Si no se proporciona en $args, se inicializa a null.
        $this->nombre = $args['nombre'] ?? ''; // El nombre del usuario. Si no se proporciona en $args, se inicializa a una cadena vacía.
        $this->apellido = $args['apellido'] ?? ''; // El apellido del usuario. Si no se proporciona en $args, se inicializa a una cadena vacía.
        $this->email = $args['email'] ?? ''; // El email del usuario. Si no se proporciona en $args, se inicializa a una cadena vacía.
        $this->password = $args['password'] ?? ''; // El password del usuario. Si no se proporciona en $args, se inicializa a una cadena vacía.
        $this->password2 = $args['password2'] ?? ''; // El segundo password del usuario, utilizado para confirmar el password. Si no se proporciona en $args, se inicializa a una cadena vacía.
        $this->confirmado = $args['confirmado'] ?? 0; // Indica si el usuario ha confirmado su email. Si no se proporciona en $args, se inicializa a 0 (no confirmado).
        $this->token = $args['token'] ?? ''; // El token del usuario, utilizado para confirmar el email o restablecer el password. Si no se proporciona en $args, se inicializa a una cadena vacía.
        $this->admin = $args['admin'] ?? 0; // Indica si el usuario es administrador. Si no se proporciona en $args, se inicializa a una cadena vacía.
    }

    // Método para validar los datos de inicio de sesión del usuario
    public function validarLogin() {
        // Si no se proporcionó un email, agrega un error a las alertas
        if(!$this->email) {
            self::$alertas['error'][] = 'El Email del Usuario es Obligatorio';
        }
        // Si el email proporcionado no es válido, agrega un error a las alertas
        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas['error'][] = 'Email no válido';
        }
        // Si no se proporcionó una contraseña, agrega un error a las alertas
        if(!$this->password) {
            self::$alertas['error'][] = 'El Password no puede ir vacio';
        }
        // Devuelve las alertas
        return self::$alertas;
    }

    // Método para validar los datos de una nueva cuenta de usuario
    public function validar_cuenta() {
        // Si no se proporcionó un nombre, agrega un error a las alertas
        if(!$this->nombre) {
            self::$alertas['error'][] = 'El Nombre es Obligatorio';
        }
        // Si no se proporcionó un apellido, agrega un error a las alertas
        if(!$this->apellido) {
            self::$alertas['error'][] = 'El Apellido es Obligatorio';
        }
        // Si no se proporcionó un email, agrega un error a las alertas
        if(!$this->email) {
            self::$alertas['error'][] = 'El Email es Obligatorio';
        }
        // Si no se proporcionó una contraseña, agrega un error a las alertas
        if(!$this->password) {
            self::$alertas['error'][] = 'El Password no puede ir vacio';
        }
        // Si la contraseña proporcionada tiene menos de 6 caracteres, agrega un error a las alertas
        if(strlen($this->password) < 6) {
            self::$alertas['error'][] = 'El password debe contener al menos 6 caracteres';
        }
        // Si las contraseñas proporcionadas no coinciden, agrega un error a las alertas
        if($this->password !== $this->password2) {
            self::$alertas['error'][] = 'Los password son diferentes';
        }
        // Devuelve las alertas
        return self::$alertas;
    }

    // Método para validar un email
    public function validarEmail() {
        // Si no se proporcionó un email, agrega un error a las alertas
        if(!$this->email) {
            self::$alertas['error'][] = 'El Email es Obligatorio';
        }
        // Si el email proporcionado no es válido, agrega un error a las alertas
        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas['error'][] = 'Email no válido';
        }
        // Devuelve las alertas
        return self::$alertas;
    }

    // Método para validar el password
    public function validarPassword() {
        // Si no se proporcionó un password, agrega un error a las alertas
        if(!$this->password) {
            self::$alertas['error'][] = 'El Password no puede ir vacio';
        }
        // Si el password proporcionado tiene menos de 6 caracteres, agrega un error a las alertas
        if(strlen($this->password) < 6) {
            self::$alertas['error'][] = 'El password debe contener al menos 6 caracteres';
        }
        // Devuelve las alertas
        return self::$alertas;
    }

    // Método para validar un nuevo password
    public function nuevo_password() : array {
        // Si no se proporcionó un password actual, agrega un error a las alertas
        if(!$this->password_actual) {
            self::$alertas['error'][] = 'El Password Actual no puede ir vacio';
        }
        // Si no se proporcionó un nuevo password, agrega un error a las alertas
        if(!$this->password_nuevo) {
            self::$alertas['error'][] = 'El Password Nuevo no puede ir vacio';
        }
        // Si el nuevo password tiene menos de 6 caracteres, agrega un error a las alertas
        if(strlen($this->password_nuevo) < 6) {
            self::$alertas['error'][] = 'El Password debe contener al menos 6 caracteres';
        }
        // Devuelve las alertas
        return self::$alertas;
    }
   
    // Método para comprobar el password
    public function comprobar_password() : bool {
        // Comprueba si el password actual coincide con el password hasheado almacenado
        // Devuelve true si coincide, false en caso contrario
        return password_verify($this->password_actual, $this->password );
    }

    // Método para hashear el password
    public function hashPassword() : void {
        // Hashea el password utilizando el algoritmo BCRYPT y lo almacena en la propiedad password
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    // Método para generar un token
    public function crearToken() : void {
        // Genera un token único y lo almacena en la propiedad token
        $this->token = uniqid();
    }
}
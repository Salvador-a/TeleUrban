<?php

namespace Model;

class Usuario extends ActiveRecord {
    protected static $tabla = 'usuarios'; // Define la tabla asociada al modelo
    protected static $columnasDB = ['id', 'nombre', 'a_paterno', 'a_materno', 'email', 'password', 'telefono', 'genero_id', 'departamento_id', 'puesto_trabajo_id', 'confirmado', 'token', 'rol']; // Define las columnas de la tabla

    public $id;
    public $nombre;
    public $a_paterno;
    public $a_materno;
    public $email;
    public $password;
    public $telefono;
    public $genero_id;
    public $departamento_id;
    public $puesto_trabajo_id;
    public $confirmado;
    public $token;
    public $rol;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null; // Asigna el ID
        $this->nombre = $args['nombre'] ?? ''; // Asigna el nombre
        $this->a_paterno = $args['a_paterno'] ?? ''; // Asigna el apellido paterno
        $this->a_materno = $args['a_materno'] ?? ''; // Asigna el apellido materno
        $this->email = $args['email'] ?? ''; // Asigna el email
        $this->password = $args['password'] ?? ''; // Asigna la contraseña
        $this->telefono = $args['telefono'] ?? ''; // Asigna el teléfono
        $this->genero_id = $args['genero_id'] ?? null; // Asigna el ID del género
        $this->departamento_id = $args['departamento_id'] ?? null; // Asigna el ID del departamento
        $this->puesto_trabajo_id = $args['puesto_trabajo_id'] ?? null; // Asigna el ID del puesto de trabajo
        $this->confirmado = $args['confirmado'] ?? 0; // Asigna el estado de confirmado
        $this->token = $args['token'] ?? ''; // Asigna el token
        $this->rol = $args['rol'] ?? 0; // Asigna el rol
    }

    public function validarCuenta() {
        if (!$this->nombre) {
            self::$alertas['error'][] = 'El Nombre es Obligatorio'; // Valida el nombre
        }
        if (!$this->a_paterno) {
            self::$alertas['error'][] = 'El Apellido Paterno es Obligatorio'; // Valida el apellido paterno
        }
        if (!$this->a_materno) {
            self::$alertas['error'][] = 'El Apellido Materno es Obligatorio'; // Valida el apellido materno
        }
        if (!$this->email) {
            self::$alertas['error'][] = 'El Email es Obligatorio'; // Valida el email
        }
        if (!$this->password) {
            self::$alertas['error'][] = 'El Password es Obligatorio'; // Valida la contraseña
        }
        if (strlen($this->password) < 6) {
            self::$alertas['error'][] = 'El Password debe contener al menos 6 caracteres'; // Valida la longitud de la contraseña
        }
        return self::$alertas; // Retorna las alertas
    }

    public function validarLogin() {
        if (!$this->email) {
            self::$alertas['error'][] = 'El Email es Obligatorio'; // Valida el email
        }
        if (!$this->password) {
            self::$alertas['error'][] = 'El Password es Obligatorio'; // Valida la contraseña
        }
        return self::$alertas; // Retorna las alertas
    }

    public function validarEmail() {
        if (!$this->email) {
            self::$alertas['error'][] = 'El Email es Obligatorio'; // Valida el email
        }
        return self::$alertas; // Retorna las alertas
    }

    public function validarPassword() {
        if (!$this->password) {
            self::$alertas['error'][] = 'El Password es Obligatorio'; // Valida la contraseña
        }
        if (strlen($this->password) < 6) {
            self::$alertas['error'][] = 'El Password debe contener al menos 6 caracteres'; // Valida la longitud de la contraseña
        }
        return self::$alertas; // Retorna las alertas
    }

    public function hashPassword() {
        $this->password = password_hash($this->password, PASSWORD_BCRYPT); // Hashea la contraseña
    }

    public function crearToken() {
        $this->token = bin2hex(random_bytes(13)); // Token de 26 caracteres // Crea un nuevo token
    }

    public function comprobarPasswordAndVerificado($password) {
        $resultado = password_verify($password, $this->password); // Verifica la contraseña
        if(!$resultado || !$this->confirmado) {
            self::$alertas['error'][] = 'Password incorrecto o tu cuenta no ha sido confirmada'; // Añade una alerta si la contraseña es incorrecta o la cuenta no está confirmada
        } else {
            return true; // Retorna verdadero si la contraseña es correcta y la cuenta está confirmada
        }
    }
}

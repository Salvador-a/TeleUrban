<?php

namespace Model;

class Usuario extends ActiveRecord {
    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id', 'nombre', 'a_paterno', 'a_materno', 'email', 'password', 'telefono', 'genero_id', 'departamento_id', 'puesto_trabajo_id', 'confirmado', 'token', 'rol'];

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
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->a_paterno = $args['a_paterno'] ?? '';
        $this->a_materno = $args['a_materno'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->telefono = $args['telefono'] ?? '';
        $this->genero_id = $args['genero_id'] ?? null;
        $this->departamento_id = $args['departamento_id'] ?? null;
        $this->puesto_trabajo_id = $args['puesto_trabajo_id'] ?? null;
        $this->confirmado = $args['confirmado'] ?? 0;
        $this->token = $args['token'] ?? '';
        $this->rol = $args['rol'] ?? 0;
    }

    public function validarCuenta() {
        if (!$this->nombre) {
            self::$alertas['error'][] = 'El Nombre es Obligatorio';
        }
        if (!$this->a_paterno) {
            self::$alertas['error'][] = 'El Apellido Paterno es Obligatorio';
        }
        if (!$this->a_materno) {
            self::$alertas['error'][] = 'El Apellido Materno es Obligatorio';
        }
        if (!$this->email) {
            self::$alertas['error'][] = 'El Email es Obligatorio';
        }
        if (!$this->password) {
            self::$alertas['error'][] = 'El Password es Obligatorio';
        }
        if (strlen($this->password) < 6) {
            self::$alertas['error'][] = 'El Password debe contener al menos 6 caracteres';
        }
        return self::$alertas;
    }

    public function validarLogin() {
        if (!$this->email) {
            self::$alertas['error'][] = 'El Email es Obligatorio';
        }
        if (!$this->password) {
            self::$alertas['error'][] = 'El Password es Obligatorio';
        }
        return self::$alertas;
    }

    public function validarEmail() {
        if (!$this->email) {
            self::$alertas['error'][] = 'El Email es Obligatorio';
        }
        return self::$alertas;
    }

    public function validarPassword() {
        if (!$this->password) {
            self::$alertas['error'][] = 'El Password es Obligatorio';
        }
        if (strlen($this->password) < 6) {
            self::$alertas['error'][] = 'El Password debe contener al menos 6 caracteres';
        }
        return self::$alertas;
    }

    public function hashPassword() {
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    public function crearToken() {
        $this->token = bin2hex(random_bytes(13)); // Token de 26 caracteres
    }

    public function comprobarPasswordAndVerificado($password) {
        $resultado = password_verify($password, $this->password);
        if(!$resultado || !$this->confirmado) {
            self::$alertas['error'][] = 'Password incorrecto o tu cuenta no ha sido confirmada';
        } else {
            return true;
        }
    }
}

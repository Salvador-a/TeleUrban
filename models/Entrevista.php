<?php

namespace Model;

class Entrevista extends ActiveRecord {
    protected static $tabla = 'entrevistas'; // Define la tabla asociada al modelo
    protected static $columnasDB = [
        'id', 'nombre', 'a_paterno', 'a_materno', 'email', 'telefono',
        'discapacidad_id', 'genero_id', 'semestre_id', 'universidad_id',
        'curriculum', 'fecha_hora', 'departamento_id', 'modalidad_id', 
        'tags', 'habilidades', 'token', 'token_expiracion', 'estatus_id',
        'usos_token'  // Nuevo campo agregado
    ]; // Define las columnas de la tabla

    public $id;
    public $nombre;
    public $a_paterno;
    public $a_materno;
    public $email;
    public $telefono;
    public $discapacidad_id;
    public $genero_id;
    public $semestre_id;
    public $universidad_id;
    public $curriculum;
    public $fecha_hora;
    public $departamento_id;
    public $modalidad_id;
    public $tags;
    public $habilidades;
    public $token;
    public $token_expiracion;
    public $estatus_id;
    public $usos_token; 

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null; // Asigna el ID
        $this->nombre = $args['nombre'] ?? ''; // Asigna el nombre
        $this->a_paterno = $args['a_paterno'] ?? ''; // Asigna el apellido paterno
        $this->a_materno = $args['a_materno'] ?? ''; // Asigna el apellido materno
        $this->email = $args['email'] ?? ''; // Asigna el email
        $this->telefono = $args['telefono'] ?? ''; // Asigna el teléfono
        $this->fecha_hora = $args['fecha_hora'] ?? ''; // Asigna la fecha y hora
        $this->discapacidad_id = $args['discapacidad_id'] ?? ''; // Asigna la discapacidad
        $this->genero_id = $args['genero_id'] ?? ''; // Asigna el género
        $this->semestre_id = $args['semestre_id'] ?? ''; // Asigna el semestre
        $this->universidad_id = $args['universidad_id'] ?? ''; // Asigna la universidad
        $this->curriculum = $args['curriculum'] ?? ''; // Asigna el curriculum
        $this->departamento_id = $args['departamento_id'] ?? ''; // Asigna el departamento
        $this->modalidad_id = $args['modalidad_id'] ?? ''; // Asigna la modalidad
        $this->tags = $args['tags'] ?? ''; // Asigna las tags
        $this->habilidades = $args['habilidades'] ?? ''; // Asigna las habilidades
        $this->token = $args['token'] ?? ''; // Asigna el token
        $this->token_expiracion = $args['token_expiracion'] ?? ''; // Asigna la expiración del token
        $this->estatus_id = $args['estatus_id'] ?? null; // Asigna el estatus
        $this->usos_token = $args['usos_token'] ?? 0; // Inicializa el contador de usos del token
    }

    // Verifica si el token es válido (menos de 2 usos)
    public function tokenValido() {
        return $this->usos_token < 2;
    }

    // Incrementa el uso del token
    public function incrementarUsoToken() {
        $this->usos_token += 1;
        return $this->guardar();
    }

    public function obtenerUniversidad() {
        return self::obtenerNombrePorId('universidad', $this->universidad_id); // Retorna el nombre de la universidad
    }

    public function obtenerSemestre() {
        return self::obtenerNombrePorId('semestre', $this->semestre_id, 'grado'); // Retorna el grado del semestre
    }

    public function obtenerDepartamento() {
        return self::obtenerNombrePorId('departamentos', $this->departamento_id, 'nombre_departamento'); // Retorna el nombre del departamento
    }

    public function obtenerModalidad() {
        return self::obtenerNombrePorId('descripcion', $this->modalidad_id); // Retorna la modalidad
    }

    public function obtenerDiscapacidad() {
        return self::obtenerNombrePorId('discapacidad', $this->discapacidad_id); // Retorna la discapacidad
    }

    public function obtenerGenero() {
        return self::obtenerNombrePorId('genero', $this->genero_id); // Retorna el género
    }

    public function obtenerEstatus() {
        return self::obtenerNombrePorId('status', $this->estatus_id); // Retorna el estatus
    }

    public function validar() {
        if (!$this->nombre) {
            self::setAlerta('error', 'El nombre es obligatorio'); // Valida el nombre
        }
        if (!$this->a_paterno) {
            self::setAlerta('error', 'El apellido paterno es obligatorio'); // Valida el apellido paterno
        }
        if (!$this->a_materno) {
            self::setAlerta('error', 'El apellido materno es obligatorio'); // Valida el apellido materno
        }
        if (!$this->email) {
            self::setAlerta('error', 'El email es obligatorio'); // Valida el email
        }
        if (!$this->telefono) {
            self::setAlerta('error', 'El teléfono es obligatorio'); // Valida el teléfono
        }
        if (!$this->fecha_hora) {
            self::setAlerta('error', 'La fecha y hora de la entrevista son obligatorias'); // Valida la fecha y hora
        }
        if (!$this->discapacidad_id) {
            self::setAlerta('error', 'La discapacidad es obligatoria'); // Valida la discapacidad
        }
        if (!$this->genero_id) {
            self::setAlerta('error', 'El género es obligatorio'); // Valida el género
        }
        if (!$this->semestre_id) {
            self::setAlerta('error', 'El semestre es obligatorio'); // Valida el semestre
        }
        if (!$this->universidad_id) {
            self::setAlerta('error', 'La universidad es obligatoria'); // Valida la universidad
        }
        if (!$this->departamento_id) {
            self::setAlerta('error', 'El área es obligatoria'); // Valida el área
        }
        if (!$this->modalidad_id) {
            self::setAlerta('error', 'La modalidad es obligatoria'); // Valida la modalidad
        }
        if (!$this->habilidades) {
            self::setAlerta('error', 'Las habilidades son obligatorias'); // Valida las habilidades
        }

        if (!$this->tags) {
            self::setAlerta('error', 'El Campo Áreas de Experiencia es obligatorio'); // Valida las áreas de experiencia
        }

        return self::getAlertas(); // Retorna las alertas
    }

    public static function eliminarTokensExpirados() {
        $fechaActual = date('Y-m-d H:i:s'); // Obtiene la fecha actual
        $sql = "DELETE FROM " . self::$tabla . " WHERE token_expiracion < '$fechaActual'"; // Crea la consulta SQL para eliminar tokens expirados
        self::ejecutarSQL($sql); // Ejecuta la consulta SQL
    }

    public static function ejecutarSQL($sql) {
        $resultado = self::$db->query($sql); // Ejecuta la consulta SQL
        return $resultado; // Retorna el resultado de la consulta
    }

    
}

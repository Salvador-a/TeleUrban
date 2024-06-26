<?php

namespace Model;

class Entrevista extends ActiveRecord {
    protected static $tabla = 'entrevistas';
    protected static $columnasDB = [
        'id', 'nombre', 'a_paterno', 'a_materno', 'email', 'telefono',
        'discapacidad_id', 'genero_id', 'semestre_id', 'universidad_id',
        'curriculum', 'fecha_hora', 'departamento_id', 'modalidad_id',
        'habilidades', 'token', 'token_expiracion', 'estatus_id'
    ];

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
    public $habilidades;
    public $token;
    public $token_expiracion;
    public $estatus_id;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->a_paterno = $args['a_paterno'] ?? '';
        $this->a_materno = $args['a_materno'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->telefono = $args['telefono'] ?? '';
        $this->fecha_hora = $args['fecha_hora'] ?? '';
        $this->discapacidad_id = $args['discapacidad_id'] ?? '';
        $this->genero_id = $args['genero_id'] ?? '';
        $this->semestre_id = $args['semestre_id'] ?? '';
        $this->universidad_id = $args['universidad_id'] ?? '';
        $this->curriculum = $args['curriculum'] ?? '';
        $this->departamento_id = $args['departamento_id'] ?? '';
        $this->modalidad_id = $args['modalidad_id'] ?? '';
        $this->habilidades = $args['habilidades'] ?? '';
        $this->token = $args['token'] ?? '';
        $this->token_expiracion = $args['token_expiracion'] ?? '';
        $this->estatus_id = $args['estatus_id'] ?? null;
    }

    public function obtenerUniversidad() {
        return self::obtenerNombrePorId('universidad', $this->universidad_id);
    }

    public function obtenerSemestre() {
        return self::obtenerNombrePorId('semestre', $this->semestre_id, 'grado');
    }

    public function obtenerDepartamento() {
        return self::obtenerNombrePorId('departamentos', $this->departamento_id, 'nombre_departamento');
    }

    public function obtenerModalidad() {
        return self::obtenerNombrePorId('descripcion', $this->modalidad_id);
    }

    public function obtenerDiscapacidad() {
        return self::obtenerNombrePorId('discapacidad', $this->discapacidad_id);
    }

    public function obtenerGenero() {
        return self::obtenerNombrePorId('genero', $this->genero_id);
    }

    public function obtenerEstatus() {
        return self::obtenerNombrePorId('status', $this->estatus_id);
    }

    public function validar() {
        if (!$this->nombre) {
            self::setAlerta('error', 'El nombre es obligatorio');
        }
        if (!$this->a_paterno) {
            self::setAlerta('error', 'El apellido paterno es obligatorio');
        }
        if (!$this->a_materno) {
            self::setAlerta('error', 'El apellido materno es obligatorio');
        }
        if (!$this->email) {
            self::setAlerta('error', 'El email es obligatorio');
        }
        if (!$this->telefono) {
            self::setAlerta('error', 'El teléfono es obligatorio');
        }
        if (!$this->fecha_hora) {
            self::setAlerta('error', 'La fecha y hora de la entrevista son obligatorias');
        }
        if (!$this->discapacidad_id) {
            self::setAlerta('error', 'La discapacidad es obligatoria');
        }
        if (!$this->genero_id) {
            self::setAlerta('error', 'El género es obligatorio');
        }
        if (!$this->semestre_id) {
            self::setAlerta('error', 'El semestre es obligatorio');
        }
        if (!$this->universidad_id) {
            self::setAlerta('error', 'La universidad es obligatoria');
        }
        if (!$this->departamento_id) {
            self::setAlerta('error', 'El área es obligatoria');
        }
        if (!$this->modalidad_id) {
            self::setAlerta('error', 'La modalidad es obligatoria');
        }
        if (!$this->habilidades) {
            self::setAlerta('error', 'Las habilidades son obligatorias');
        }

        return self::getAlertas();
    }

    public static function eliminarTokensExpirados() {
        $fechaActual = date('Y-m-d H:i:s');
        $sql = "DELETE FROM " . self::$tabla . " WHERE token_expiracion < '$fechaActual'";
        self::ejecutarSQL($sql);
    }

    public static function ejecutarSQL($sql) {
        $resultado = self::$db->query($sql);
        return $resultado;
    }
}

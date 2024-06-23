<?php

namespace Model;

class Entrevista extends ActiveRecord {
    protected static $tabla = 'entrevistas';
    protected static $columnasDB = [
        'id', 
        'nombre', 
        'a_paterno', 
        'a_materno', 
        'email', 
        'telefono', 
        'discapacidad_id', 
        'genero_id', 
        'semestre_id', 
        'universidad_id', 
        'curriculum', 
        'fecha_hora', 
        'area_id', 
        'modalidad_id'
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
    public $area_id;
    public $modalidad_id;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->a_paterno = $args['a_paterno'] ?? '';
        $this->a_materno = $args['a_materno'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->telefono = $args['telefono'] ?? '';
        $this->discapacidad_id = $args['discapacidad_id'] ?? null;
        $this->genero_id = $args['genero_id'] ?? null;
        $this->semestre_id = $args['semestre_id'] ?? null;
        $this->universidad_id = $args['universidad_id'] ?? null;
        $this->curriculum = $args['curriculum'] ?? null;
        $this->fecha_hora = $args['fecha_hora'] ?? '';
        $this->area_id = $args['area_id'] ?? null;
        $this->modalidad_id = $args['modalidad_id'] ?? null;
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

        return self::getAlertas();
    }
}

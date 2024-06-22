<?php

namespace Model;

class Entrevista extends ActiveRecord {
    protected static $tabla = 'entrevistas';
    protected static $columnasDB = ['id', 'nombre', 'a_paterno', 'a_materno', 'email', 'telefono', 'fecha_hora', 'discapacidad_id', 'genero_id', 'semestre_id', 'universidad_id', 'curriculum', 'area_id', 'modalidad_id'];

    public $id;
    public $nombre;
    public $a_paterno;
    public $a_materno;
    public $email;
    public $telefono;
    public $fecha_hora;
    public $discapacidad_id;
    public $genero_id;
    public $semestre_id;
    public $universidad_id;
    public $curriculum;
    public $area_id;
    public $modalidad_id;

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
        $this->area_id = $args['area_id'] ?? '';
        $this->modalidad_id = $args['modalidad_id'] ?? '';
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
        if (!$this->area_id) {
            self::setAlerta('error', 'El área es obligatoria');
        }
        if (!$this->modalidad_id) {
            self::setAlerta('error', 'La modalidad es obligatoria');
        }
    
        return self::getAlertas();
    }    
    
}

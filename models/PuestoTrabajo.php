<?php

namespace Model;

class PuestoTrabajo extends ActiveRecord {
    protected static $tabla = 'puestos_trabajo';
    protected static $columnasDB = ['id', 'nombre_puesto', 'rol'];

    public $id;
    public $nombre_puesto;
    public $rol;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->nombre_puesto = $args['nombre_puesto'] ?? '';
        $this->rol = $args['rol'] ?? '';
    }
}

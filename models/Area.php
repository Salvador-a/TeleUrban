<?php

namespace Model;

class Area extends ActiveRecord {
    protected static $tabla = 'area';
    protected static $columnasDB = ['id', 'nombre', 'id_disponibilidad'];

    public $id;
    public $nombre;
    public $id_disponibilidad;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->id_disponibilidad = $args['id_disponibilidad'] ?? null;
    }
}

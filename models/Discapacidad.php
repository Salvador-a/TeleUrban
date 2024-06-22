<?php

namespace Model;

class Discapacidad extends ActiveRecord {
    protected static $tabla = 'discapacidad';
    protected static $columnasDB = ['id', 'nombre'];

    public $id;
    public $nombre;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
    }
}

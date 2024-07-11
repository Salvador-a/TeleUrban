<?php

namespace Model;

class Discapacidad extends ActiveRecord {
    protected static $tabla = 'discapacidad'; // Define la tabla asociada al modelo
    protected static $columnasDB = ['id', 'nombre']; // Define las columnas de la tabla

    public $id;
    public $nombre;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null; // Asigna el ID
        $this->nombre = $args['nombre'] ?? ''; // Asigna el nombre
    }
}

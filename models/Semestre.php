<?php

namespace Model;

class Semestre extends ActiveRecord {
    protected static $tabla = 'semestre'; // Define la tabla asociada al modelo
    protected static $columnasDB = ['id', 'grado']; // Define las columnas de la tabla

    public $id;
    public $grado;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null; // Asigna el ID
        $this->grado = $args['grado'] ?? ''; // Asigna el grado
    }
}

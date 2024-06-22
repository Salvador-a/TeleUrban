<?php

namespace Model;

class Semestre extends ActiveRecord {
    protected static $tabla = 'semestre';
    protected static $columnasDB = ['id', 'grado'];

    public $id;
    public $grado;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->grado = $args['grado'] ?? '';
    }
}

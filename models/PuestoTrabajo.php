<?php

namespace Model;

class PuestoTrabajo extends ActiveRecord {
    protected static $tabla = 'puestos_trabajo'; // Define la tabla asociada al modelo
    protected static $columnasDB = ['id', 'nombre_puesto', 'rol']; // Define las columnas de la tabla

    public $id;
    public $nombre_puesto;
    public $rol;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null; // Asigna el ID
        $this->nombre_puesto = $args['nombre_puesto'] ?? ''; // Asigna el nombre del puesto
        $this->rol = $args['rol'] ?? ''; // Asigna el rol
    }
}

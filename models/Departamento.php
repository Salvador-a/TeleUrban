<?php

namespace Model;

class Departamento extends ActiveRecord {
    protected static $tabla = 'departamentos';
    protected static $columnasDB = ['id', 'nombre_departamento', 'id_encargado', 'descripcion', 'imagen'];

    public $id;
    public $nombre_departamento;
    public $id_encargado;
    public $descripcion;
    public $imagen;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->nombre_departamento = $args['nombre_departamento'] ?? '';
        $this->id_encargado = $args['id_encargado'] ?? '';
        $this->descripcion = $args['descripcion'] ?? '';
        $this->imagen = $args['imagen'] ?? '';
    }

    public function validar() {
        if (!$this->nombre_departamento) {
            self::$alertas['error'][] = 'El Nombre del Departamento es Obligatorio';
        }
        if (!$this->id_encargado) {
            self::$alertas['error'][] = 'El Jefe del Departamento es Obligatorio';
        }
        if (!$this->descripcion) {
            self::$alertas['error'][] = 'La Descripción es Obligatoria';
        }
        if (!$this->imagen) {
            self::$alertas['error'][] = 'La Imagen es Obligatoria';
        }
        return self::$alertas;
    }
}

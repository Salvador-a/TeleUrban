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
            self::setAlerta('error', 'El nombre del departamento es obligatorio');
        }
        if (!$this->id_encargado) {
            self::setAlerta('error', 'El encargado del departamento es obligatorio');
        }
        if (!$this->descripcion) {
            self::setAlerta('error', 'La descripción es obligatoria');
        }
        if (!$this->imagen) {
            self::setAlerta('error', 'La imagen es obligatoria');
        }
        return self::getAlertas();
    }
}

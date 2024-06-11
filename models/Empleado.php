<?php

namespace Model;

class Empleado extends ActiveRecord {
    protected static $tabla = 'empleados';
    protected static $columnasDB = ['id', 'nombre', 'apellido', 'ciudad', 'pais', 'imagen', 'puesto_trabajo', 'tags', 'redes_sociales'];

    public $id;
    public $nombre;
    public $apellido;
    public $ciudad;
    public $pais;
    public $imagen;
    public $puesto_trabajo;
    public $tags;
    // public $descripcion_area_trabajo;
    public $redes_sociales;

    
    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->apellido = $args['apellido'] ?? '';
        $this->ciudad = $args['ciudad'] ?? '';
        $this->pais = $args['pais'] ?? '';
        $this->imagen = $args['imagen'] ?? '';
        $this->puesto_trabajo = $args['puesto_trabajo'] ?? '';
        $this->tags = $args['tags'] ?? '';
        // $this->descripcion_area_trabajo = $args['descripcion_area_trabajo'] ?? '';
        $this->redes_sociales = $args['redes_sociales'] ?? '';
    }

    
    public function validar() {
        if(!$this->nombre) {
            self::$alertas['error'][] = 'El Nombre es Obligatorio';
        }
        if(!$this->apellido) {
            self::$alertas['error'][] = 'El Apellido es Obligatorio';
        }
        if(!$this->ciudad) {
            self::$alertas['error'][] = 'El Campo Ciudad es Obligatorio';
        }
        if(!$this->pais) {
            self::$alertas['error'][] = 'El Campo País es Obligatorio';
        }
        if(!$this->imagen) {
            self::$alertas['error'][] = 'La imagen es obligatoria';
        }
         if(!$this->puesto_trabajo) {
            self::$alertas['error'][] = 'El Campo Puesto de Trabajo es Obligatorio';
         }
         if(!$this->tags) {
             self::$alertas['error'][] = 'El Campo Áreas de Experiencia es obligatorio';
         }
        //  if(!$this->descripcion_area_trabajo) {
        //      self::$alertas['error'][] = 'El Campo Descripción de Área de Trabajo es obligatorio';
        //  }
         if(!$this->redes_sociales) {
            self::$alertas['error'][] = 'El Campo Redes Sociales es obligatorio';
            }
    
        return self::$alertas;
    }
}

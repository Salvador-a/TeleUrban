<?php

namespace Model;

class Departamento extends ActiveRecord {
    protected static $tabla = 'departamentos';
    protected static $columnasDB = ['id', 'nombre_departamento', 'id_encargado', 'imagen', 'descripcion', 'publicado', 'disponible'];

    public $id;
    public $nombre_departamento;
    public $id_encargado;
    public $imagen;
    public $descripcion;
    public $publicado;
    public $disponible;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->nombre_departamento = $args['nombre_departamento'] ?? '';
        $this->id_encargado = $args['id_encargado'] ?? '';
        $this->imagen = $args['imagen'] ?? '';
        $this->descripcion = $args['descripcion'] ?? '';
        $this->publicado = $args['publicado'] ?? 0;
        $this->disponible = isset($args['disponible']) ? (int)$args['disponible'] : 1; // Valor predeterminado de 1
    }

    public function validar() {
        if (!$this->nombre_departamento) {
            self::setAlerta('error', 'El Nombre del Departamento es Obligatorio');
        }

        if (!$this->id_encargado) {
            self::setAlerta('error', 'El Encargado del Departamento es Obligatorio');
        }

        if (!$this->descripcion) {
            self::setAlerta('error', 'La Descripción es Obligatoria');
        }

        return self::$alertas;
    }

    public function setImagen($imagen) {
        // Elimina la imagen anterior si existe
        if (!is_null($this->id)) {
            $this->borrarImagen();
        }

        // Asigna al atributo de imagen el nombre de la nueva imagen
        $this->imagen = $imagen;
    }

    public function borrarImagen() {
        // Comprueba si hay una imagen existente en la instancia y la elimina del servidor
        if (!is_null($this->imagen)) {
            $existeArchivo = file_exists(CARPETA_IMAGENES . $this->imagen);
            if ($existeArchivo) {
                unlink(CARPETA_IMAGENES . $this->imagen);
            }
        }
    }
}

<?php

namespace Model;

class Departamento extends ActiveRecord {
    protected static $tabla = 'departamentos'; // Define la tabla asociada al modelo
    protected static $columnasDB = ['id', 'nombre_departamento', 'id_encargado', 'imagen', 'descripcion', 'publicado', 'disponible']; // Define las columnas de la tabla

    public $id;
    public $nombre_departamento;
    public $id_encargado;
    public $imagen;
    public $descripcion;
    public $publicado;
    public $disponible;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null; // Asigna el ID
        $this->nombre_departamento = $args['nombre_departamento'] ?? ''; // Asigna el nombre del departamento
        $this->id_encargado = $args['id_encargado'] ?? ''; // Asigna el ID del encargado
        $this->imagen = $args['imagen'] ?? ''; // Asigna la imagen
        $this->descripcion = $args['descripcion'] ?? ''; // Asigna la descripción
        $this->publicado = $args['publicado'] ?? 0; // Asigna el estado publicado
        $this->disponible = isset($args['disponible']) ? (int)$args['disponible'] : 1; // Valor predeterminado de disponible
    }

    public function validar() {
        if (!$this->nombre_departamento) {
            self::setAlerta('error', 'El Nombre del Departamento es Obligatorio'); // Valida el nombre del departamento
        }

        if (!$this->id_encargado) {
            self::setAlerta('error', 'El Encargado del Departamento es Obligatorio'); // Valida el encargado del departamento
        }

        if (!$this->descripcion) {
            self::setAlerta('error', 'La Descripción es Obligatoria'); // Valida la descripción
        }

        return self::$alertas; // Retorna las alertas
    }

    public function setImagen($imagen) {
        if (!is_null($this->id)) {
            $this->borrarImagen(); // Elimina la imagen anterior si existe
        }

        $this->imagen = $imagen; // Asigna la nueva imagen
    }

    public function borrarImagen() {
        if (!is_null($this->imagen)) {
            $existeArchivo = file_exists(CARPETA_IMAGENES . $this->imagen); // Comprueba si existe el archivo
            if ($existeArchivo) {
                unlink(CARPETA_IMAGENES . $this->imagen); // Elimina el archivo
            }
        }
    }
}

<?php

namespace Model;

class Empleado extends ActiveRecord {
    protected static $tabla = 'empleados'; // Define la tabla asociada al modelo
    protected static $columnasDB = ['id', 'nombre', 'apellido', 'ciudad', 'pais', 'imagen', 'puesto_trabajo_id', 'tags', 'redes_sociales', 'departamento_id', 'email']; // Define las columnas de la tabla

    public $id;
    public $nombre;
    public $apellido;
    public $ciudad;
    public $pais;
    public $imagen;
    public $puesto_trabajo_id;
    public $tags;
    public $redes_sociales;
    public $departamento_id;
    public $email;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null; // Asigna el ID
        $this->nombre = $args['nombre'] ?? ''; // Asigna el nombre
        $this->apellido = $args['apellido'] ?? ''; // Asigna el apellido
        $this->ciudad = $args['ciudad'] ?? ''; // Asigna la ciudad
        $this->pais = $args['pais'] ?? ''; // Asigna el país
        $this->imagen = $args['imagen'] ?? ''; // Asigna la imagen
        $this->puesto_trabajo_id = $args['puesto_trabajo_id'] ?? null; // Asigna el ID del puesto de trabajo
        $this->tags = $args['tags'] ?? ''; // Asigna las tags
        $this->redes_sociales = $args['redes_sociales'] ?? ''; // Asigna las redes sociales
        $this->departamento_id = $args['departamento_id'] ?? null; // Asigna el ID del departamento
        $this->email = $args['email'] ?? ''; // Asigna el email
    }

    public function validar() {
        if (!$this->nombre) {
            self::setAlerta('error', 'El Nombre es Obligatorio'); // Valida el nombre
        }
        if (!$this->apellido) {
            self::setAlerta('error', 'El Apellido es Obligatorio'); // Valida el apellido
        }
        if (!$this->ciudad) {
            self::setAlerta('error', 'El Campo Ciudad es Obligatorio'); // Valida la ciudad
        }
        if (!$this->pais) {
            self::setAlerta('error', 'El Campo País es Obligatorio'); // Valida el país
        }
        if (!$this->imagen) {
            self::setAlerta('error', 'La imagen es obligatoria'); // Valida la imagen
        }
        if (!$this->puesto_trabajo_id) {
            self::setAlerta('error', 'El Campo Puesto de Trabajo es Obligatorio'); // Valida el puesto de trabajo
        }
        if (!$this->tags) {
            self::setAlerta('error', 'El Campo Áreas de Experiencia es obligatorio'); // Valida las áreas de experiencia
        }
        if (!$this->departamento_id) {
            self::setAlerta('error', 'El Campo Departamento es Obligatorio'); // Valida el departamento
        }
        if (!$this->email) {
            self::setAlerta('error', 'El Email es Obligatorio'); // Valida el email
        }

        return self::$alertas; // Retorna las alertas
    }

    public function obtenerNombrePuestoTrabajo() {
        return self::obtenerNombrePorId('puestos_trabajo', $this->puesto_trabajo_id, 'nombre_puesto'); // Retorna el nombre del puesto de trabajo
    }

    public function obtenerNombreDepartamento() {
        return self::obtenerNombrePorId('departamentos', $this->departamento_id, 'nombre_departamento'); // Retorna el nombre del departamento
    }
}

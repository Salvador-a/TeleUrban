<?php

namespace Model;

class Empleado extends ActiveRecord {
    protected static $tabla = 'empleados';
    protected static $columnasDB = ['id', 'nombre', 'apellido', 'ciudad', 'pais', 'imagen', 'puesto_trabajo_id', 'tags', 'redes_sociales', 'departamento_id'];

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

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->apellido = $args['apellido'] ?? '';
        $this->ciudad = $args['ciudad'] ?? '';
        $this->pais = $args['pais'] ?? '';
        $this->imagen = $args['imagen'] ?? '';
        $this->puesto_trabajo_id = $args['puesto_trabajo_id'] ?? null;
        $this->tags = $args['tags'] ?? '';
        $this->redes_sociales = $args['redes_sociales'] ?? '';
        $this->departamento_id = $args['departamento_id'] ?? null;
    }

    public function validar() {
        if (!$this->nombre) {
            self::setAlerta('error', 'El Nombre es Obligatorio');
        }
        if (!$this->apellido) {
            self::setAlerta('error', 'El Apellido es Obligatorio');
        }
        if (!$this->ciudad) {
            self::setAlerta('error', 'El Campo Ciudad es Obligatorio');
        }
        if (!$this->pais) {
            self::setAlerta('error', 'El Campo País es Obligatorio');
        }
        if (!$this->imagen) {
            self::setAlerta('error', 'La imagen es obligatoria');
        }
        if (!$this->puesto_trabajo_id) {
            self::setAlerta('error', 'El Campo Puesto de Trabajo es Obligatorio');
        }
        if (!$this->tags) {
            self::setAlerta('error', 'El Campo Áreas de Experiencia es obligatorio');
        }
        
        if (!$this->departamento_id) {
            self::setAlerta('error', 'El Campo Departamento es Obligatorio');
        }

        return self::$alertas;
    }

    public function obtenerNombrePuestoTrabajo() {
        return self::obtenerNombrePorId('puestos_trabajo', $this->puesto_trabajo_id, 'nombre_puesto');
    }

    public function obtenerNombreDepartamento() {
        return self::obtenerNombrePorId('departamentos', $this->departamento_id, 'nombre_departamento');
    }
}
?>

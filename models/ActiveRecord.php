<?php
namespace Model; // Define el espacio de nombres del archivo

#[\AllowDynamicProperties] // Permite propiedades dinámicas en la clase
class ActiveRecord {

    // Base DE DATOS
    protected static $db; // Variable estática para la base de datos
    protected static $tabla = ''; // Variable estática para la tabla de la base de datos
    protected static $columnasDB = []; // Variable estática para las columnas de la tabla

    // Alertas y Mensajes
    protected static $alertas = []; // Variable estática para las alertas y mensajes
    
    // Definir la conexión a la BD - includes/database.php
    public static function setDB($database) { // Método para establecer la base de datos
        self::$db = $database; // Asigna la base de datos a la variable estática $db
    }

    // Setear un tipo de Alerta
    public static function setAlerta($tipo, $mensaje) { // Método para establecer una alerta
        static::$alertas[$tipo][] = $mensaje; // Añade el mensaje a la alerta del tipo especificado
    }

    // Obtener las alertas
    public static function getAlertas() { // Método para obtener las alertas
        return static::$alertas; // Devuelve las alertas
    }

    // Validación que se hereda en modelos
    public function validar() { // Método para validar
        static::$alertas = []; // Reinicia las alertas
        return static::$alertas; // Devuelve las alertas
    }
    // Consulta SQL para crear un objeto en Memoria (Active Record)
    public static function consultarSQL($query) {
        // Consultar la base de datos
        $resultado = self::$db->query($query);

        // Iterar los resultados
        $array = [];
        while($registro = $resultado->fetch_assoc()) {
            $array[] = static::crearObjeto($registro);
        }

        // liberar la memoria
        $resultado->free();

        // retornar los resultados
        return $array;
    }

    // Este método crea un objeto en memoria que es igual al de la BD
    protected static function crearObjeto($registro) {
        $objeto = new static; // Crea una nueva instancia de la clase

        // Asigna los valores del registro a las propiedades del objeto
        foreach($registro as $key => $value ) {
            if(property_exists( $objeto, $key  )) {
                $objeto->$key = $value;
            }
        }
        return $objeto; // Retorna el objeto
    }

    // Este método identifica y une los atributos de la BD
    public function atributos() {
        $atributos = []; // Inicializa un array vacío para los atributos
        foreach(static::$columnasDB as $columna) { // Itera sobre las columnas de la BD
            if($columna === 'id') continue; // Si la columna es 'id', la omite
            $atributos[$columna] = $this->$columna; // Asigna el valor de la propiedad a la columna correspondiente en el array de atributos
        }
        return $atributos; // Retorna los atributos
    }

    // Sanitizar los datos antes de guardarlos en la BD
    public function sanitizarAtributos() {
        $atributos = $this->atributos(); // Obtiene los atributos del objeto
        $sanitizado = []; // Inicializa un array vacío para los atributos sanitizados
        foreach($atributos as $key => $value ) {
            $sanitizado[$key] = self::$db->escape_string($value); // Sanitiza el valor y lo añade al array sanitizado
        }
        return $sanitizado; // Retorna los atributos sanitizados
    }

    // Sincroniza BD con Objetos en memoria
    public function sincronizar($args=[]) { 
        foreach($args as $key => $value) {
            if(property_exists($this, $key) && !is_null($value)) {
                $this->$key = $value; // Si la propiedad existe y el valor no es nulo, asigna el valor a la propiedad
            }
        }
    }

    // Registros - CRUD
    public function guardar() {
        $resultado = '';
        if(!is_null($this->id)) {
            // actualizar
            $resultado = $this->actualizar(); // Si el id no es nulo, actualiza el registro
        } else {
            // Creando un nuevo registro
            $resultado = $this->crear(); // Si el id es nulo, crea un nuevo registro
        }
        return $resultado; // Retorna el resultado de la operación
    }

    // Obtener todos los Registros
    public static function all($orden = 'DESC') {
        $query = "SELECT * FROM " . static::$tabla . " ORDER BY id {$orden}"; // Crea la consulta SQL para obtener todos los registros
        $resultado = self::consultarSQL($query); // Ejecuta la consulta SQL
        return $resultado; // Retorna los resultados
    }

    // Busca un registro por su id
    public static function find($id) {
        $query = "SELECT * FROM " . static::$tabla  ." WHERE id = {$id}"; // Crea la consulta SQL para buscar un registro por su id
        $resultado = self::consultarSQL($query); // Ejecuta la consulta SQL
        return array_shift( $resultado ) ; // Retorna el primer elemento del resultado
    }

    // Obtener Registros con cierta cantidad
    public static function get($limite) {
        $query = "SELECT * FROM " . static::$tabla . " ORDER BY id DESC LIMIT {$limite} " ; // Crea la consulta SQL para obtener un número limitado de registros
        $resultado = self::consultarSQL($query); // Ejecuta la consulta SQL
        return ( $resultado ) ; // Retorna los resultados
    }

    // Paginar los registros
    public static function paginar($por_pagina, $offset) {
        $query = "SELECT * FROM " . static::$tabla . " ORDER BY id DESC LIMIT {$por_pagina} OFFSET {$offset} " ; // Crea la consulta SQL para paginar los registros
        $resultado = self::consultarSQL($query); // Ejecuta la consulta SQL
        return $resultado ; // Retorna los resultados
    }

    // Busqueda Where con Columna 
    public static function where($columna, $valor) {
        $query = "SELECT * FROM " . static::$tabla . " WHERE {$columna} = '{$valor}'"; // Crea la consulta SQL para buscar registros donde una columna tenga un valor específico
        $resultado = self::consultarSQL($query); // Ejecuta la consulta SQL
        return array_shift( $resultado ) ; // Retorna el primer elemento del resultado
    }

    // Retornas los registros por un orden
    public static function ordenar($columna, $orden) {
        $query = "SELECT * FROM " . static::$tabla . " ORDER BY {$columna} {$orden}"; // Crea la consulta SQL para ordenar los registros por una columna específica
        $resultado = self::consultarSQL($query); // Ejecuta la consulta SQL
        return $resultado; // Retorna los resultados
    }

    // Retorna registros ordenados y limitados
    public static function ordenarLimete($columna, $orden, $limite) {
        $query = "SELECT * FROM " . static::$tabla . " ORDER BY {$columna} {$orden} LIMIT {$limite} "; // Crea la consulta SQL para obtener registros ordenados y limitados
        $resultado = self::consultarSQL($query); // Ejecuta la consulta SQL
        return $resultado; // Retorna los resultados
    }

    // Busca registros con múltiples condiciones
    public static function whereArray($array = []) {
        $query = "SELECT * FROM " . static::$tabla . " WHERE "; // Inicia la consulta SQL
        foreach($array as $key => $value) { // Itera sobre el array de condiciones
            if($key == array_key_last($array)) { // Si es la última condición
                $query .= " {$key} = '{$value}'"; // Añade la condición sin el operador AND al final
            } else {
                $query .= " {$key} = '{$value}' AND "; // Añade la condición con el operador AND al final
            }
        }
        $resultado = self::consultarSQL($query); // Ejecuta la consulta SQL
        return $resultado; // Retorna los resultados
    }

    // Obtiene el total de registros
    public static function total($columna = '', $valor= '') {
        $query = "SELECT COUNT(*) FROM " . static::$tabla ; // Inicia la consulta SQL para obtener el total de registros
        if ($columna) { // Si se proporciona una columna
            $query .= " WHERE {$columna} = '{$valor}'"; // Añade una condición a la consulta SQL
        }
        $resultado = self::$db->query($query); // Ejecuta la consulta SQL
        $total = $resultado->fetch_array(); // Obtiene el total de registros
        return array_shift($total); // Retorna el total de registros
    }

    // Obtiene el total de registros con múltiples condiciones
    public static function totalArray($array = []) {
        $query = "SELECT COUNT(*) FROM " . static::$tabla . ' WHERE '; // Inicia la consulta SQL para obtener el total de registros
        foreach($array as $key => $value) { // Itera sobre el array de condiciones
            if($key == array_key_last($array)) { // Si es la última condición
                $query .= " {$key} = '{$value}'"; // Añade la condición sin el operador AND al final
            } else {
                $query .= " {$key} = '{$value}' AND "; // Añade la condición con el operador AND al final
            }
        }
        $resultado = self::$db->query($query); // Ejecuta la consulta SQL
        $total = $resultado->fetch_array(); // Obtiene el total de registros
        return array_shift($total); // Retorna el total de registros
    }

    // Crea un nuevo registro
    public function crear() {
        // Sanitiza los datos
        $atributos = $this->sanitizarAtributos();

        // Inserta en la base de datos
        $query = " INSERT INTO " . static::$tabla . " ( ";
        $query .= join(', ', array_keys($atributos)); // Une las claves del array de atributos con comas
        $query .= " ) VALUES (' "; 
        $query .= join("', '", array_values($atributos)); // Une los valores del array de atributos con comas
        $query .= " ') ";
    

        // debuguear($query); // Descomentar si no te funciona algo

        // Ejecuta la consulta SQL
        $resultado = self::$db->query($query);

        // Retorna un array con el resultado de la consulta y el id del último registro insertado
        return [
            'resultado' =>  $resultado, // Resultado de la consulta
            'id' => self::$db->insert_id // ID del último registro insertado
        ];
    }

    // Actualizar el registro
    public function actualizar() {
        // Sanitiza los datos
        $atributos = $this->sanitizarAtributos();

        // Itera para ir agregando cada campo de la BD
        $valores = [];
        foreach($atributos as $key => $value) {
            $valores[] = "{$key}='{$value}'"; // Añade cada valor al array de valores
        }

        // Consulta SQL
        $query = "UPDATE " . static::$tabla ." SET ";
        $query .=  join(', ', $valores ); // Une los valores con comas
        $query .= " WHERE id = '" . self::$db->escape_string($this->id) . "' "; // Añade la condición WHERE
        $query .= " LIMIT 1 "; // Limita la consulta a un solo registro

        // Actualizar BD
        $resultado = self::$db->query($query); // Ejecuta la consulta SQL
        return $resultado; // Retorna el resultado de la consulta
    }

    // Agregar el método findWhere
    public static function findWhere($conditions) {
        $query = "SELECT * FROM " . static::$tabla . " WHERE ";
        $queryParams = [];

        foreach ($conditions as $field => $value) {
            $queryParams[] = "$field = '" . self::$db->escape_string($value) . "'";
        }

        $query .= implode(' AND ', $queryParams);

        $resultado = self::consultarSQL($query);
        return array_shift($resultado);
    }
    
    public static function obtenerNombrePorId($tabla, $id, $columnaNombre = 'nombre') {
        if (is_null($id)) {
            return '';
        }

        $id = self::$db->escape_string($id);
        $query = "SELECT {$columnaNombre} FROM {$tabla} WHERE id = {$id}";
        $resultado = self::$db->query($query);

        if ($resultado) {
            $registro = $resultado->fetch_assoc();
            return $registro[$columnaNombre] ?? '';
        }

        return '';
    }
    

    // Eliminar un Registro por su ID
    public function eliminar() {
        // Crea la consulta SQL para eliminar un registro por su ID
        $query = "DELETE FROM "  . static::$tabla . " WHERE id = " . self::$db->escape_string($this->id) . " LIMIT 1";

        // Ejecuta la consulta SQL
        $resultado = self::$db->query($query);

        // Retorna el resultado de la consulta
        return $resultado;
    }
}
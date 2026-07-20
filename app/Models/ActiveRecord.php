<?php

class ActiveRecord
{
    protected static $db;
    protected static $columnasDB = [];
    protected static $errores = [];
    protected static $tabla = '';

    public static function setDB($database)
    {
        self::$db = $database;
    }

    public function guardar()
    {
        if ($this->id) {
            return $this->actualizar();
        }

        return $this->crear();
    }

    public static function getErrores()
    {
        return static::$errores;
    }

    public function atributos()
    {
        $atributos = [];
        foreach (static::$columnasDB as $columna) {
            $atributos[$columna] = $this->$columna;
        }
        return $atributos;
    }

    public function sanitizarAtributos()
    {
        // Obtener los atributos del objeto
        $atributos = $this->atributos();
        // Crear un nuevo arreglo para los datos sanitizados
        $sanitizados = [];
        // Recorrer cada atributo
        foreach ($atributos as $key => $value) {
            // Escapar el valor utilizando la conexión a la base de datos
            $sanitizados[$key] = static::$db->escape_string($value);
        }
        // Regresar el arreglo sanitizado
        return $sanitizados;
    }

    public function validar()
    {
        static::$errores = [];
        return static::$errores;
    }

    public function crear()
    {
        // Obtener los atributos sanitizados
        $atributos = $this->sanitizarAtributos();
        // Obtener los nombres de las columnas
        $columnas = implode(', ', array_keys($atributos));
        // Obtener los valores
        $valores = implode("', '", array_values($atributos));
        // Construir la consulta SQL
        $query = "INSERT INTO " . static::$tabla . " (";
        $query .= $columnas;
        $query .= ") VALUES ('";
        $query .= $valores;
        $query .= "')";
        // Ejecutar la consulta
        $resultado = static::$db->query($query);

        return $resultado;
    }

    public function sincronizar($args = [])
    {
        foreach ($args as $key => $value) {
            if (property_exists($this, $key) && !is_null($value)) {
                $this->$key = $value;
            }
        }
    }
}

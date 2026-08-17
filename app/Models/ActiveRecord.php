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

        // Si el registro se creó correctamente,
        // obtenemos el ID generado automáticamente
        // por MySQL y lo asignamos al objeto actual.
        if ($resultado) {
            $this->id = static::$db->insert_id;
        }
        // Regresa el resultado de la consulta.
        return $resultado;
    }

    /**
     * Sincroniza los datos recibidos (por ejemplo, desde un formulario)
     * con las propiedades del objeto actual.
     *
     * Recorre un arreglo de datos y actualiza únicamente las propiedades
     * que existen en el modelo y cuyo valor no sea null. Esto permite
     * reutilizar el mismo objeto al editar registros sin reemplazar
     * información innecesariamente.
     */
    public function sincronizar($args = [])
    {
        foreach ($args as $key => $value) {
            if (property_exists($this, $key) && !is_null($value)) {
                $this->$key = $value;
            }
        }
    }

    public function actualizar()
    {
        // Obtener los atributos sanitizados
        $atributos = $this->sanitizarAtributos();

        // Crear un arreglo con cada columna y su valor
        $valores = [];

        foreach ($atributos as $key => $value) {
            $valores[] = "{$key}='{$value}'";
        }

        // Construir la consulta SQL
        $query = "UPDATE " . static::$tabla . " SET ";
        $query .= implode(', ', $valores);
        $query .= " WHERE id = '" . static::$db->escape_string($this->id) . "' LIMIT 1";

        // Ejecutar la consulta
        $resultado = static::$db->query($query);

        return $resultado;
    }

    public static function find($id)
    {
        // Sanitizar el ID recibido
        $id = static::$db->escape_string($id);

        // Construir la consulta
        $query = "SELECT * FROM " . static::$tabla;
        $query .= " WHERE id = {$id}";
        $query .= " LIMIT 1";

        // Ejecutar la consulta
        $resultado = static::$db->query($query);

        // Obtener el registro
        $registro = $resultado->fetch_assoc();

        // Si no existe el registro
        if (!$registro) {
            return null;
        }

        // Convertir el registro en un objeto
        return static::crearObjeto($registro);
    }

    protected static function crearObjeto($registro)
    {
        // Crear una nueva instancia del modelo
        $objeto = new static;

        // Asignar cada valor a su propiedad correspondiente
        foreach ($registro as $key => $value) {

            if (property_exists($objeto, $key)) {
                $objeto->$key = $value;
            }
        }

        return $objeto;
    }

    public static function all()
    {
        // Construir la consulta
        $query = "SELECT * FROM " . static::$tabla;

        // Ejecutar la consulta
        $resultado = static::$db->query($query);

        // Arreglo donde se almacenarán los objetos
        $objetos = [];

        // Recorrer todos los registros obtenidos
        while ($registro = $resultado->fetch_assoc()) {
            $objetos[] = static::crearObjeto($registro);
        }

        // Liberar memoria del resultado
        $resultado->free();

        // Regresar el arreglo de objetos
        return $objetos;
    }

    public function delete()
    {
        // Sanitizar el ID
        $id = static::$db->escape_string($this->id);

        // Construir la consulta SQL
        $query = "DELETE FROM " . static::$tabla;
        $query .= " WHERE id = '{$id}'";
        $query .= " LIMIT 1";

        // Ejecutar la consulta
        $resultado = static::$db->query($query);

        return $resultado;
    }

    public static function consultaSQL($query)
    {
        // Ejecutar la consulta
        $resultado = static::$db->query($query);

        // Arreglo donde se almacenarán los objetos
        $objetos = [];

        // Recorrer cada registro obtenido
        while ($registro = $resultado->fetch_assoc()) {
            $objetos[] = static::crearObjeto($registro);
        }

        // Liberar memoria
        $resultado->free();

        // Regresar los objetos
        return $objetos;
    }

    /**
     * Obtiene la cantidad total de registros
     * existentes en la tabla del modelo.
     *
     * @return int
     */
    public static function totalRegistros()
    {
        // Construye la consulta SQL.
        $query = "SELECT COUNT(*) AS total FROM " . static::$tabla;

        // Ejecuta la consulta.
        $resultado = static::$db->query($query);

        // Obtiene el resultado como arreglo asociativo.
        $fila = $resultado->fetch_assoc();

        // Devuelve el total de registros.
        return (int) $fila['total'];
    }

    /**
     * Busca un registro por una columna específica.
     *
     * @param string $columna
     * @param mixed $valor
     * @return static|null
     */
    public static function where($columna, $valor)
    {
        // Escapa el valor para evitar inyección SQL.
        $valor = static::$db->escape_string($valor);

        // Construye la consulta.
        $query = "SELECT * FROM " . static::$tabla;
        $query .= " WHERE {$columna} = '{$valor}'";
        $query .= " LIMIT 1";

        // Ejecuta la consulta.
        $resultado = static::consultaSQL($query);

        // Devuelve el primer resultado o null.
        return array_shift($resultado);
    }

    /**
     * Inicia una transacción en la base de datos.
     */
    public static function iniciarTransaccion()
    {
        static::$db->begin_transaction();
    }


    /**
     * Confirma todos los cambios
     * realizados durante la transacción.
     */
    public static function confirmarTransaccion()
    {
        static::$db->commit();
    }


    /**
     * Revierte todos los cambios
     * realizados durante la transacción.
     */
    public static function revertirTransaccion()
    {
        static::$db->rollback();
    }
}

<?php

/**
 * Modelo que representa la tabla marcas.
 */
class Marca extends ActiveRecord
{
    protected static $tabla = 'marcas';

    protected static $columnasDB = [
        'nombre'
    ];

    public $id;
    public $nombre;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
    }

    /**
     * Obtiene marcas relacionadas con una categoría.
     */
    public static function obtenerPorCategoria($categoriaId)
    {
        $categoriaId = (int) $categoriaId;

        if ($categoriaId <= 0) {
            return [];
        }

        $query = "SELECT m.*
              FROM marcas m
              INNER JOIN categoria_marca cm
                  ON cm.marca_id = m.id
              WHERE cm.categoria_id = {$categoriaId}
              ORDER BY m.nombre ASC";

        return static::consultaSQL($query);
    }

    /**
     * Comprueba si una marca pertenece a una categoría.
     */
    public static function perteneceACategoria($marcaId, $categoriaId)
    {
        $marcaId = (int) $marcaId;
        $categoriaId = (int) $categoriaId;

        if ($marcaId <= 0 || $categoriaId <= 0) {
            return false;
        }

        $query = "SELECT 1
              FROM categoria_marca
              WHERE categoria_id = {$categoriaId}
              AND marca_id = {$marcaId}
              LIMIT 1";

        $resultado = static::$db->query($query);

        return $resultado && $resultado->num_rows > 0;
    }
}

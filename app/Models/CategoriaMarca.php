<?php

class CategoriaMarca extends ActiveRecord
{
    protected static $tabla = 'categoria_marca';

    protected static $columnasDB = [
        'categoria_id',
        'marca_id'
    ];

    public $categoria_id;
    public $marca_id;

    public function __construct($args = [])
    {
        $this->categoria_id = $args['categoria_id'] ?? null;
        $this->marca_id = $args['marca_id'] ?? null;
    }

    /**
     * Obtiene las marcas asignadas a una categoría.
     */
    public static function obtenerMarcasPorCategoria($categoriaId)
    {
        $categoriaId = (int) $categoriaId;

        $query = "SELECT marca_id FROM categoria_marca
                  WHERE categoria_id = {$categoriaId}";

        return static::consultaSQL($query);
    }

    /**
     * Elimina todas las relaciones de una categoría.
     */
    public static function eliminarPorCategoria($categoriaId)
    {
        $categoriaId = (int) $categoriaId;

        $query = "DELETE FROM categoria_marca
                  WHERE categoria_id = {$categoriaId}";

        return static::$db->query($query);
    }
}
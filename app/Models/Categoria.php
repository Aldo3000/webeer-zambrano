<?php

/**
 * Modelo que representa la tabla categorias.
 */
class Categoria extends ActiveRecord
{
    // Nombre de la tabla.
    protected static $tabla = 'categorias';

    // Columnas existentes en la tabla.
    protected static $columnasDB = [
        'id',
        'nombre'
    ];

    // Propiedades del objeto.
    public $id;
    public $nombre;

    /**
     * Constructor.
     */
    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
    }
}
<?php

/**
 * Modelo que representa la tabla marcas.
 */
class Marca extends ActiveRecord
{
    protected static $tabla = 'marcas';

    protected static $columnasDB = [
        'id',
        'nombre'
    ];

    public $id;
    public $nombre;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
    }
}
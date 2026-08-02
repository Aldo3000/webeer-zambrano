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
        'nombre',
        'descripcion'
    ];

    // Propiedades del objeto.
    public $id;
    public $nombre;
    public $descripcion;

    /**
     * Constructor.
     */
    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->descripcion = $args['descripcion'] ?? '';
    }
}
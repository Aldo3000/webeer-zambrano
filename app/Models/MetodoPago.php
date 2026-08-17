<?php

/**
 * Modelo:
 * Método de pago
 */
class MetodoPago extends ActiveRecord
{
    protected static $tabla = 'metodos_pago';

    protected static $columnasDB = [
        'nombre'
    ];

    public $id;
    public $nombre;
    /**
     * Constructor del modelo.
     */
    public function __construct($args = [])
    {
        $this->id =
            $args['id'] ?? null;

        $this->nombre =
            $args['nombre'] ?? '';
    }
}
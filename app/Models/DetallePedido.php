<?php

/**
 * Modelo:
 * DetallePedido
 *
 * Representa cada producto
 * incluido dentro de un pedido.
 */
class DetallePedido extends ActiveRecord
{
    protected static $tabla = 'detalle_pedido';

    protected static $columnasDB = [
        'pedido_id',
        'producto_id',
        'sku',
        'nombre_producto',
        'cantidad',
        'precio_unitario',
        'subtotal'
    ];


    public $id;

    public $pedido_id;
    public $producto_id;

    public $sku;
    public $nombre_producto;

    public $cantidad;

    public $precio_unitario;
    public $subtotal;


    /**
     * Constructor del modelo.
     */
    public function __construct($args = [])
    {
        $this->id =
            $args['id'] ?? null;

        $this->pedido_id =
            $args['pedido_id'] ?? null;

        $this->producto_id =
            $args['producto_id'] ?? null;

        $this->sku =
            $args['sku'] ?? '';

        $this->nombre_producto =
            $args['nombre_producto'] ?? '';

        $this->cantidad =
            $args['cantidad'] ?? 0;

        $this->precio_unitario =
            $args['precio_unitario'] ?? 0;

        $this->subtotal =
            $args['subtotal'] ?? 0;
    }

    /**
     * Obtiene todos los detalles
     * pertenecientes a un pedido.
     */
    public static function obtenerPorPedido($pedidoId)
    {
        $pedidoId = (int) $pedidoId;

        if ($pedidoId <= 0) {
            return [];
        }

        $query = "SELECT *
              FROM " . static::$tabla . "
              WHERE pedido_id = {$pedidoId}
              ORDER BY id ASC";

        return static::consultaSQL($query);
    }
}

<?php

/**
 * Modelo:
 * Pedido
 *
 * Representa la tabla pedidos.
 */
class Pedido extends ActiveRecord
{
    protected static $tabla = 'pedidos';

    protected static $columnasDB = [
        'numero_pedido',
        'usuario_id',
        'estado_pedido_id',
        'estado_pago_id',
        'metodo_pago_id',
        'nombre',
        'apellido',
        'correo',
        'telefono',
        'calle',
        'numero',
        'colonia',
        'municipio',
        'estado',
        'codigo_postal',
        'subtotal',
        'total',
        'referencia_pago',
        'notas'
    ];

    public $id;

    public $numero_pedido;
    public $usuario_id;

    public $estado_pedido_id;
    public $estado_pago_id;
    public $metodo_pago_id;

    public $nombre;
    public $apellido;
    public $correo;
    public $telefono;

    public $calle;
    public $numero;
    public $colonia;
    public $municipio;
    public $estado;
    public $codigo_postal;

    public $subtotal;
    public $total;

    public $referencia_pago;
    public $notas;

    public $created_at;
    public $updated_at;


    /**
     * Constructor del modelo.
     */
    public function __construct($args = [])
    {
        $this->id =
            $args['id'] ?? null;

        $this->numero_pedido =
            $args['numero_pedido'] ?? '';

        $this->usuario_id =
            $args['usuario_id'] ?? null;

        $this->estado_pedido_id =
            $args['estado_pedido_id'] ?? null;

        $this->estado_pago_id =
            $args['estado_pago_id'] ?? null;

        $this->metodo_pago_id =
            $args['metodo_pago_id'] ?? null;

        $this->nombre =
            $args['nombre'] ?? '';

        $this->apellido =
            $args['apellido'] ?? '';

        $this->correo =
            $args['correo'] ?? '';

        $this->telefono =
            $args['telefono'] ?? '';

        $this->calle =
            $args['calle'] ?? '';

        $this->numero =
            $args['numero'] ?? '';

        $this->colonia =
            $args['colonia'] ?? '';

        $this->municipio =
            $args['municipio'] ?? '';

        $this->estado =
            $args['estado'] ?? '';

        $this->codigo_postal =
            $args['codigo_postal'] ?? '';

        $this->subtotal =
            $args['subtotal'] ?? 0;

        $this->total =
            $args['total'] ?? 0;

        $this->referencia_pago =
            $args['referencia_pago'] ?? '';

        $this->notas =
            $args['notas'] ?? '';
    }
}

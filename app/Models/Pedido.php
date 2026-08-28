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

    /**
     * Obtiene todos los pedidos
     * pertenecientes a un usuario.
     *
     * @param int $usuarioId
     * @return array
     */
    public static function obtenerPorUsuario($usuarioId)
    {
        $usuarioId = (int) $usuarioId;

        if ($usuarioId <= 0) {
            return [];
        }

        $query = "SELECT *
              FROM " . static::$tabla . "
              WHERE usuario_id = {$usuarioId}
              ORDER BY created_at DESC";

        return static::consultaSQL($query);
    }

    /**
     * Obtiene un pedido específico
     * perteneciente a un usuario.
     *
     * @param int $pedidoId
     * @param int $usuarioId
     * @return Pedido|null
     */
    public static function obtenerPorUsuarioPedido($pedidoId, $usuarioId)
    {
        $pedidoId = (int) $pedidoId;
        $usuarioId = (int) $usuarioId;

        if ($pedidoId <= 0 || $usuarioId <= 0) {
            return null;
        }

        $query = "SELECT *
              FROM " . static::$tabla . "
              WHERE id = {$pedidoId}
              AND usuario_id = {$usuarioId}
              LIMIT 1";

        $resultado = static::consultaSQL($query);

        return array_shift($resultado);
    }

    /**
     * Obtiene los pedidos de un usuario.
     *
     * Puede recibir un estado para filtrar
     * los resultados.
     *
     * @param int $usuarioId
     * @param int|null $estadoPedidoId
     * @return array
     */
    public static function obtenerPorUsuarioEstado(
        $usuarioId,
        $estadoPedidoId = null
    ) {
        $usuarioId = (int) $usuarioId;

        if ($usuarioId <= 0) {
            return [];
        }

        $query = "SELECT *
              FROM " . static::$tabla . "
              WHERE usuario_id = {$usuarioId}";

        // Si se recibió un estado,
        // agregamos el filtro.
        if ($estadoPedidoId !== null) {

            $estadoPedidoId =
                (int) $estadoPedidoId;

            $query .= "
            AND estado_pedido_id = {$estadoPedidoId}
        ";
        }

        $query .= "
        ORDER BY id DESC
    ";

        return static::consultaSQL($query);
    }

    /**
     * Obtiene todos los pedidos registrados.
     *
     * @return array
     */
    public static function obtenerTodos()
    {
        $query = "SELECT *
              FROM " . static::$tabla . "
              ORDER BY created_at DESC";

        return static::consultaSQL($query);
    }

    /**
     * Actualiza el estado de un pedido.
     *
     * @param int $pedidoId
     * @param int $estadoPedidoId
     * @return bool
     */
    public static function actualizarEstado($pedidoId, $estadoPedidoId)
    {
        $pedidoId = (int) $pedidoId;
        $estadoPedidoId = (int) $estadoPedidoId;

        if ($pedidoId <= 0 || $estadoPedidoId <= 0) {
            return false;
        }

        $query = "UPDATE " . static::$tabla . "
              SET estado_pedido_id = {$estadoPedidoId}
              WHERE id = {$pedidoId}
              LIMIT 1";

        $resultado = static::$db->query($query);

        return $resultado;
    }
    /**
     * Obtiene pedidos para administración
     * aplicando filtros de búsqueda y estado.
     *
     * @param string $busqueda
     * @param int|null $estadoPedidoId
     * @return array
     */
    public static function buscarAdmin($busqueda = '', $estadoPedidoId = null)
    {
        $busqueda = trim($busqueda);

        $query = "SELECT *
              FROM " . static::$tabla . "
              WHERE 1 = 1";
        /*
    |--------------------------------------------------------------------------
    | Filtro por estado
    |--------------------------------------------------------------------------
    */
        if ($estadoPedidoId !== null) {
            $estadoPedidoId =
                (int) $estadoPedidoId;
            $query .= "
            AND estado_pedido_id =
            {$estadoPedidoId}
        ";
        }
        /*
    |--------------------------------------------------------------------------
    | Búsqueda
    |--------------------------------------------------------------------------
    */
        if ($busqueda !== '') {
            $busqueda =
                static::$db->escape_string(
                    $busqueda
                );
            $query .= "
            AND (
                numero_pedido LIKE '%{$busqueda}%'
                OR nombre LIKE '%{$busqueda}%'
                OR apellido LIKE '%{$busqueda}%'
                OR correo LIKE '%{$busqueda}%'
            )
        ";
        }
        /*
    |--------------------------------------------------------------------------
    | Orden
    |--------------------------------------------------------------------------
    */
        $query .= "
        ORDER BY created_at DESC
    ";
        return static::consultaSQL($query);
    }
}

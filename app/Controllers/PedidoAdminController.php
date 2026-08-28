<?php

/**
 * Controlador encargado
 * de la administración de pedidos.
 */
class PedidoAdminController
{
    /**
     * Muestra los pedidos administrativos
     * aplicando filtros de búsqueda.
     */
    public static function index()
    {
        // Verifica acceso administrativo.
        estaAutenticado();
        /*
    |--------------------------------------------------------------------------
    | Obtener filtros
    |--------------------------------------------------------------------------
    */
        $busqueda = trim($_GET['busqueda'] ?? '');

        $estado = $_GET['estado'] ?? '';
        /*
    |--------------------------------------------------------------------------
    | Estados permitidos
    |--------------------------------------------------------------------------
    */
        $estadosPermitidos = [
            'pendiente' => 1,
            'pagado' => 2,
            'preparando' => 3,
            'enviado' => 4,
            'entregado' => 5,
            'cancelado' => 6
        ];
        /*
    |--------------------------------------------------------------------------
    | Determinar estado
    |--------------------------------------------------------------------------
    */
        $estadoPedidoId = null;

        if (isset($estadosPermitidos[$estado])) {
            $estadoPedidoId = $estadosPermitidos[$estado];
        }
        /*
    |--------------------------------------------------------------------------
    | Buscar pedidos
    |--------------------------------------------------------------------------
    */
        $pedidos = Pedido::buscarAdmin($busqueda, $estadoPedidoId);
        /*
    |--------------------------------------------------------------------------
    | Renderizar
    |--------------------------------------------------------------------------
    */
        $router = new Router();
        $router->render(
            'admin/pedidos/index',
            [
                'pedidos' => $pedidos,
                'busqueda' => $busqueda,
                'estado' => $estado
            ]
        );
    }

    /**
     * Muestra el detalle de un pedido
     * desde administración.
     */
    public static function detalle()
    {
        // Verifica autenticación administrativa.
        estaAutenticado();

        // Obtiene el ID.
        $pedidoId = filter_var(
            $_GET['id'] ?? null,
            FILTER_VALIDATE_INT
        );

        // ID inválido.
        if (!$pedidoId) {

            header('Location: /admin/pedidos');
            exit;
        }

        // Busca el pedido.
        $pedido =
            Pedido::find($pedidoId);

        // Pedido inexistente.
        if (!$pedido) {

            header('Location: /admin/pedidos');
            exit;
        }

        // Obtiene los productos.
        $detalles =
            DetallePedido::obtenerPorPedido(
                $pedido->id
            );

        // Renderiza.
        $router = new Router();

        $router->render(
            'admin/pedidos/ver',
            [
                'pedido' => $pedido,
                'detalles' => $detalles
            ]
        );
    }

    /**
     * Actualiza el estado de un pedido.
     */
    public static function actualizarEstado()
    {
        // Verifica que el usuario
        // tenga acceso administrativo.
        estaAutenticado();
        // La actualización debe hacerse
        // únicamente mediante POST.
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/pedidos');
            exit;
        }

        // Obtiene el ID del pedido.
        $pedidoId = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);

        // Obtiene el nuevo estado.
        $estadoPedidoId = filter_var($_POST['estado_pedido_id'] ?? null, FILTER_VALIDATE_INT);

        // Si alguno de los valores
        // no es válido, regresamos.
        if (!$pedidoId || !$estadoPedidoId) {
            header('Location: /admin/pedidos');
            exit;
        }

        // Verifica que el pedido exista.
        $pedido = Pedido::find($pedidoId);

        if (!$pedido) {
            header('Location: /admin/pedidos');
            exit;
        }
        // Estados válidos.
        $estadosPermitidos = [
            1,
            2,
            3,
            4,
            5,
            6
        ];

        // Evita estados inexistentes.
        if (!in_array($estadoPedidoId, $estadosPermitidos, true)) {
            $_SESSION['admin_error'] =
                'El estado seleccionado no es válido.';
            header(
                'Location: /admin/pedido?id='
                    . $pedidoId
            );
            exit;
        }

        // Actualiza el estado.
        $resultado = Pedido::actualizarEstado($pedidoId, $estadoPedidoId);
        if (!$resultado) {
            $_SESSION['admin_error'] =
                'No fue posible actualizar el estado.';
        } else {
            $_SESSION['admin_exito'] =
                'El estado del pedido fue actualizado correctamente.';
        }

        // Regresa al detalle.
        header('Location: /admin/pedido?id=' . $pedidoId);
        exit;
    }
}

<?php

/**
 * Controlador encargado del panel
 * y funcionalidades de la cuenta del cliente.
 */
class CuentaController
{
    /**
     * Muestra el panel principal
     * del cliente.
     */
    public static function index()
    {
        // Verifica que el usuario tenga
        // una sesión válida y sea cliente.
        estaAutorizadoCliente();
        /*
        |--------------------------------------------------------------------------
        | Datos básicos del usuario
        |--------------------------------------------------------------------------
        */
        // Obtiene el ID del usuario autenticado.
        $usuarioId =
            $_SESSION['usuario_id'];
        // Obtiene el nombre almacenado
        // en la sesión.
        $nombre =
            $_SESSION['nombre'] ?? '';
        /*
        |--------------------------------------------------------------------------
        | Mostrar vista
        |--------------------------------------------------------------------------
        */
        $router = new Router();
        $router->render(
            'cuenta/index',
            [
                'usuarioId' => $usuarioId,
                'nombre' => $nombre
            ],
            false
        );
    }

    /**
     * Muestra el perfil del cliente.
     */
    public static function perfil()
    {
        // Verifica que sea un cliente autenticado.
        estaAutorizadoCliente();

        // Obtiene el ID del usuario autenticado.
        $usuarioId =
            $_SESSION['usuario_id'];

        // Busca el usuario en la base de datos.
        $usuario =
            Usuario::buscarPorId($usuarioId);

        // Si por alguna razón el usuario
        // ya no existe, cerramos la sesión.
        if (!$usuario) {

            header('Location: /logout');
            exit;
        }

        // Obtiene posibles errores
        // almacenados durante una actualización.
        $errores =
            $_SESSION['perfil_errores'] ?? [];

        unset($_SESSION['perfil_errores']);


        // Renderiza la vista.
        $router = new Router();

        $router->render(
            'cuenta/perfil',
            [
                'usuario' => $usuario,
                'errores' => $errores
            ],
            false
        );
    }

    /**
     * Actualiza la información del perfil
     * del cliente autenticado.
     */
    public static function actualizarPerfil()
    {
        estaAutorizadoCliente();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /cuenta/perfil');
            exit;
        }
        $usuarioId = $_SESSION['usuario_id'];
        $usuario = Usuario::buscarPorId($usuarioId);
        if (!$usuario) {
            header('Location: /logout');
            exit;
        }
        $seccion = $_POST['seccion'] ?? '';
        /*
    |--------------------------------------------------------------------------
    | Datos personales
    |--------------------------------------------------------------------------
    */
        if ($seccion === 'personales') {
            $usuario->nombre =
                trim($_POST['nombre'] ?? '');
            $usuario->apellido =
                trim($_POST['apellido'] ?? '');
            $usuario->correo =
                trim($_POST['correo'] ?? '');
            $usuario->telefono =
                trim($_POST['telefono'] ?? '');
            $errores =
                $usuario->validar();
            /*
        |--------------------------------------------------------------------------
        | Verificar correo duplicado
        |--------------------------------------------------------------------------
        */
            if (empty($errores) && Usuario::correoExisteEnOtroUsuario(
                $usuario->correo,
                $usuarioId
            )) {
                $errores[] =
                    'El correo electrónico ya está registrado por otra cuenta.';
            }


            if (!empty($errores)) {
                $_SESSION['perfil_errores'] =
                    $errores;
                header('Location: /cuenta/perfil');
                exit;
            }


            if (!$usuario->guardar()) {
                $_SESSION['perfil_errores'] = [
                    'No fue posible actualizar los datos personales.'
                ];
                header('Location: /cuenta/perfil');
                exit;
            }
            // Actualizar información utilizada
            // durante la sesión.
            $_SESSION['nombre'] =
                $usuario->nombre;
            $_SESSION['perfil_exito'] =
                'Los datos personales se actualizaron correctamente.';
            header('Location: /cuenta/perfil');
            exit;
        }


        /*
    |--------------------------------------------------------------------------
    | Dirección
    |--------------------------------------------------------------------------
    */
        if ($seccion === 'direccion') {
            $usuario->calle =
                trim($_POST['calle'] ?? '');
            $usuario->numero =
                trim($_POST['numero'] ?? '');
            $usuario->colonia =
                trim($_POST['colonia'] ?? '');
            $usuario->municipio =
                trim($_POST['municipio'] ?? '');
            $usuario->estado =
                trim($_POST['estado'] ?? '');
            $usuario->codigo_postal =
                trim($_POST['codigo_postal'] ?? '');
            $errores =
                $usuario->validarDireccion();
            if (!empty($errores)) {
                $_SESSION['perfil_errores'] =
                    $errores;
                header('Location: /cuenta/perfil');
                exit;
            }
            if (!$usuario->guardar()) {
                $_SESSION['perfil_errores'] = [
                    'No fue posible actualizar la dirección.'
                ];
                header('Location: /cuenta/perfil');
                exit;
            }
            $_SESSION['perfil_exito'] =
                'La dirección se actualizó correctamente.';
            header('Location: /cuenta/perfil');
            exit;
        }
        /*
    |--------------------------------------------------------------------------
    | Sección no válida
    |--------------------------------------------------------------------------
    */
        $_SESSION['perfil_errores'] = [
            'La sección enviada no es válida.'
        ];
        header('Location: /cuenta/perfil');
        exit;
    }

    /**
     * Muestra el historial de pedidos
     * del cliente autenticado.
     */
    public static function pedidos()
    {
        // Verifica que exista un cliente autenticado.
        estaAutorizadoCliente();
        // Obtiene el ID del usuario.
        $usuarioId = $_SESSION['usuario_id'];

        // Obtiene el filtro enviado
        // desde la URL.
        $filtro = $_GET['estado'] ?? '';

        // Estados permitidos.
        $estadosPermitidos = [
            'pendiente' => 1,
            'pagado' => 2,
            'preparando' => 3,
            'enviado' => 4,
            'entregado' => 5,
            'cancelado' => 6
        ];
        // Determina el ID del estado.
        $estadoPedidoId = null;
        if (isset($estadosPermitidos[$filtro])) {
            $estadoPedidoId = $estadosPermitidos[$filtro];
        }
        // Obtiene los pedidos.
        $pedidos = Pedido::obtenerPorUsuarioEstado($usuarioId, $estadoPedidoId);
        // Renderiza la vista.
        $router = new Router();
        $router->render(
            'cuenta/pedidos',
            [
                'pedidos' => $pedidos,
                'filtro' => $filtro
            ],
            false
        );
    }

    /**
     * Muestra el detalle de un pedido
     * del cliente autenticado.
     */
    public static function pedido()
    {
        // Verifica que sea un cliente autenticado.
        estaAutorizadoCliente();
        // Obtiene el ID del usuario
        // directamente de la sesión.
        $usuarioId = $_SESSION['usuario_id'];
        // Obtiene el ID del pedido
        // enviado por la URL.
        $pedidoId = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
        // Si el ID no es válido,
        // regresamos al historial.
        if (!$pedidoId) {
            header('Location: /cuenta/pedidos');
            exit;
        }
        // Busca el pedido asegurándose
        // de que pertenezca al usuario.
        $pedido = Pedido::obtenerPorUsuarioPedido($pedidoId, $usuarioId);
        // Si no existe o no pertenece
        // al usuario autenticado,
        // no permitimos continuar.
        if (!$pedido) {
            header('Location: /cuenta/pedidos');
            exit;
        }
        // Obtiene los detalles del pedido.
        $detalles = DetallePedido::obtenerPorPedido($pedido->id);
        // Renderiza la vista.
        $router = new Router();
        $router->render(
            'cuenta/pedido',
            [
                'pedido' => $pedido,
                'detalles' => $detalles
            ],
            false
        );
    }

    /**
     * Agrega nuevamente al carrito
     * los productos de un pedido anterior.
     */
    public static function repetirPedido()
    {
        estaAutorizadoCliente();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /cuenta/pedidos');
            exit;
        }
        iniciarSesion();
        $usuarioId = $_SESSION['usuario_id'];
        $pedidoId = filter_var(
            $_POST['id'] ?? null,
            FILTER_VALIDATE_INT
        );
        if (!$pedidoId) {
            header('Location: /cuenta/pedidos');
            exit;
        }
        // Verifica que el pedido
        // pertenezca al usuario.
        $pedido = Pedido::obtenerPorUsuarioPedido($pedidoId, $usuarioId);

        if (!$pedido) {
            header('Location: /cuenta/pedidos');
            exit;
        }
        // Obtiene los productos
        // del pedido anterior.
        $detalles = DetallePedido::obtenerPorPedido($pedido->id);
        if (empty($detalles)) {
            header('Location: /cuenta/pedidos');
            exit;
        }
        // Inicializa el carrito.
        if (!isset($_SESSION['carrito'])) {
            $_SESSION['carrito'] = [];
        }
        // Agrega cada producto.
        foreach ($detalles as $detalle) {
            $producto = Producto::find($detalle->producto_id);
            // Si el producto ya no existe,
            // simplemente lo omitimos.
            if (!$producto) {
                $_SESSION['carrito_error'] =
                    'Algunos productos del pedido anterior '
                    . 'no pudieron agregarse por falta de stock.';
                continue;
            }
            // Cantidad solicitada.
            $cantidad = (int) $detalle->cantidad;
            // Cantidad que ya existe
            // en el carrito.
            $cantidadActual = $_SESSION['carrito'][$producto->id] ?? 0;
            // Nueva cantidad.
            $nuevaCantidad = $cantidadActual + $cantidad;
            // No superar stock.
            if ($nuevaCantidad > $producto->stock) {
                $nuevaCantidad =
                    $producto->stock;
                $_SESSION['carrito_error'] =
                    'Algunos productos del pedido anterior '
                    . 'no pudieron agregarse por falta de stock.';
            }
            // Si todavía existe stock,
            // guardar en el carrito.
            if ($nuevaCantidad > 0) {
                $_SESSION['carrito'][$producto->id] = $nuevaCantidad;
            }
        }
        // Regresa al carrito.
        header('Location: /carrito');
        exit;
    }
}

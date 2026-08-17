<?php

/**
 * Controlador:
 * Checkout
 *
 * Se encarga de preparar y procesar
 * el proceso de compra.
 */

class CheckoutController
{
    /**
     * Muestra la pantalla inicial
     * del checkout.
     */
    public static function index()
    {

        // Inicia la sesión si todavía
        // no existe una activa.
        iniciarSesion();
        // Obtiene el carrito almacenado
        // en la sesión.
        $carrito = $_SESSION['carrito'] ?? [];
        // Si el carrito está vacío,
        // no permite continuar al checkout.
        if (empty($carrito)) {
            // Regresa al carrito.
            header('Location: /carrito');
            exit;
        }
        // Obtiene el resumen actual del carrito.
        $resumen = CarritoController::obtenerResumen();
        // Si existen problemas de stock,
        // regresa al carrito.
        if ($resumen['hayProblemasStock']) {
            header('Location: /carrito');
            exit;
        }

        // Recorre los productos del carrito
        // para calcular su subtotal individual.
        foreach ($resumen['productos'] as $producto) {

            $producto->subtotal =
                (float) $producto->precio_actual
                * (int) $producto->cantidad;
        }
        // Variable que almacenará los datos
        // del cliente si existe una sesión.
        $cliente = null;
        // Verifica si existe un usuario
        // autenticado actualmente.
        if (usuarioAutenticado()) {
            // Por ahora solamente obtenemos
            // el ID almacenado en la sesión.
            $usuarioId = $_SESSION['usuario_id'] ?? null;
            // Si existe un ID válido,
            // buscamos al usuario.
            if ($usuarioId) {
                $cliente = Usuario::find($usuarioId);
            }
        }

        // Recupera los datos introducidos anteriormente
        // si existen.
        $checkoutDatos = $_SESSION['checkout_datos'] ?? [];

        // Recupera los errores de validación
        // si existen.
        $checkoutErrores = $_SESSION['checkout_errores'] ?? [];

        // Elimina los errores después de recuperarlos.
        // Así solamente se muestran una vez.
        unset($_SESSION['checkout_errores']);

        // Si el usuario está autenticado
        // y todavía no existen datos guardados
        // del checkout, usamos sus datos.
        if ($cliente && empty($checkoutDatos)) {

            $checkoutDatos = [
                'nombre' => $cliente->nombre,
                'apellido' => $cliente->apellido,
                'correo' => $cliente->correo,
                'telefono' => $cliente->telefono,
                'calle' => $cliente->calle,
                'numero' => $cliente->numero,
                'colonia' => $cliente->colonia,
                'municipio' => $cliente->municipio,
                'estado' => $cliente->estado,
                'codigo_postal' => $cliente->codigo_postal
            ];
        }

        // Elimina los errores después de recuperarlos.
        unset($_SESSION['checkout_errores']);
        // Crea una instancia del Router.
        $router = new Router();
        // Muestra la vista del checkout.
        $router->render(
            'checkout/index',
            [
                // Productos del carrito.
                'productos' => $resumen['productos'],
                // Total del carrito.
                'total' => $resumen['total'],
                // Cliente autenticado o null.
                'cliente' => $cliente,
                // Datos introducidos en el formulario.
                'checkoutDatos' => $checkoutDatos,
                // Errores encontrados.
                'checkoutErrores' => $checkoutErrores
            ],
            false
        );
    }

    /**
     * Procesa los datos enviados
     * desde el formulario del checkout.
     */
    public static function procesar()
    {
        // Verifica que la petición
        // realmente sea mediante POST.
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

            header('Location: /checkout');
            exit;
        }
        // Inicia la sesión si todavía
        // no existe una activa.
        iniciarSesion();
        // Obtiene el carrito actual.
        $carrito = $_SESSION['carrito'] ?? [];
        // Si el carrito está vacío,
        // no se puede continuar.
        if (empty($carrito)) {
            header('Location: /carrito');
            exit;
        }
        // Obtiene nuevamente el resumen
        // y verifica el stock actual.
        $resumen = CarritoController::obtenerResumen();
        // Si existen problemas de stock,
        // regresamos al carrito.
        if ($resumen['hayProblemasStock']) {
            $_SESSION['carrito_error'] =
                'Algunos productos ya no tienen suficiente stock.';
            header('Location: /carrito');
            exit;
        }
        /*
    |--------------------------------------------------------------------------
    | Obtener datos del formulario
    |--------------------------------------------------------------------------
    */
        $nombre = trim($_POST['nombre'] ?? '');
        $apellido = trim($_POST['apellido'] ?? '');
        $correo = trim($_POST['correo'] ?? '');
        $telefono = trim($_POST['telefono'] ?? '');

        $calle = trim($_POST['calle'] ?? '');
        $numero = trim($_POST['numero'] ?? '');
        $colonia = trim($_POST['colonia'] ?? '');
        $municipio = trim($_POST['municipio'] ?? '');
        $estado = trim($_POST['estado'] ?? '');
        $codigo_postal = trim($_POST['codigo_postal'] ?? '');
        /*
    |--------------------------------------------------------------------------
    | Validaciones básicas
    |--------------------------------------------------------------------------
    */
        $errores = [];
        // Nombre
        if ($nombre === '') {
            $errores[] =
                'El nombre es obligatorio.';
        }
        // Apellido
        if ($apellido === '') {
            $errores[] =
                'El apellido es obligatorio.';
        }
        // Correo
        if ($correo === '') {
            $errores[] =
                'El correo es obligatorio.';
        } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            $errores[] =
                'El correo electrónico no es válido.';
        }
        // Teléfono
        if ($telefono === '') {
            $errores[] =
                'El teléfono es obligatorio.';
        }
        // Calle
        if ($calle === '') {
            $errores[] =
                'La calle es obligatoria.';
        }
        // Número
        if ($numero === '') {
            $errores[] =
                'El número es obligatorio.';
        }
        // Colonia
        if ($colonia === '') {
            $errores[] =
                'La colonia es obligatoria.';
        }
        // Municipio
        if ($municipio === '') {
            $errores[] =
                'El municipio es obligatorio.';
        }
        // Estado
        if ($estado === '') {
            $errores[] =
                'El estado es obligatorio.';
        }
        // Código postal
        if ($codigo_postal === '') {
            $errores[] =
                'El código postal es obligatorio.';
        }
        /*
  /*
    |--------------------------------------------------------------------------
    | Guardar datos enviados
    |--------------------------------------------------------------------------
    */

        $_SESSION['checkout_datos'] = [

            'nombre' => $nombre,
            'apellido' => $apellido,
            'correo' => $correo,
            'telefono' => $telefono,

            'calle' => $calle,
            'numero' => $numero,
            'colonia' => $colonia,
            'municipio' => $municipio,
            'estado' => $estado,
            'codigo_postal' => $codigo_postal
        ];

        // Genera un token único para este checkout.
        //
        // Este token permitirá que la confirmación
        // solamente pueda utilizarse una vez.
        $_SESSION['checkout_token'] =
            bin2hex(random_bytes(32));
        if (!empty($errores)) {
            // Guardamos temporalmente los datos
            // para que el formulario pueda
            // conservar lo que escribió el usuario.
            // Guardamos los errores.
            $_SESSION['checkout_errores'] = $errores;
            // Regresamos al checkout.
            header('Location: /checkout');
            exit;
        }
        // Continuamos al siguiente paso.
        header('Location: /checkout/confirmar');
        exit;
    }

    /**
     * Muestra la pantalla de confirmación
     * antes de crear el pedido.
     */
    public static function confirmar()
    {
        // Inicia la sesión.
        iniciarSesion();

        // Obtiene el carrito actual.
        $carrito = $_SESSION['carrito'] ?? [];

        // Si el carrito está vacío,
        // regresa al carrito.
        if (empty($carrito)) {

            header('Location: /carrito');
            exit;
        }

        // Obtiene los datos de envío
        // previamente validados.
        $checkoutDatos =
            $_SESSION['checkout_datos'] ?? [];

        // Si todavía no existen datos de envío,
        // no permite entrar directamente
        // a la pantalla de confirmación.
        if (empty($checkoutDatos)) {

            header('Location: /checkout');
            exit;
        }

        // Obtiene nuevamente el resumen
        // actual del carrito.
        $resumen =
            CarritoController::obtenerResumen();

        // Verifica nuevamente el stock.
        if ($resumen['hayProblemasStock']) {

            $_SESSION['carrito_error'] =
                'Algunos productos ya no tienen suficiente stock.';

            header('Location: /carrito');
            exit;
        }

        // Obtiene los métodos de pago
        // registrados en la base de datos.
        $metodosPago = MetodoPago::all();

        // Obtiene el método seleccionado anteriormente,
        // si existe.
        $metodoPagoSeleccionado =
            $_SESSION['checkout_metodo_pago'] ?? null;

        // Obtiene los errores de confirmación,
        // si existen.
        $errores =
            $_SESSION['checkout_confirmacion_errores'] ?? [];

        // Elimina los errores después de recuperarlos.
        unset($_SESSION['checkout_confirmacion_errores']);


        // Crea una instancia del Router.
        $router = new Router();

        // Muestra la vista de confirmación.
        $router->render(
            'checkout/confirmar',
            [
                'productos' => $resumen['productos'],
                'total' => $resumen['total'],
                'checkoutDatos' => $checkoutDatos,
                'metodosPago' => $metodosPago,
                'metodoPagoSeleccionado' => $metodoPagoSeleccionado,
                'errores' => $errores
            ],
            false
        );
    }

    /**
     * Procesa la confirmación del checkout
     * y crea el pedido principal.
     *
     * En este sprint:
     * - Crea el registro en pedidos.
     * - NO crea detalle_pedido.
     * - NO modifica stock.
     * - NO vacía el carrito.
     */
    public static function procesarConfirmacion()
    {
        // Verifica que la petición
        // realmente sea mediante POST.
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

            header('Location: /checkout/confirmar');
            exit;
        }

        // Inicia la sesión.
        iniciarSesion();

        /*
|--------------------------------------------------------------------------
| Validar token del checkout
|--------------------------------------------------------------------------
*/

        // Obtiene el token enviado desde el formulario.
        $tokenEnviado =
            $_POST['checkout_token'] ?? null;

        // Obtiene el token válido almacenado
        // en la sesión.
        $tokenSesion =
            $_SESSION['checkout_token'] ?? null;


        // Si no existe alguno de los dos tokens,
        // el checkout ya no es válido.
        if (!$tokenEnviado || !$tokenSesion) {

            $_SESSION['checkout_confirmacion_errores'] = [
                'Este checkout ya no está disponible.'
            ];

            header('Location: /carrito');
            exit;
        }


        // Compara ambos tokens de forma segura.
        if (!hash_equals($tokenSesion, $tokenEnviado)) {

            $_SESSION['checkout_confirmacion_errores'] = [
                'La sesión del checkout ya no es válida.'
            ];

            header('Location: /carrito');
            exit;
        }

        /*
    |--------------------------------------------------------------------------
    | Verificar carrito
    |--------------------------------------------------------------------------
    */

        // Obtiene el carrito actual.
        $carrito = $_SESSION['carrito'] ?? [];

        // Si el carrito está vacío,
        // no se puede crear un pedido.
        if (empty($carrito)) {

            header('Location: /carrito');
            exit;
        }


        /*
    |--------------------------------------------------------------------------
    | Verificar datos del checkout
    |--------------------------------------------------------------------------
    */

        // Obtiene los datos de envío
        // que fueron validados previamente.
        $checkoutDatos =
            $_SESSION['checkout_datos'] ?? [];

        // Si no existen datos de checkout,
        // regresamos al formulario.
        if (empty($checkoutDatos)) {

            header('Location: /checkout');
            exit;
        }


        /*
    |--------------------------------------------------------------------------
    | Obtener método de pago
    |--------------------------------------------------------------------------
    */

        // Obtiene el método de pago
        // enviado desde el formulario.
        $metodoPagoId = filter_var(
            $_POST['metodo_pago_id'] ?? null,
            FILTER_VALIDATE_INT
        );


        /*
    |--------------------------------------------------------------------------
    | Validar método de pago
    |--------------------------------------------------------------------------
    */

        if (!$metodoPagoId) {

            $_SESSION['checkout_confirmacion_errores'] = [
                'Debes seleccionar un método de pago.'
            ];

            header('Location: /checkout/confirmar');
            exit;
        }


        // Busca el método de pago en la BD.
        $metodoPago =
            MetodoPago::find($metodoPagoId);


        // Si no existe,
        // no permitimos crear el pedido.
        if (!$metodoPago) {

            $_SESSION['checkout_confirmacion_errores'] = [
                'El método de pago seleccionado no es válido.'
            ];

            header('Location: /checkout/confirmar');
            exit;
        }


        /*
    |--------------------------------------------------------------------------
    | Verificar stock nuevamente
    |--------------------------------------------------------------------------
    */

        // Obtenemos nuevamente el resumen
        // directamente de la base de datos.
        $resumen =
            CarritoController::obtenerResumen();


        // Si algún producto ya no tiene
        // suficiente stock, detenemos el proceso.
        if ($resumen['hayProblemasStock']) {

            $_SESSION['carrito_error'] =
                'Algunos productos ya no tienen suficiente stock.';

            header('Location: /carrito');
            exit;
        }


        /*
    |--------------------------------------------------------------------------
    | Usuario
    |--------------------------------------------------------------------------
    */

        // Si existe un usuario autenticado,
        // obtenemos su ID.
        //
        // Si es invitado, será null.
        $usuarioId =
            $_SESSION['usuario_id'] ?? null;


        /*
    |--------------------------------------------------------------------------
    | Generar número de pedido
    |--------------------------------------------------------------------------
    */

        // Generamos un número único
        // para identificar visualmente el pedido.
        $numeroPedido =
            'PED-' .
            date('Ymd-His') .
            '-' .
            random_int(100, 999);


        /*
    |--------------------------------------------------------------------------
    | Crear pedido
    |--------------------------------------------------------------------------
    */

        $pedido = new Pedido([

            // Número público del pedido.
            'numero_pedido' =>
            $numeroPedido,

            // ID del usuario si está autenticado.
            // Será null para invitados.
            'usuario_id' =>
            $usuarioId,

            // Estado inicial del pedido:
            // 1 = Pendiente.
            'estado_pedido_id' =>
            1,

            // Estado inicial del pago:
            // 1 = Pendiente.
            'estado_pago_id' =>
            1,

            // Método seleccionado por el cliente.
            'metodo_pago_id' =>
            $metodoPagoId,

            // Datos confirmados durante checkout.
            'nombre' =>
            $checkoutDatos['nombre'],

            'apellido' =>
            $checkoutDatos['apellido'],

            'correo' =>
            $checkoutDatos['correo'],

            'telefono' =>
            $checkoutDatos['telefono'],

            'calle' =>
            $checkoutDatos['calle'],

            'numero' =>
            $checkoutDatos['numero'],

            'colonia' =>
            $checkoutDatos['colonia'],

            'municipio' =>
            $checkoutDatos['municipio'],

            'estado' =>
            $checkoutDatos['estado'],

            'codigo_postal' =>
            $checkoutDatos['codigo_postal'],

            // Actualmente no tenemos costo de envío,
            // por lo que subtotal y total son iguales.
            'subtotal' =>
            $resumen['total'],

            'total' =>
            $resumen['total'],

            // Todavía no existe una referencia
            // de pago real.
            'referencia_pago' =>
            null,

            // No tenemos notas todavía.
            'notas' =>
            null
        ]);


        /*
|--------------------------------------------------------------------------
| Iniciar transacción
|--------------------------------------------------------------------------
*/

        // A partir de este punto,
        // todas las operaciones forman
        // parte de una sola transacción.
        ActiveRecord::iniciarTransaccion();


        /*
    |--------------------------------------------------------------------------
    | Guardar pedido
    |--------------------------------------------------------------------------
    */

        // Inserta el pedido en la base de datos.
        $resultado =
            $pedido->guardar();
        /*
    |--------------------------------------------------------------------------
    | Verificar resultado
    |--------------------------------------------------------------------------
    */

        if (!$resultado) {

            // Revierte cualquier cambio realizado.
            ActiveRecord::revertirTransaccion();

            $_SESSION['checkout_confirmacion_errores'] = [
                'No fue posible crear el pedido.'
            ];

            header('Location: /checkout/confirmar');
            exit;
        }

        /*
|--------------------------------------------------------------------------
| Crear detalles del pedido
|--------------------------------------------------------------------------
*/

        // Utilizamos los productos obtenidos
        // del resumen actualizado del carrito.
        $detallesCreados =
            self::crearDetallesPedido(
                $pedido,
                $resumen['productos']
            );


        // Si algún detalle no pudo crearse,
        // detenemos el proceso.
        if (!$detallesCreados) {

            $_SESSION['checkout_confirmacion_errores'] = [
                'El pedido fue creado, pero no fue posible registrar todos sus productos.'
            ];

            header('Location: /checkout/confirmar');
            exit;
        }

        /*
|--------------------------------------------------------------------------
| Actualizar stock
|--------------------------------------------------------------------------
*/

        $stockActualizado =
            self::reducirStockProductos(
                $resumen['productos']
            );


        // Si algún producto no pudo
        // actualizar su stock,
        // cancelamos toda la operación.
        if (!$stockActualizado) {

            ActiveRecord::revertirTransaccion();

            $_SESSION['checkout_confirmacion_errores'] = [
                'Uno o más productos ya no tienen suficiente stock.'
            ];

            header('Location: /carrito');
            exit;
        }


        /*
|--------------------------------------------------------------------------
| Confirmar transacción
|--------------------------------------------------------------------------
*/

        // Todas las operaciones fueron exitosas.
        ActiveRecord::confirmarTransaccion();

        /*
|--------------------------------------------------------------------------
| Pedido completado
|--------------------------------------------------------------------------
*/

        // El pedido y todos sus detalles
        // fueron creados correctamente.
        //
        // Ya no necesitamos conservar
        // el carrito utilizado para esta compra.
        unset($_SESSION['carrito']);
        unset($_SESSION['checkout_datos']);
        unset($_SESSION['checkout_metodo_pago']);

        /*
|--------------------------------------------------------------------------
| Consumir token del checkout
|--------------------------------------------------------------------------
*/

        // El token solamente puede utilizarse
        // una vez para crear un pedido.
        unset($_SESSION['checkout_token']);


        /*
    |--------------------------------------------------------------------------
    | Pedido creado correctamente
    |--------------------------------------------------------------------------
    

        // Por ahora mostramos el número
        // para comprobar que el INSERT
        // funcionó correctamente.
        echo "
        <h1>Pedido creado correctamente</h1>

        <p>
            Número de pedido:
            <strong>
                " . htmlspecialchars($pedido->numero_pedido) . "
            </strong>
        </p>
    ";

        exit;*/


        $_SESSION['pedido_exitoso'] = [
            'id' => $pedido->id,
            'numero_pedido' => $pedido->numero_pedido,
            'total' => $pedido->total
        ];

        header('Location: /checkout/exitoso');
        exit;
    }

    /**
     * Crea los detalles correspondientes
     * a los productos del carrito.
     *
     * @param Pedido $pedido
     * @param array $productos
     * @return bool
     */
    private static function crearDetallesPedido($pedido, $productos)
    {
        // Recorre los productos que forman
        // parte del carrito.
        foreach ($productos as $producto) {
            // Obtiene la cantidad solicitada.
            $cantidad =
                (int) $producto->cantidad;
            // Calcula el subtotal del producto.
            $subtotal =
                (float) $producto->precio_actual
                * $cantidad;
            /*
        |--------------------------------------------------------------------------
        | Crear detalle
        |--------------------------------------------------------------------------
        */
            $detalle = new DetallePedido([
                // ID del pedido recién creado.
                'pedido_id' =>
                $pedido->id,
                // ID actual del producto.
                'producto_id' =>
                $producto->id,
                // Guardamos el SKU histórico.
                'sku' =>
                $producto->sku,
                // Guardamos el nombre histórico.
                'nombre_producto' =>
                $producto->nombre,
                // Cantidad comprada.
                'cantidad' =>
                $cantidad,
                // Precio al momento de la compra.
                'precio_unitario' =>
                $producto->precio_actual,
                // Subtotal de ese producto.
                'subtotal' =>
                $subtotal
            ]);

            /*
        |--------------------------------------------------------------------------
        | Guardar detalle
        |--------------------------------------------------------------------------
        */
            $resultado =
                $detalle->guardar();
            /*
        |--------------------------------------------------------------------------
        | Verificar resultado
        |--------------------------------------------------------------------------
        */
            if (!$resultado) {
                // Si un detalle no pudo guardarse,
                // detenemos el proceso.
                return false;
            }
        }
        // Todos los detalles fueron creados.
        return true;
    }


    /**
     * Muestra la pantalla de pedido realizado.
     */
    public static function exitoso()
    {
        // Inicia la sesión.
        iniciarSesion();
        /*
    |--------------------------------------------------------------------------
    | Obtener información del pedido
    |--------------------------------------------------------------------------
    */
        $pedido =
            $_SESSION['pedido_exitoso'] ?? null;
        /*
    |--------------------------------------------------------------------------
    | Verificar que exista un pedido
    |--------------------------------------------------------------------------
    */

        // Si alguien intenta entrar directamente
        // a /checkout/exitoso sin haber realizado
        // una compra, lo regresamos al catálogo.
        if (!$pedido) {
            header('Location: /productos');
            exit;
        }

        /*
    |--------------------------------------------------------------------------
    | Mostrar vista
    |--------------------------------------------------------------------------
    */
        $router = new Router();
        $router->render(
            'checkout/exitoso',
            [
                'pedido' => $pedido
            ],
            false
        );
        /*
    |--------------------------------------------------------------------------
    | Consumir información temporal
    |--------------------------------------------------------------------------
    */
        // Eliminamos la información después
        // de preparar la vista.
        unset($_SESSION['pedido_exitoso']);
    }


    /**
     * Reduce el stock de todos los productos
     * incluidos en el pedido.
     *
     * @param array $productos
     * @return bool
     */
    private static function reducirStockProductos($productos)
    {
        // Recorremos todos los productos
        // incluidos en el carrito.
        foreach ($productos as $producto) {

            // Obtiene la cantidad comprada.
            $cantidad =
                (int) $producto->cantidad;

            // Intenta reducir el stock.
            $resultado =
                Producto::reducirStock(
                    $producto->id,
                    $cantidad
                );

            // Si algún producto no pudo
            // actualizar su stock,
            // detenemos el proceso.
            if (!$resultado) {
                return false;
            }
        }

        // Todos los productos
        // actualizaron correctamente su stock.
        return true;
    }
}

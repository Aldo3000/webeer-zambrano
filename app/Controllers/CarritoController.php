<?php

/**
 * Controlador encargado de administrar
 * las operaciones relacionadas con el carrito.
 */
class CarritoController
{
    /**
     * Muestra el contenido actual del carrito.
     */
    public static function index()
    {
        // Inicia la sesión si todavía no existe una activa.
        iniciarSesion();
        // Obtiene el carrito almacenado en la sesión.
        // Si todavía no existe, utiliza un arreglo vacío.
        $carrito = $_SESSION['carrito'] ?? [];
        // Arreglo donde almacenaremos los productos
        // que realmente existen en la base de datos.
        $productos = [];
        // Total general del carrito.
        $total = 0;
        // Indica si existe algún producto
        // con problemas de stock.
        $hayProblemasStock = false;
        // Recorre los productos almacenados en el carrito.
        foreach ($carrito as $id => $cantidad) {
            // Busca el producto correspondiente en la base de datos.
            $producto = Producto::find($id);
            // Si el producto ya no existe,
            // lo ignoramos temporalmente.
            if (!$producto) {
                continue;
            }
            // Guarda la cantidad solicitada
            // dentro del objeto.
            $producto->cantidad = $cantidad;
            // Verifica si la cantidad solicitada
            // supera el stock actual.
            if ($cantidad > $producto->stock) {
                // Marca el producto como
                // insuficiente en stock.
                $producto->stock_insuficiente = true;
                // Indica que existe al menos
                // un problema en el carrito.
                $hayProblemasStock = true;
            } else {
                // El producto tiene stock suficiente.
                $producto->stock_insuficiente = false;
                // Calcula el subtotal solamente
                // si la cantidad es válida.
                $subtotal =
                    (float) $producto->precio_actual
                    * (int) $cantidad;
                // Acumula el subtotal al total general.
                $total += $subtotal;
            }

            // Agrega el producto al arreglo.
            $productos[] = $producto;
        }
        // Crea una instancia del Router.
        $router = new Router();
        // Muestra la vista del carrito.
        $router->render(
            'carrito/index',
            [
                'productos' => $productos,
                'total' => $total,
                'hayProblemasStock' => $hayProblemasStock
            ],
            false
        );
    }

    /**
     * Agrega un producto al carrito.
     */
    public static function agregar()
    {
        // Verifica que la petición sea mediante POST.
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /carrito');
            exit;
        }

        // Inicia la sesión si todavía no existe una activa.
        iniciarSesion();

        // Obtiene el ID del producto enviado por el formulario.
        $id = filter_var(
            $_POST['id'] ?? null,
            FILTER_VALIDATE_INT
        );

        // Si el ID no es válido,
        // regresa al catálogo.
        if (!$id) {
            header('Location: /carrito');
            exit;
        }

        // Busca el producto en la base de datos.
        $producto = Producto::find($id);

        // Si el producto no existe,
        // regresa al catálogo.
        if (!$producto) {
            header('Location: /carrito');
            exit;
        }

        // Si el producto no tiene stock disponible,
        // no permite agregarlo al carrito.
        if ($producto->stock <= 0) {

            // Guarda un mensaje temporal en la sesión.
            $_SESSION['carrito_error'] =
                'Este producto actualmente está agotado.';

            // Regresa al showroom.
            header('Location: /carrito');
            exit;
        }

        // Si todavía no existe un carrito en la sesión,
        // crea uno vacío.
        if (!isset($_SESSION['carrito'])) {
            $_SESSION['carrito'] = [];
        }

        // Obtiene la cantidad actual del producto
        // dentro del carrito.
        $cantidadActual = $_SESSION['carrito'][$id] ?? 0;

        // Calcula la cantidad que tendría
        // después de agregar una unidad.
        $nuevaCantidad = $cantidadActual + 1;

        // Verifica que la nueva cantidad
        // no supere el stock disponible.
        if ($nuevaCantidad > $producto->stock) {

            // Guarda un mensaje temporal en la sesión.
            $_SESSION['carrito_error'] =
                'No hay suficiente stock disponible para este producto.';

            // Regresa al showroom.
            header('Location: /carrito');
            exit;
        }

        // Actualiza la cantidad del producto
        // dentro del carrito.
        $_SESSION['carrito'][$id] = $nuevaCantidad;

        // Regresa al showroom.
        header('Location: /productos');
        exit;
    }

    /**
     * Aumenta en una unidad la cantidad
     * de un producto dentro del carrito.
     */
    public static function aumentar()
    {
        // Verifica que la petición sea mediante POST.
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /carrito');
            exit;
        }

        // Inicia la sesión si todavía no existe una activa.
        iniciarSesion();

        // Obtiene el ID enviado desde el formulario.
        $id = filter_var(
            $_POST['id'] ?? null,
            FILTER_VALIDATE_INT
        );

        // Si el ID no es válido,
        // regresa al carrito.
        if (!$id) {
            header('Location: /carrito');
            exit;
        }

        // Verifica que el producto exista
        // dentro del carrito.
        if (isset($_SESSION['carrito'][$id])) {

            // Busca el producto en la base de datos.
            $producto = Producto::find($id);

            // Si el producto ya no existe,
            // elimina su referencia del carrito.
            if (!$producto) {

                unset($_SESSION['carrito'][$id]);

                $_SESSION['carrito_error'] =
                    'El producto ya no está disponible.';

                header('Location: /carrito');
                exit;
            }

            // Calcula la nueva cantidad.
            $nuevaCantidad =
                $_SESSION['carrito'][$id] + 1;

            // Verifica el stock disponible.
            if ($nuevaCantidad > $producto->stock) {

                $_SESSION['carrito_error'] =
                    'No hay suficiente stock disponible.';

                header('Location: /carrito');
                exit;
            }

            // Actualiza la cantidad.
            $_SESSION['carrito'][$id] = $nuevaCantidad;
            header('Location: /carrito');
            exit;
        }
    }

    /**
     * Disminuye en una unidad la cantidad
     * de un producto dentro del carrito.
     */
    public static function disminuir()
    {
        // Verifica que la petición sea mediante POST.
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /carrito');
            exit;
        }

        // Inicia la sesión si todavía no existe una activa.
        iniciarSesion();

        // Obtiene el ID enviado desde el formulario.
        $id = filter_var(
            $_POST['id'] ?? null,
            FILTER_VALIDATE_INT
        );

        // Si el ID no es válido,
        // regresa al carrito.
        if (!$id) {
            header('Location: /carrito');
            exit;
        }

        // Verifica que el producto exista
        // dentro del carrito.
        if (isset($_SESSION['carrito'][$id])) {

            // Si hay más de una unidad,
            // disminuye la cantidad.
            if ($_SESSION['carrito'][$id] > 1) {

                $_SESSION['carrito'][$id]--;
            } else {

                // Si solamente queda una unidad,
                // elimina el producto del carrito.
                unset($_SESSION['carrito'][$id]);
            }
        }

        // Regresa al carrito.
        header('Location: /carrito');
        exit;
    }

    /**
     * Elimina completamente un producto
     * del carrito.
     */
    public static function eliminar()
    {
        // Verifica que la petición sea mediante POST.
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /carrito');
            exit;
        }

        // Inicia la sesión si todavía no existe una activa.
        iniciarSesion();

        // Obtiene el ID enviado desde el formulario.
        $id = filter_var(
            $_POST['id'] ?? null,
            FILTER_VALIDATE_INT
        );

        // Si el ID no es válido,
        // regresa al carrito.
        if (!$id) {
            header('Location: /carrito');
            exit;
        }

        // Elimina el producto del carrito.
        unset($_SESSION['carrito'][$id]);

        // Regresa al carrito.
        header('Location: /carrito');
        exit;
    }

    /**
     * Vacía completamente el carrito
     * almacenado en la sesión.
     */
    public static function vaciar()
    {
        // Verifica que la petición sea mediante POST.
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /carrito');
            exit;
        }

        // Inicia la sesión si todavía no existe una activa.
        iniciarSesion();

        // Elimina todos los productos del carrito.
        $_SESSION['carrito'] = [];

        // Regresa al carrito.
        header('Location: /carrito');
        exit;
    }

    /**
     * Obtiene la información necesaria para
     * mostrar el resumen del carrito.
     *
     * Este método es utilizado por otras vistas
     * que necesitan consultar el estado actual
     * del carrito sin mostrar la vista completa.
     *
     * @return array
     */
    public static function obtenerResumen()
    {
        // Inicia la sesión si todavía no existe una activa.
        iniciarSesion();

        // Obtiene el carrito almacenado en la sesión.
        $carrito = $_SESSION['carrito'] ?? [];

        // Arreglo donde almacenaremos
        // los productos del carrito.
        $productos = [];

        // Total general del carrito.
        $total = 0;

        // Cantidad total de unidades.
        $cantidadTotal = 0;

        // Indica si existe algún producto
        // con problemas de stock.
        $hayProblemasStock = false;

        // Recorre los productos almacenados
        // dentro del carrito.
        foreach ($carrito as $id => $cantidad) {

            // Busca el producto en la base de datos.
            $producto = Producto::find($id);

            // Si el producto ya no existe,
            // lo ignoramos.
            if (!$producto) {
                continue;
            }

            // Guarda la cantidad del carrito
            // dentro del objeto Producto.
            $producto->cantidad = $cantidad;

            // Acumula la cantidad total de unidades.
            $cantidadTotal += (int) $cantidad;

            // Verifica si la cantidad solicitada
            // supera el stock disponible.
            if ($cantidad > $producto->stock) {

                // Marca el producto como
                // insuficiente en stock.
                $producto->stock_insuficiente = true;

                // Indica que existe un problema.
                $hayProblemasStock = true;
            } else {

                // El producto tiene stock suficiente.
                $producto->stock_insuficiente = false;

                // Calcula el subtotal.
                $subtotal =
                    (float) $producto->precio_actual
                    * (int) $cantidad;

                // Acumula el subtotal.
                $total += $subtotal;
            }

            // Agrega el producto al resumen.
            $productos[] = $producto;
        }

        // Devuelve la información del carrito.
        return [
            'productos' => $productos,
            'total' => $total,
            'cantidadTotal' => $cantidadTotal,
            'hayProblemasStock' => $hayProblemasStock
        ];
    }
}

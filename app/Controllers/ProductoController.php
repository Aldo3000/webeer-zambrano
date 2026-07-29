<?php

/**
 * Controlador encargado de administrar
 * las operaciones relacionadas con los productos.
 */
class ProductoController
{
    /**
     * Muestra el listado de productos.
     */
    public static function index()
    {
        // Solicita al modelo todos los productos registrados.
        $productos = Producto::all();

        // Crea una instancia del Router para poder renderizar una vista.
        $router = new Router();

        // Envía la lista de productos a la vista "productos/index".
        $router->render('productos/index', [
            // La vista recibirá una variable llamada $productos.
            'productos' => $productos
        ]);
    }

    /**
     * Muestra el formulario para crear un producto
     * y procesa el registro cuando el usuario envía
     * la información mediante una petición POST.
     */
    public static function crear()
    {
        // Crea un objeto Producto vacío que será utilizado
        // para llenar el formulario o conservar los datos
        // cuando existan errores de validación.
        $producto = new Producto();

        // Obtiene el arreglo de errores del modelo.
        // La primera vez estará vacío.
        $errores = Producto::getErrores();

        // Obtiene todas las categorías.
        $categorias = Categoria::all();

        // Obtiene todas las marcas.
        $marcas = Marca::all();

        // Verifica si el formulario fue enviado.
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Obtiene únicamente la información del producto
            // enviada desde el formulario.
            $datos = $_POST['producto'];

            // Copia los valores recibidos hacia el objeto Producto.
            $producto->sincronizar($datos);

            // Ejecuta las reglas de validación definidas
            // dentro del modelo Producto.
            $errores = $producto->validar();

            // Si no existen errores de validación...
            if (empty($errores)) {

                // Guarda el producto en la base de datos.
                $producto->guardar();

                // Redirecciona al listado para evitar que
                // el navegador vuelva a enviar el formulario
                // si el usuario actualiza la página.
                header('Location: /admin/productos');
                exit;
            }
        }

        // Crea una instancia del Router para renderizar la vista.
        $router = new Router();

        // Muestra la vista del formulario de creación.
        $router->render('productos/crear', [

            // Envía el objeto Producto para llenar
            // automáticamente los campos del formulario.
            'producto' => $producto,

            // Envía los mensajes de error, si existen.
            'errores' => $errores,

            'categorias' => $categorias,

            'marcas' => $marcas
        ]);
    }
    /**
     * Muestra el formulario de edición
     * y procesa la actualización del producto.
     */
    public static function editar()
    {
        // Obtiene el ID enviado mediante la URL
        // y verifica que sea un número entero válido.
        $id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);

        // Si el ID no es válido, regresa al listado.
        if (!$id) {
            header('Location: /admin/productos');
            exit;
        }

        // Busca el producto correspondiente en la base de datos.
        $producto = Producto::find($id);

        // Si el producto no existe,
        // vuelve al listado principal.
        if (!$producto) {
            header('Location: /admin/productos');
            exit;
        }

        // Temporalmente mostramos el objeto para comprobar
        // que la búsqueda funciona correctamente.
        /*echo '<pre>';
        var_dump($producto);
        echo '</pre>';*/

        // Obtiene el arreglo de errores.
        $errores = Producto::getErrores();
        $categorias = Categoria::all();
        $marcas = Marca::all();

        // Verifica si el usuario envió el formulario.
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Obtiene únicamente la información del producto
            // enviada desde el formulario.
            $datos = $_POST['producto'];

            // Actualiza las propiedades del objeto Producto
            // con los nuevos valores enviados.
            $producto->sincronizar($datos);

            // Ejecuta nuevamente las validaciones.
            $errores = $producto->validar();

            // Si no existen errores...
            if (empty($errores)) {

                // Guarda los cambios realizados.
                $producto->guardar();

                // Regresa al listado de productos.
                header('Location: /admin/productos');
                exit;
            }
        }

        // Crea una instancia del Router.
        $router = new Router();

        // Muestra la vista de edición.
        $router->render('productos/editar', [

            // Envía el producto encontrado
            // para llenar el formulario.
            'producto' => $producto,
            'categorias' => $categorias,
            'marcas' => $marcas,

            // Envía los errores de validación.
            'errores' => $errores
        ]);
    }

    /**
     * Elimina un producto.
     */
    public static function eliminar()
    {
        // Solo permite eliminar mediante una petición POST.
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Obtiene el ID enviado desde el formulario.
            $id = $_POST['id'] ?? null;

            // Verifica que exista un ID.
            if ($id) {

                // Busca el producto correspondiente.
                $producto = Producto::find($id);

                // Si el producto existe...
                if ($producto) {

                    // Elimina el registro de la base de datos.
                    $producto->delete();
                }
            }
        }

        // Regresa al listado de productos.
        header('Location: /admin/productos');
    }
}

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
     * Muestra el formulario de creación
     * y procesa el registro del producto.
     */
    public static function crear()
    {
        // Crea un objeto vacío que será utilizado por el formulario.
        $producto = new Producto();

        // Obtiene un arreglo vacío de errores (o errores previos).
        $errores = Producto::getErrores();

        // Verifica si el formulario fue enviado.
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Crea un objeto Producto utilizando la información del formulario.
            $producto = new Producto($_POST);

            // Ejecuta las validaciones definidas en el modelo.
            $errores = $producto->validar();

            // Si no existen errores...
            if (empty($errores)) {

                // Guarda el producto en la base de datos.
                $producto->guardar();

                // Redirecciona al listado para evitar reenviar el formulario.
                header('Location: /admin/productos');
            }
        }

        // Crea una instancia del Router.
        $router = new Router();

        // Muestra el formulario de creación.
        $router->render('productos/crear', [

            // Envía el objeto Producto a la vista.
            'producto' => $producto,

            // Envía los errores de validación.
            'errores' => $errores
        ]);
    }

    /**
     * Muestra el formulario de edición
     * y procesa la actualización.
     */
    public static function editar()
    {
        // Obtiene el ID enviado por la URL.
        $id = $_GET['id'] ?? null;

        // Si no existe un ID válido, vuelve al listado.
        if (!$id) {
            header('Location: /admin/productos');
        }

        // Busca el producto correspondiente en la base de datos.
        $producto = Producto::find($id);

        // Inicializa el arreglo de errores.
        $errores = Producto::getErrores();

        // Si el usuario envió el formulario...
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Actualiza el objeto con la nueva información.
            $producto->sincronizar($_POST);

            // Ejecuta nuevamente las validaciones.
            $errores = $producto->validar();

            // Si todo es correcto...
            if (empty($errores)) {

                // Guarda los cambios realizados.
                $producto->guardar();

                // Regresa al listado.
                header('Location: /admin/productos');
            }
        }

        // Crea una instancia del Router.
        $router = new Router();

        // Muestra el formulario de edición.
        $router->render('productos/editar', [

            // Envía el producto encontrado.
            'producto' => $producto,

            // Envía los posibles errores.
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

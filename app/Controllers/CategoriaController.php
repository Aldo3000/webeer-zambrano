<?php

/**
 * Controlador encargado de administrar
 * las operaciones relacionadas con los productos.
 */
class CategoriaController
{
    /**
     * Muestra el listado de productos.
     */
    public static function index()
    {
        // Verifica que el usuario esté autenticado.
        estaAutenticado();
        // Solicita al modelo todos los productos registrados.
        $categorias = Categoria::all();

        // Crea una instancia del Router para poder renderizar una vista.
        $router = new Router();

        // // Envía la lista de categorías a la vista.
        $router->render('categorias/index', [
            // La vista recibirá una variable llamada $categorias.
            'categorias' => $categorias
        ]);
    }

    /**
     * Muestra el formulario para crear un categoria
     * y procesa el registro cuando el usuario envía
     * la información mediante una petición POST.
     */
    public static function crear()
    {
        // Crea un objeto categoria vacío que será utilizado
        // para llenar el formulario o conservar los datos
        // cuando existan errores de validación.
        $categoria = new Categoria();

        // Obtiene el arreglo de errores del modelo.
        // La primera vez estará vacío.
        $errores = Categoria::getErrores();

        // Verifica si el formulario fue enviado.
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Obtiene únicamente la información del producto
            // enviada desde el formulario.
            $datos = $_POST['categoria'];

            // Copia los datos al objeto Categoria.
            $categoria->sincronizar($datos);

            // Ejecuta las reglas de validación definidas
            // dentro del modelo categoria.
            $errores = $categoria->validar();

            // Si no existen errores de validación...
            if (empty($errores)) {

                // Guarda el producto en la base de datos.
                $resultado = $categoria->guardar();

                // Redirecciona al listado para evitar que
                // el navegador vuelva a enviar el formulario
                // si el usuario actualiza la página.
                if ($resultado) {
                    header('Location: /admin/categorias?resultado=1');
                    exit;
                }
            }
        }

        // Crea una instancia del Router para renderizar la vista.
        $router = new Router();

        // Muestra la vista del formulario de creación.
        $router->render('categorias/crear', [

            // Envía el objeto categoria para llenar
            // automáticamente los campos del formulario.
            'categorias' => $categoria,

            // Envía los mensajes de error, si existen.
            'errores' => $errores
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
            header('Location: /admin/categorias');
            exit;
        }

        // Busca la categoria correspondiente en la base de datos.
        $categoria = Categoria::find($id);

        // Si la categoria no existe,
        // vuelve al listado principal.
        if (!$categoria) {
            header('Location: /admin/categorias');
            exit;
        }

        // Temporalmente mostramos el objeto para comprobar
        // que la búsqueda funciona correctamente.
        /*echo '<pre>';
        var_dump($categoria);
        echo '</pre>';*/

        // Obtiene el arreglo de errores.
        $errores = Categoria::getErrores();

        // Verifica si el usuario envió el formulario.
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Obtiene únicamente la información del producto
            // enviada desde el formulario.
            $datos = $_POST['categoria'];

            // Actualiza las propiedades del objeto Producto
            // con los nuevos valores enviados.
            $categoria->sincronizar($datos);

            // Ejecuta nuevamente las validaciones.
            $errores = $categoria->validar();

            // Si no existen errores...
            if (empty($errores)) {

                // Guarda los cambios realizados.
                $resultado = $categoria->guardar();

                // Regresa al listado de productos.
                if ($resultado) {
                    header('Location: /admin/categorias?resultado=2');
                    exit;
                }
            }
        }

        // Crea una instancia del Router.
        $router = new Router();

        // Muestra la vista de edición.
        $router->render('categorias/editar', [

            // Envía la categoria encontrado
            // para llenar el formulario.
            'categorias' => $categoria,

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
                $categoria = Categoria::find($id);

                // Si el producto existe...
                if ($categoria) {

                    // Elimina el registro de la base de datos.
                    $resultado = $categoria->delete();

                    if ($resultado) {
                        header('Location: /admin/categorias?resultado=3');
                        exit;
                    }
                }
            }
        }

        // Regresa al listado de productos.
        header('Location: /admin/categorias');
    }
}

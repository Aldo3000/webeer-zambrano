<?php

/**
 * Controlador encargado de administrar
 * las operaciones relacionadas con las marcas.
 */
class MarcaController
{
    /**
     * Muestra el listado de marcas.
     */
    public static function index()
    {
        // Verifica que el usuario esté autenticado.
        estaAutenticado();
        // Solicita al modelo todas las marcas registradas.
        $marcas = Marca::all();

        // Crea una instancia del Router para poder renderizar la vista.
        $router = new Router();

        // Envía la lista de marcas a la vista.
        $router->render('marcas/index', [

            // La vista recibirá una variable llamada $marcas.
            'marcas' => $marcas
        ]);
    }

    /**
     * Muestra el formulario para crear una marca
     * y procesa el registro cuando el usuario envía
     * la información mediante una petición POST.
     */
    public static function crear()
    {
        // Crea un objeto Marca vacío que será utilizado
        // para llenar el formulario o conservar los datos
        // cuando existan errores de validación.
        $marca = new Marca();

        // Obtiene el arreglo de errores del modelo.
        // La primera vez estará vacío.
        $errores = Marca::getErrores();

        // Verifica si el formulario fue enviado.
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Obtiene únicamente la información de la marca
            // enviada desde el formulario.
            $datos = $_POST['marca'];

            // Copia los datos al objeto Marca.
            $marca->sincronizar($datos);

            // Ejecuta las reglas de validación definidas
            // dentro del modelo Marca.
            $errores = $marca->validar();

            // Si no existen errores de validación...
            if (empty($errores)) {

                // Guarda la marca en la base de datos.
                $resultado = $marca->guardar();

                // Redirecciona al listado para evitar que
                // el navegador vuelva a enviar el formulario
                // si el usuario actualiza la página.
                if ($resultado) {
                    header('Location: /admin/marcas?resultado=1');
                    exit;
                }
            }
        }

        // Crea una instancia del Router para renderizar la vista.
        $router = new Router();

        // Muestra la vista del formulario de creación.
        $router->render('marcas/crear', [

            // Envía el objeto Marca para llenar
            // automáticamente los campos del formulario.
            'marca' => $marca,

            // Envía los mensajes de error, si existen.
            'errores' => $errores
        ]);
    }

    /**
     * Muestra el formulario de edición
     * y procesa la actualización de la marca.
     */
    public static function editar()
    {
        // Obtiene el ID enviado mediante la URL
        // y verifica que sea un número entero válido.
        $id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);

        // Si el ID no es válido, regresa al listado.
        if (!$id) {
            header('Location: /admin/marcas');
            exit;
        }

        // Busca la marca correspondiente en la base de datos.
        $marca = Marca::find($id);

        // Si la marca no existe,
        // vuelve al listado principal.
        if (!$marca) {
            header('Location: /admin/marcas');
            exit;
        }

        // Obtiene el arreglo de errores.
        $errores = Marca::getErrores();

        // Verifica si el usuario envió el formulario.
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Obtiene únicamente la información de la marca
            // enviada desde el formulario.
            $datos = $_POST['marca'];

            // Actualiza las propiedades del objeto Marca
            // con los nuevos valores enviados.
            $marca->sincronizar($datos);

            // Ejecuta nuevamente las validaciones.
            $errores = $marca->validar();

            // Si no existen errores...
            if (empty($errores)) {

                // Guarda los cambios realizados.
                $resultado = $marca->guardar();

                // Regresa al listado de marcas.
                if ($resultado) {
                    header('Location: /admin/marcas?resultado=2');
                    exit;
                }
            }
        }

        // Crea una instancia del Router.
        $router = new Router();

        // Muestra la vista de edición.
        $router->render('marcas/editar', [

            // Envía la marca encontrada
            // para llenar el formulario.
            'marca' => $marca,

            // Envía los errores de validación.
            'errores' => $errores
        ]);
    }

    /**
     * Elimina una marca.
     */
    public static function eliminar()
    {
        // Solo permite eliminar mediante una petición POST.
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Obtiene el ID enviado desde el formulario.
            $id = $_POST['id'] ?? null;

            // Verifica que exista un ID.
            if ($id) {

                // Busca la marca correspondiente.
                $marca = Marca::find($id);

                // Si la marca existe...
                if ($marca) {

                    // Elimina el registro de la base de datos.
                    $resultado = $marca->delete();

                    if ($resultado) {
                        header('Location: /admin/marcas?resultado=3');
                        exit;
                    }
                }
            }
        }

        // Regresa al listado de marcas.
        header('Location: /admin/marcas');
        exit;
    }
}

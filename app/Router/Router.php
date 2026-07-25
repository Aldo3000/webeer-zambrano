<?php

/**
 * Clase Router
 *
 * Administra las rutas de la aplicación y dirige
 * cada petición al controlador correspondiente.
 */
class Router
{
    /**
     * Almacena todas las rutas GET registradas.
     *
     * @var array
     */
    protected $rutasGET = [];

    /**
     * Almacena todas las rutas POST registradas.
     *
     * @var array
     */
    protected $rutasPOST = [];



    /**
     * Registra una ruta de tipo GET.
     *
     * @param string $url Ruta que se desea registrar.
     * @param callable|array $fn Función o controlador que se ejecutará.
     */
    public function get($url, $fn)
    {
        $this->rutasGET[$url] = $fn;
    }

    /**
     * Registra una ruta de tipo POST.
     *
     * @param string $url Ruta que se desea registrar.
     * @param callable|array $fn Función o controlador que se ejecutará.
     */
    public function post($url, $fn)
    {
        $this->rutasPOST[$url] = $fn;
    }

    /**
     * Comprueba la ruta solicitada por el usuario y ejecuta
     * el controlador correspondiente.
     */
    public function comprobarRutas()
    {
        // Obtener la URL solicitada
        $urlActual = $_SERVER['PATH_INFO'] ?? '/';

        // Obtener el método HTTP
        $metodo = $_SERVER['REQUEST_METHOD'];

        // Seleccionar las rutas correspondientes
        if ($metodo === 'GET') {
            $fn = $this->rutasGET[$urlActual] ?? null;
        } else {
            $fn = $this->rutasPOST[$urlActual] ?? null;
        }

        // Si la ruta existe
        if ($fn) {

            call_user_func($fn);
        } else {

            echo "Página no encontrada (404)";
        }
    }

    /**
     * Renderiza una vista de la aplicación.
     *
     * @param string $view Ruta de la vista dentro de Views.
     * @param array $datos Información enviada desde el controlador.
     */
    public function render($view, $datos = [])
    {
        // Convierte el arreglo en variables disponibles para la vista.
        extract($datos);

        // Ruta de la vista solicitada.
        $archivoVista = __DIR__ . '/../Views/' . $view . '.php';

        if (!file_exists($archivoVista)) {
            throw new Exception("La vista '{$view}' no existe.");
        }

        // Cargar la plantilla superior.
        include __DIR__ . '/../Views/layout/header.php';

        // Cargar la vista.
        include $archivoVista;

        // Cargar la plantilla inferior.
        include __DIR__ . '/../Views/layout/footer.php';
    }
}

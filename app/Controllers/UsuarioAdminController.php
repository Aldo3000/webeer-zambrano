<?php

/**
 * Controlador encargado de
 * administrar los clientes.
 */
class UsuarioAdminController
{
    /**
     * Muestra la lista de clientes.
     */
    public static function index()
    {
        // Verifica acceso administrativo.
        estaAutenticado();
        // Obtiene la búsqueda.
        $busqueda = trim($_GET['busqueda'] ?? '');
        // Obtiene los clientes.
        $clientes = Usuario::buscarClientes($busqueda);
        // Renderiza la vista.
        $router = new Router();
        $router->render(
            'admin/clientes/index',
            [
                'clientes' => $clientes,
                'busqueda' => $busqueda
            ]
        );
    }

    /**
     * Muestra el detalle de un cliente.
     */
    public static function detalle()
    {
        // Verifica acceso administrativo.
        estaAutenticado();

        // Obtiene el ID.
        $usuarioId = filter_var(
            $_GET['id'] ?? null,
            FILTER_VALIDATE_INT
        );

        if (!$usuarioId) {

            header('Location: /admin/clientes');
            exit;
        }

        // Busca el cliente.
        $cliente =
            Usuario::find($usuarioId);

        // Verifica que exista
        // y que realmente sea cliente.
        if (
            !$cliente ||
            (int) $cliente->rol_id !== 3
        ) {

            header('Location: /admin/clientes');
            exit;
        }

        // Obtiene sus pedidos.
        $pedidos =
            Pedido::obtenerPorUsuario(
                $cliente->id
            );

        // Renderiza.
        $router = new Router();

        $router->render(
            'admin/clientes/ver',
            [
                'cliente' => $cliente,
                'pedidos' => $pedidos
            ]
        );
    }

    /**
     * Muestra el formulario
     * para editar un cliente.
     */
    public static function editar()
    {
        // Verifica acceso administrativo.
        estaAutenticado();

        // Obtiene el ID.
        $usuarioId = filter_var(
            $_GET['id'] ?? null,
            FILTER_VALIDATE_INT
        );

        if (!$usuarioId) {

            header('Location: /admin/clientes');
            exit;
        }

        // Busca el cliente.
        $cliente = Usuario::find($usuarioId);

        // Verifica que exista
        // y que tenga rol de cliente.
        if (!$cliente || (int) $cliente->rol_id !== 3) {
            header('Location: /admin/clientes');
            exit;
        }
        // Renderiza.
        $router = new Router();

        $router->render(
            'admin/clientes/editar',
            [
                'cliente' => $cliente,
                'errores' => []
            ]
        );
    }

    /**
     * Procesa la actualización
     * de un cliente.
     */
    public static function actualizar()
    {
        // Verifica acceso administrativo.
        estaAutenticado();
        // Solo POST.
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/clientes');
            exit;
        }
        // ID del cliente.
        $usuarioId = filter_var(
            $_POST['id'] ?? null,
            FILTER_VALIDATE_INT
        );

        if (!$usuarioId) {
            header('Location: /admin/clientes');
            exit;
        }

        // Busca el cliente.
        $cliente = Usuario::find($usuarioId);

        // Verifica que exista
        // y sea realmente cliente.
        if (!$cliente || (int) $cliente->rol_id !== 3) {
            header('Location: /admin/clientes');
            exit;
        }

        /*
    |--------------------------------------------------------------------------
    | Obtener datos
    |--------------------------------------------------------------------------
    */

        $nombre =
            trim($_POST['nombre'] ?? '');

        $apellido =
            trim($_POST['apellido'] ?? '');

        $correo =
            trim($_POST['correo'] ?? '');

        $telefono =
            trim($_POST['telefono'] ?? '');

        $calle =
            trim($_POST['calle'] ?? '');

        $numero =
            trim($_POST['numero'] ?? '');

        $colonia =
            trim($_POST['colonia'] ?? '');

        $municipio =
            trim($_POST['municipio'] ?? '');

        $estado =
            trim($_POST['estado'] ?? '');

        $codigoPostal =
            trim($_POST['codigo_postal'] ?? '');

        /*
    |--------------------------------------------------------------------------
    | Validaciones
    |--------------------------------------------------------------------------
    */

        $errores = [];
        if ($nombre === '') {
            $errores[] =
                'El nombre es obligatorio.';
        }

        if ($apellido === '') {
            $errores[] =
                'El apellido es obligatorio.';
        }

        if ($correo === '') {

            $errores[] =
                'El correo es obligatorio.';
        } elseif (
            !filter_var(
                $correo,
                FILTER_VALIDATE_EMAIL
            )
        ) {

            $errores[] =
                'El correo electrónico no es válido.';
        }

        if ($telefono === '') {
            $errores[] =
                'El teléfono es obligatorio.';
        }

        if ($calle === '') {
            $errores[] =
                'La calle es obligatoria.';
        }

        if ($numero === '') {
            $errores[] =
                'El número es obligatorio.';
        }

        if ($colonia === '') {
            $errores[] =
                'La colonia es obligatoria.';
        }

        if ($municipio === '') {
            $errores[] =
                'El municipio es obligatorio.';
        }

        if ($estado === '') {
            $errores[] =
                'El estado es obligatorio.';
        }

        if ($codigoPostal === '') {
            $errores[] =
                'El código postal es obligatorio.';
        }

        /*
    |--------------------------------------------------------------------------
    | Validar correo duplicado
    |--------------------------------------------------------------------------
    */
        // Aquí reutilizaremos la función
        // de correo existente que ya tienes
        // en Usuario.php.

        if (empty($errores)) {

            // Esta línea la adaptaremos
            // al nombre exacto de tu función.
            $correoExiste = Usuario::correoExisteEnOtroUsuario($correo, $usuarioId);

            if ($correoExiste) {
                $errores[] =
                    'El correo electrónico ya está registrado por otro usuario.';
            }
        }

        /*
    |--------------------------------------------------------------------------
    | Si hay errores
    |--------------------------------------------------------------------------
    */

        if (!empty($errores)) {

            $cliente->nombre =
                $nombre;

            $cliente->apellido =
                $apellido;

            $cliente->correo =
                $correo;

            $cliente->telefono =
                $telefono;

            $cliente->calle =
                $calle;

            $cliente->numero =
                $numero;

            $cliente->colonia =
                $colonia;

            $cliente->municipio =
                $municipio;

            $cliente->estado =
                $estado;

            $cliente->codigo_postal =
                $codigoPostal;

            $router = new Router();

            $router->render(
                'admin/clientes/editar',
                [
                    'cliente' => $cliente,
                    'errores' => $errores
                ]
            );

            return;
        }

        /*
    |--------------------------------------------------------------------------
    | Actualizar
    |--------------------------------------------------------------------------
    */

        $resultado =
            Usuario::actualizarCliente(
                $usuarioId,
                [
                    'nombre' =>
                    $nombre,

                    'apellido' =>
                    $apellido,

                    'correo' =>
                    $correo,

                    'telefono' =>
                    $telefono,

                    'calle' =>
                    $calle,

                    'numero' =>
                    $numero,

                    'colonia' =>
                    $colonia,

                    'municipio' =>
                    $municipio,

                    'estado' =>
                    $estado,

                    'codigo_postal' =>
                    $codigoPostal
                ]
            );

        if (!$resultado) {

            $errores[] =
                'No fue posible actualizar los datos del cliente.';

            $router = new Router();

            $router->render(
                'admin/clientes/editar',
                [
                    'cliente' => $cliente,
                    'errores' => $errores
                ]
            );

            return;
        }

        /*
    |--------------------------------------------------------------------------
    | Éxito
    |--------------------------------------------------------------------------
    */

        $_SESSION['admin_exito'] =
            'Los datos del cliente fueron actualizados correctamente.';

        header(
            'Location: /admin/cliente?id='
                . $usuarioId
        );

        exit;
    }

    /**
     * Activa o desactiva un cliente.
     */
    public static function cambiarEstado()
    {
        // Verifica acceso administrativo.
        estaAutenticado();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/clientes');
            exit;
        }

        $usuarioId = filter_var(
            $_POST['id'] ?? null,
            FILTER_VALIDATE_INT
        );

        $activo = filter_var(
            $_POST['activo'] ?? null,
            FILTER_VALIDATE_INT
        );

        if (!$usuarioId || !in_array($activo, [0, 1], true)) {
            header('Location: /admin/clientes');
            exit;
        }

        // Verifica que sea un cliente.
        $cliente = Usuario::find($usuarioId);

        if (!$cliente || (int) $cliente->rol_id !== 3) {
            header('Location: /admin/clientes');
            exit;
        }

        // Cambia el estado.
        if (Usuario::cambiarEstado($usuarioId, $activo)) {
            $_SESSION['admin_exito'] =
                $activo
                ? 'El cliente fue activado correctamente.'
                : 'El cliente fue desactivado correctamente.';
        } else {
            $_SESSION['admin_error'] =
                'No fue posible cambiar el estado del cliente.';
        }

        header(
            'Location: /admin/cliente?id=' . $usuarioId
        );
        exit;
    }
}

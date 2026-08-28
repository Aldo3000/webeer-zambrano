<?php

/**
 * Controlador encargado de administrar
 * el proceso de autenticación.
 */
class LoginController
{
    /**
     * Muestra el formulario de inicio de sesión
     * y procesa la información enviada.
     */
    public static function login()
    {

        iniciarSesion();

        // Si ya existe una sesión activa,
        // enviamos al usuario directamente
        // a su panel correspondiente.
        if (isset($_SESSION['login']) && $_SESSION['login'] === true) {
            self::redireccionarSegunRol();
        }
        // Arreglo que almacenará los mensajes de error.
        $errores = [];
        // Variables utilizadas para conservar
        // el correo escrito por el usuario.
        $correo = '';
        // Verifica si el formulario fue enviado.
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Obtiene el correo enviado.
            $correo = trim($_POST['correo'] ?? '');
            // Obtiene la contraseña enviada.
            $password = $_POST['password'] ?? '';
            // Validar que el correo no esté vacío.
            if (!$correo) {
                $errores[] =
                    'El correo es obligatorio.';
            }
            // Validar que la contraseña no esté vacía.
            if (!$password) {
                $errores[] =
                    'La contraseña es obligatoria.';
            }

            // Si no existen errores...
            if (empty($errores)) {
                // Busca el usuario utilizando
                // el correo electrónico.
                $usuario = Usuario::where(
                    'correo',
                    $correo
                );
                // Si el usuario no existe...
                if (!$usuario) {
                    $errores[] =
                        'El usuario no existe.';
                } elseif ((int) $usuario->activo !== 1) {
                    $errores[] = 'Tu cuenta se encuentra desactivada.';
                } else {

                    // Verifica que la contraseña escrita por el usuario
                    // coincida con el hash almacenado en la base de datos.
                    $passwordCorrecto = password_verify(
                        $password,
                        $usuario->password_hash
                    );
                    // Si la contraseña es incorrecta...
                    if (!$passwordCorrecto) {
                        $errores[] =
                            'La contraseña es incorrecta.';
                    } else {
                        // Inicia una nueva sesión o continúa una existente.
                        iniciarSesion();

                        // Regenera el ID de sesión
                        // después de una autenticación exitosa.
                        session_regenerate_id(true);
                        // Indica que el usuario ha iniciado sesión.
                        $_SESSION['login'] = true;
                        // Elimina cualquier información temporal
                        // que pudiera haber quedado de un checkout
                        // realizado como invitado.
                        unset($_SESSION['checkout_datos']);
                        unset($_SESSION['checkout_errores']);
                        // Guarda el ID del usuario.
                        $_SESSION['usuario_id'] = $usuario->id;
                        // Guarda el nombre del usuario.
                        $_SESSION['nombre'] = $usuario->nombre;
                        // Guarda el rol del usuario.
                        $_SESSION['rol_id'] = $usuario->rol_id;
                        // Redirecciona al Dashboard.
                        // Redirecciona al usuario
                        // según el rol de su cuenta.
                        self::redireccionarSegunRol();
                        exit;
                    }
                }
            }
        }
        // Crear una instancia del Router.
        $router = new Router();
        // Mostrar la vista del Login.
        $router->render('auth/login', [
            // Mantener el correo escrito.
            'correo' => $correo,
            // Enviar los errores.
            'errores' => $errores
        ]);
    }

    /**
     * Cierra la sesión del usuario.
     */
    public static function logout()
    {
        // Si existe una sesión activa...
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        // Vacía todas las variables de sesión.
        $_SESSION = [];

        // Destruye la sesión.
        session_destroy();

        // Regresa al formulario de Login.
        header('Location: /login');

        exit;
    }

    /**
     * Redirecciona al usuario según
     * el rol de su cuenta.
     */
    private static function redireccionarSegunRol()
    {
        // Obtiene el rol almacenado
        // durante el inicio de sesión.
        $rolId =
            $_SESSION['rol_id'] ?? null;
        /*
    |--------------------------------------------------------------------------
    | Administrador
    |--------------------------------------------------------------------------
    */
        if ((int) $rolId === 1) {
            header('Location: /admin');
            exit;
        }
        /*
    |--------------------------------------------------------------------------
    | Empleado
    |--------------------------------------------------------------------------
    */
        if ((int) $rolId === 2) {
            header('Location: /admin');
            exit;
        }
        /*
    |--------------------------------------------------------------------------
    | Cliente
    |--------------------------------------------------------------------------
    */
        if ((int) $rolId === 3) {
            header('Location: /cuenta');
            exit;
        }
        /*
    |--------------------------------------------------------------------------
    | Rol no válido
    |--------------------------------------------------------------------------
    */
        // Si el usuario tiene un rol que
        // no contemplamos, cerramos la sesión.
        $_SESSION = [];
        session_destroy();
        header('Location: /login');
        exit;
    }
}

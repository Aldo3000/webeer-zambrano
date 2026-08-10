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
                        session_start();
                        // Indica que el usuario ha iniciado sesión.
                        $_SESSION['login'] = true;
                        // Guarda el ID del usuario.
                        $_SESSION['usuario_id'] = $usuario->id;
                        // Guarda el nombre del usuario.
                        $_SESSION['nombre'] = $usuario->nombre;
                        // Guarda el rol del usuario.
                        $_SESSION['rol_id'] = $usuario->rol_id;
                        // Redirecciona al Dashboard.
                        header('Location: /admin');
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
}

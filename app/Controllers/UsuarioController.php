<?php

/**
 * Controlador encargado de administrar
 * las operaciones relacionadas con los usuarios.
 */
class UsuarioController
{
    /**
     * Muestra el listado de usuarios.
     */
    public static function index()
    {
        // Obtiene todos los usuarios registrados.
        $usuarios = Usuario::all();
        // Crea una instancia del Router.
        $router = new Router();
        // Muestra la vista del listado.
        $router->render('usuarios/index', [
            // Envía los usuarios a la vista.
            'usuarios' => $usuarios
        ]);
    }

    /**
     * Muestra el formulario de creación
     * y procesa el registro del usuario.
     */
    public static function crear()
    {
        // Crea un objeto Usuario vacío.
        $usuario = new Usuario();
        // Obtiene el arreglo de errores del modelo.
        $errores = Usuario::getErrores();
        // Verifica si el formulario fue enviado.
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Obtiene únicamente la información del usuario.
            $datos = $_POST['usuario'];
            // Copia la información al objeto Usuario.
            $usuario->sincronizar($datos);
            // Ejecuta las validaciones del modelo.
            $erroresModelo = $usuario->validar();
            // Combina los errores del controlador
            // con los errores del modelo.
            $errores = array_merge(
                $errores,
                $erroresModelo
            );

            // Si no existen errores...
            if (empty($errores)) {
                // Convierte la contraseña escrita por el usuario
                // en un hash seguro utilizando el algoritmo
                // recomendado por PHP.
                $usuario->rol_id = 1;
                $usuario->password_hash = password_hash(
                    $usuario->password_hash,
                    PASSWORD_DEFAULT
                );
                // Guarda el usuario en la base de datos.
                $resultado = $usuario->guardar();
                // Si el registro fue exitoso...
                if ($resultado) {
                    // Regresa al listado de usuarios.
                    header('Location: /admin/usuarios?resultado=1');
                    exit;
                }
            }
        }
        // Crea una instancia del Router.
        $router = new Router();
        // Muestra la vista de creación.
        $router->render('usuarios/crear', [
            // Envía el objeto Usuario.
            'usuario' => $usuario,
            // Envía los errores de validación.
            'errores' => $errores
        ]);
    }
}

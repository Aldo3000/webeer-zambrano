<?php

//define('TEMPLATES_URL', __DIR__ . '/templates');
define('FUNCIONES_URL', __DIR__ . '/funciones.php');
define('CARPETA_IMAGENES', __DIR__ . '/../imagenes/');

/*function incluirTemplate(string $nombre, bool $inicio = false)
{
    include TEMPLATES_URL . "/{$nombre}.php";
}*/

function debug($variable)
{
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}

//escapa / sanitizar el html
function s($html): string
{
    $s = htmlspecialchars($html);
    return $s;
}

function esPost(): bool
{
    return $_SERVER['REQUEST_METHOD'] === 'POST';
}

/**
 * Verifica que el usuario esté autenticado
 * y tenga un rol permitido para el área administrativa.
 */
function estaAutorizadoAdmin()
{
    // Primero verifica que exista
    // una sesión autenticada.
    estaAutenticado();
    // Obtiene el rol actual.
    $rolId =
        $_SESSION['rol_id'] ?? null;
    // Administrador.
    if ((int) $rolId === 1) {
        return;
    }
    // Empleado.
    if ((int) $rolId === 2) {
        return;
    }
    /*
    |--------------------------------------------------------------------------
    | El usuario no tiene acceso administrativo
    |--------------------------------------------------------------------------
    */
    header('Location: /cuenta');
    exit;
}

/**
 * Verifica que el usuario esté autenticado
 * y tenga el rol de cliente.
 */
function estaAutorizadoCliente()
{
    // Primero verifica que exista
    // una sesión autenticada.
    estaAutenticado();
    // Obtiene el rol almacenado
    // en la sesión.
    $rolId =
        $_SESSION['rol_id'] ?? null;
    // 3 = Cliente.
    if ((int) $rolId === 3) {
        return;
    }
    /*
    |--------------------------------------------------------------------------
    | El usuario no es cliente
    |--------------------------------------------------------------------------
    */
    // Si es administrador o empleado,
    // lo regresamos al área administrativa.
    if ((int) $rolId === 1 || (int) $rolId === 2) {
        header('Location: /admin');
        exit;
    }
    // Si el rol no es válido,
    // regresamos al login.
    header('Location: /login');
    exit;
}
/**
 * Verifica que exista una sesión válida.
 *
 * Si el usuario no ha iniciado sesión,
 * será redireccionado al Login.
 */
function estaAutenticado()
{
    // Inicia la sesión si aún no existe una activa.
    iniciarSesion();

    // Verifica si el usuario inició sesión.
    if (
        !isset($_SESSION['login']) ||
        $_SESSION['login'] !== true
    ) {

        // Redirecciona al Login.
        header('Location: /login');
        exit;
    }
}

function iniciarSesion()
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
}

/**
 * Verifica si existe un usuario
 * actualmente autenticado.
 *
 * Esta función solamente consulta
 * la sesión y no realiza redirecciones.
 *
 * @return bool
 */
function usuarioAutenticado()
{
    // Inicia la sesión si todavía
    // no existe una activa.
    iniciarSesion();

    // Devuelve true solamente si
    // existe una sesión de usuario válida.
    return isset($_SESSION['login'])
        && $_SESSION['login'] === true;
}

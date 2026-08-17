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
function s($html) : string {
    $s= htmlspecialchars($html);
    return $s;
}

function esPost(): bool
{
    return $_SERVER['REQUEST_METHOD'] === 'POST';
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

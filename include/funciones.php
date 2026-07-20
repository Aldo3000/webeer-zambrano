<?php

//define('TEMPLATES_URL', __DIR__ . '/templates');
define('FUNCIONES_URL', __DIR__ . '/funciones.php');
define('CARPETA_IMAGENES', __DIR__ . '/../imagenes/');

/*function incluirTemplate(string $nombre, bool $inicio = false)
{
    include TEMPLATES_URL . "/{$nombre}.php";
}*/

function estaAutenticado()
{
    session_start();
    
    if (!$_SESSION['login']) {
        header('Location: /');
    }
}

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

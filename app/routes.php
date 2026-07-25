<?php

/*
|--------------------------------------------------------------------------
| Rutas de Productos
|--------------------------------------------------------------------------
|
| Se registran las rutas encargadas de administrar
| las operaciones del módulo de productos.
|
*/

/**
 * Página principal.
 */
$router->get('/', function () {
    ProductoController::index();
});

/**
 * Mostrar el listado de productos.
 */
$router->get('/admin/productos', function () {
    ProductoController::index();
});

/**
 * Mostrar el formulario de creación.
 */
$router->get('/admin/productos/crear', function () {
    ProductoController::crear();
});

/**
 * Procesar el formulario de creación.
 */
$router->post('/admin/productos/crear', function () {
    ProductoController::crear();
});

/**
 * Mostrar el formulario de edición.
 */
$router->get('/admin/productos/editar', function () {
    ProductoController::editar();
});

/**
 * Procesar el formulario de edición.
 */
$router->post('/admin/productos/editar', function () {
    ProductoController::editar();
});

/**
 * Eliminar un producto.
 */
$router->post('/admin/productos/eliminar', function () {
    ProductoController::eliminar();
});
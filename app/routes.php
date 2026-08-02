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

/**
 * Listar categorías.
 */
$router->get('/admin/categorias', function () {
    CategoriaController::index();
});

/**
 * Mostrar el formulario de creación.
 */
$router->get('/admin/categorias/crear', function () {
    CategoriaController::crear();
});

/**
 * Procesar el formulario de creación.
 */
$router->post('/admin/categorias/crear', function () {
    CategoriaController::crear();
});

/**
 * Mostrar el formulario de edición.
 */
$router->get('/admin/categorias/editar', function () {
    CategoriaController::editar();
});

/**
 * Procesar el formulario de edición.
 */
$router->post('/admin/categorias/editar', function () {
    CategoriaController::editar();
});

/**
 * Eliminar una categoría.
 */
$router->post('/admin/categorias/eliminar', function () {
    CategoriaController::eliminar();
});



/**
 * Listar marcas.
 */
$router->get('/admin/marcas', function () {
    MarcaController::index();
});

/**
 * Mostrar el formulario de creación.
 */
$router->get('/admin/marcas/crear', function () {
    MarcaController::crear();
});

/**
 * Procesar el formulario de creación.
 */
$router->post('/admin/marcas/crear', function () {
    MarcaController::crear();
});

/**
 * Mostrar el formulario de edición.
 */
$router->get('/admin/marcas/editar', function () {
    MarcaController::editar();
});

/**
 * Procesar el formulario de edición.
 */
$router->post('/admin/marcas/editar', function () {
    MarcaController::editar();
});

/**
 * Eliminar una categoría.
 */
$router->post('/admin/marcas/eliminar', function () {
    MarcaController::eliminar();
});
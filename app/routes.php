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

/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/
// Mostrar el panel principal de administración.
$router->get('/admin', function () {
    AdminController::index();
});


/*
|--------------------------------------------------------------------------
| Usuarios
|--------------------------------------------------------------------------
*/

// Listado de usuarios.
$router->get('/admin/usuarios', function () {
    UsuarioController::index();
});

// Formulario para crear un usuario.
$router->get('/admin/usuarios/crear', function () {
    UsuarioController::crear();
});

// Procesar el formulario de creación.
$router->post('/admin/usuarios/crear', function () {
    UsuarioController::crear();
});

/*
|--------------------------------------------------------------------------
| Login
|--------------------------------------------------------------------------
|
| Rutas relacionadas con la autenticación.
|
*/

// Mostrar el formulario de inicio de sesión.
$router->get('/login', function () {

    LoginController::login();

});

// Procesar el formulario.
$router->post('/login', function () {

    LoginController::login();

});

/*
|--------------------------------------------------------------------------
| Logout
|--------------------------------------------------------------------------
*/

$router->get('/logout', function () {

    LoginController::logout();

});

/*
|--------------------------------------------------------------------------
| Showroom
|--------------------------------------------------------------------------
|
| Rutas públicas del catálogo de productos.
|
*/

// Mostrar el catálogo.
$router->get('/productos', function () {
    ShowroomController::index();
});

/*
|--------------------------------------------------------------------------
| Detalle de producto
|--------------------------------------------------------------------------
|
| Muestra la información individual de un producto.
|
*/

$router->get('/productos/ver', function () {
    ShowroomController::ver();
});

/*
|--------------------------------------------------------------------------
| Carrito
|--------------------------------------------------------------------------
|
| Muestra el carrito actual del cliente.
|
*/
$router->get('/carrito', function () {
     CarritoController::index();
});

/*
|--------------------------------------------------------------------------
| Agregar producto al carrito
|--------------------------------------------------------------------------
|
| Agrega un producto al carrito mediante una petición POST.
|
*/

$router->post('/carrito/agregar', function () {

    CarritoController::agregar();

});

/*
|--------------------------------------------------------------------------
| Aumentar cantidad del carrito
|--------------------------------------------------------------------------
*/

$router->post('/carrito/aumentar', function () {

    CarritoController::aumentar();

});

/*
|--------------------------------------------------------------------------
| Disminuir cantidad del carrito
|--------------------------------------------------------------------------
*/

$router->post('/carrito/disminuir', function () {

    CarritoController::disminuir();

});

/*
|--------------------------------------------------------------------------
| Eliminar producto del carrito
|--------------------------------------------------------------------------
*/

$router->post('/carrito/eliminar', function () {

    CarritoController::eliminar();

});

/*
|--------------------------------------------------------------------------
| Vaciar carrito
|--------------------------------------------------------------------------
*/

$router->post('/carrito/vaciar', function () {

    CarritoController::vaciar();

});

/*
|--------------------------------------------------------------------------
| Checkout
|--------------------------------------------------------------------------
*/

// Mostrar checkout
$router->get('/checkout', function () {

    CheckoutController::index();

});

// Procesar formulario del checkout
$router->post('/checkout', function () {

    CheckoutController::procesar();

});

// Mostrar pantalla de confirmación
$router->get('/checkout/confirmar', function () {

    CheckoutController::confirmar();

});


// Procesar selección de método de pago
$router->post('/checkout/confirmar', function () {

    CheckoutController::procesarConfirmacion();

});

/*
|--------------------------------------------------------------------------
| Pedido realizado
|--------------------------------------------------------------------------
*/
$router->get('/checkout/exitoso', function () {

    CheckoutController::exitoso();

});
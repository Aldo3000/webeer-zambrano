<?php

/*
|--------------------------------------------------------------------------
| Cargar archivos necesarios
|--------------------------------------------------------------------------
|
| Se incluyen las clases y funciones que utilizará la aplicación.
|
*/

// Funciones generales
require_once __DIR__ . '/../include/funciones.php';

// Conexión a la base de datos
require_once __DIR__ . '/../include/config/database.php';

// Modelos
require_once __DIR__ . '/../app/Models/ActiveRecord.php';
require_once __DIR__ . '/../app/Models/Productos.php';
require_once __DIR__ . '/../app/Models/Categoria.php';
require_once __DIR__ . '/../app/Models/Marca.php';

// Router
require_once __DIR__ . '/../app/Router/Router.php';

// Controladores
require_once __DIR__ . '/../app/Controllers/ProductoController.php';
require_once __DIR__ . '/../app/Controllers/CategoriaController.php';
require_once __DIR__ . '/../app/Controllers/MarcaController.php';


/*
|--------------------------------------------------------------------------
| Conectar con la base de datos
|--------------------------------------------------------------------------
|
| Se establece la conexión con MySQL y se asigna al ActiveRecord.
|
*/
$db = conectarDB();

ActiveRecord::setDB($db);


/*
|--------------------------------------------------------------------------
| Crear el Router
|--------------------------------------------------------------------------
|
| Se crea la instancia principal del Router.
|
*/
$router = new Router();

/*
|--------------------------------------------------------------------------
| Registrar las rutas
|--------------------------------------------------------------------------
|
| Se cargan todas las rutas definidas en la aplicación.
|
*/
require_once __DIR__ . '/../app/routes.php';

/*
|--------------------------------------------------------------------------
| Ejecutar el Router
|--------------------------------------------------------------------------
|
| El Router analiza la petición y ejecuta el controlador correspondiente.
|
*/
$router->comprobarRutas();

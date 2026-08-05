<?php

/**
 * Controlador encargado de administrar
 * las operaciones relacionadas con los productos.
 */
class ProductoController
{
    /**
     * Muestra el listado de productos.
     */
    public static function index()
    {
        // Solicita al modelo todos los productos registrados.
        $productos = Producto::all();

        // Crea una instancia del Router para poder renderizar una vista.
        $router = new Router();

        // Envía la lista de productos a la vista "productos/index".
        $router->render('productos/index', [
            // La vista recibirá una variable llamada $productos.
            'productos' => $productos
        ]);
    }

    /**
     * Muestra el formulario para crear un producto
     * y procesa el registro cuando el usuario envía
     * la información mediante una petición POST.
     */
    public static function crear()
    {
        // Crea un objeto Producto vacío que será utilizado
        // para llenar el formulario o conservar los datos
        // cuando existan errores de validación.
        $producto = new Producto();

        // Obtiene el arreglo de errores del modelo.
        // La primera vez estará vacío.
        $errores = Producto::getErrores();

        // Obtiene todas las categorías.
        $categorias = Categoria::all();

        // Obtiene todas las marcas.
        $marcas = Marca::all();

        // Verifica si el formulario fue enviado.
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Obtiene únicamente la información del producto
            // enviada desde el formulario.
            $datos = $_POST['producto'];

            // Tipos MIME permitidos.
            $tiposPermitidos = [
                'image/jpeg',
                'image/png',
                'image/webp'
            ];

            // Tamaño máximo permitido (2 MB).
            $tamanoMaximo = 3 * 1024 * 1024;

            // Verifica si el usuario seleccionó una imagen.
            if (!empty($_FILES['imagen']['name'])) {

                // Verifica que el archivo sea una imagen válida.
                if (!in_array($_FILES['imagen']['type'], $tiposPermitidos)) {

                    $errores[] =
                        'Solo se permiten imágenes JPG, PNG o WEBP.';
                }

                // Verifica que el tamaño no exceda el máximo permitido.
                if ($_FILES['imagen']['size'] > $tamanoMaximo) {

                    $errores[] =
                        'La imagen no puede ser mayor a 3 MB.';
                }

                // Si la imagen cumple todas las validaciones...
                if (empty($errores)) {

                    // Obtiene la extensión original del archivo.
                    $extension = pathinfo(
                        $_FILES['imagen']['name'],
                        PATHINFO_EXTENSION
                    );

                    // Genera un nombre aleatorio para evitar
                    // archivos repetidos.
                    $nombreImagen = bin2hex(random_bytes(16));

                    // Agrega nuevamente la extensión.
                    $nombreImagen .= '.' . strtolower($extension);

                    // Agrega el nombre de la imagen a los datos
                    // que posteriormente serán sincronizados.
                    $datos['imagen_principal'] = $nombreImagen;
                }
            }

            // Copia los valores recibidos hacia el objeto Producto.
            $producto->sincronizar($datos);

            // Ejecuta las reglas de validación definidas
            // dentro del modelo Producto.
            $errores = array_merge($errores, $producto->validar());

            // Si no existen errores de validación...
            if (empty($errores)) {

                // Guarda el producto en la base de datos.
                $resultado = $producto->guardar();

                // Redirecciona al listado para evitar que
                // el navegador vuelva a enviar el formulario
                // si el usuario actualiza la página.
                // Si el producto se guardó correctamente...
                if ($resultado) {

                    // Ruta donde se almacenarán las imágenes.
                    $carpetaImagenes = __DIR__ . '/../../public/imagenes/';

                    // Si la carpeta no existe, crearla automáticamente.
                    if (!is_dir($carpetaImagenes)) {
                        mkdir($carpetaImagenes, 0755, true);
                    }

                    // Verifica que realmente se haya seleccionado una imagen.
                    if (!empty($_FILES['imagen']['tmp_name'])) {

                        // Mueve la imagen desde la carpeta temporal de PHP
                        // hacia la carpeta definitiva del proyecto.
                        move_uploaded_file(
                            $_FILES['imagen']['tmp_name'],
                            $carpetaImagenes . $nombreImagen
                        );
                    }

                    // Regresa al listado de productos.
                    header('Location: /admin/productos?resultado=1');
                    exit;
                }
            }
        }

        // Crea una instancia del Router para renderizar la vista.
        $router = new Router();

        // Muestra la vista del formulario de creación.
        $router->render('productos/crear', [

            // Envía el objeto Producto para llenar
            // automáticamente los campos del formulario.
            'producto' => $producto,

            // Envía los mensajes de error, si existen.
            'errores' => $errores,

            'categorias' => $categorias,

            'marcas' => $marcas
        ]);
    }
    /**
     * Muestra el formulario de edición
     * y procesa la actualización del producto.
     */
    public static function editar()
    {
        // Obtiene el ID enviado mediante la URL
        // y verifica que sea un número entero válido.
        $id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);

        // Si el ID no es válido, regresa al listado.
        if (!$id) {
            header('Location: /admin/productos');
            exit;
        }

        // Busca el producto correspondiente en la base de datos.
        $producto = Producto::find($id);

        // Si el producto no existe,
        // vuelve al listado principal.
        if (!$producto) {
            header('Location: /admin/productos');
            exit;
        }

        // Temporalmente mostramos el objeto para comprobar
        // que la búsqueda funciona correctamente.
        /*echo '<pre>';
        var_dump($producto);
        echo '</pre>';*/

        // Obtiene el arreglo de errores.
        $errores = Producto::getErrores();
        $categorias = Categoria::all();
        $marcas = Marca::all();

        // Verifica si el usuario envió el formulario.
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Obtiene únicamente la información del producto
            // enviada desde el formulario.
            $datos = $_POST['producto'];
            // Guarda el nombre de la imagen actual antes de modificar el objeto.
            $imagenAnterior = $producto->imagen_principal;

            // Guarda el nombre de la imagen actual antes de modificar el objeto.
            $imagenAnterior = $producto->imagen_principal;

            // Tipos MIME permitidos.
            $tiposPermitidos = [
                'image/jpeg',
                'image/png',
                'image/webp'
            ];

            // Tamaño máximo permitido (3 MB).
            $tamanoMaximo = 3 * 1024 * 1024;

            // Verifica si el usuario seleccionó una nueva imagen.
            if (!empty($_FILES['imagen']['name'])) {

                // Verifica que el archivo sea una imagen válida.
                if (!in_array($_FILES['imagen']['type'], $tiposPermitidos)) {

                    $errores[] =
                        'Solo se permiten imágenes JPG, PNG o WEBP.';
                }

                // Verifica que el tamaño no exceda el máximo permitido.
                if ($_FILES['imagen']['size'] > $tamanoMaximo) {

                    $errores[] =
                        'La imagen no puede ser mayor a 3 MB.';
                }

                // Si la imagen cumple todas las validaciones...
                if (empty($errores)) {

                    // Obtiene la extensión del archivo.
                    $extension = pathinfo(
                        $_FILES['imagen']['name'],
                        PATHINFO_EXTENSION
                    );

                    // Genera un nombre aleatorio.
                    $nombreImagen = bin2hex(random_bytes(16));

                    // Agrega nuevamente la extensión.
                    $nombreImagen .= '.' . strtolower($extension);

                    // Actualiza el nombre de la imagen
                    // dentro del arreglo de datos.
                    $datos['imagen_principal'] = $nombreImagen;
                }
            }

            // Actualiza las propiedades del objeto Producto
            // con los nuevos valores enviados.
            $producto->sincronizar($datos);

            // Ejecuta nuevamente las validaciones.
            $errores = array_merge($errores, $producto->validar());

            // Si no existen errores...
            if (empty($errores)) {

                // Guarda los cambios realizados.
                $resultado = $producto->guardar();

                // Regresa al listado de productos.
                if ($resultado) {

                    // Ruta donde se almacenan las imágenes.
                    $carpetaImagenes = __DIR__ . '/../../public/imagenes/';

                    // Verifica si el usuario seleccionó una imagen nueva.
                    if (!empty($_FILES['imagen']['tmp_name'])) {

                        // Mueve la imagen nueva.
                        move_uploaded_file(
                            $_FILES['imagen']['tmp_name'],
                            $carpetaImagenes . $nombreImagen
                        );

                        // Si existía una imagen anterior...
                        if (!empty($imagenAnterior)) {

                            // Construye la ruta completa.
                            $rutaImagenAnterior = $carpetaImagenes . $imagenAnterior;

                            // Verifica que el archivo exista.
                            if (file_exists($rutaImagenAnterior)) {

                                // Elimina la imagen anterior.
                                unlink($rutaImagenAnterior);
                            }
                        }

                        // Aquí eliminaremos la imagen anterior.
                    }

                    header('Location: /admin/productos?resultado=2');
                    exit;
                }
            }
        }

        // Crea una instancia del Router.
        $router = new Router();

        // Muestra la vista de edición.
        $router->render('productos/editar', [

            // Envía el producto encontrado
            // para llenar el formulario.
            'producto' => $producto,
            'categorias' => $categorias,
            'marcas' => $marcas,

            // Envía los errores de validación.
            'errores' => $errores
        ]);
    }

    /**
     * Elimina un producto.
     */
    public static function eliminar()
    {
        // Solo permite eliminar mediante una petición POST.
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Obtiene el ID enviado desde el formulario.
            $id = $_POST['id'] ?? null;

            // Verifica que exista un ID.
            if ($id) {

                // Busca el producto correspondiente.
                $producto = Producto::find($id);

                // Si el producto existe...
                if ($producto) {
                    // Si el producto tiene una imagen asociada...
                    if (!empty($producto->imagen_principal)) {

                        // Obtiene la carpeta donde se almacenan
                        // todas las imágenes del proyecto.
                        $carpetaImagenes = __DIR__ . '/../../public/imagenes/';

                        // Construye la ruta completa del archivo.
                        $rutaImagen = $carpetaImagenes . $producto->imagen_principal;

                        // Verifica que el archivo exista antes
                        // de intentar eliminarlo.
                        if (is_file($rutaImagen)) {

                            // Elimina la imagen del disco.
                            unlink($rutaImagen);
                        }
                    }

                    // Elimina el registro del producto
                    // de la base de datos.
                    $resultado = $producto->delete();

                    if ($resultado) {
                        header('Location: /admin/productos?resultado=3');
                        exit;
                    }
                }
            }
        }

        // Regresa al listado de productos.
        header('Location: /admin/productos');
    }
}

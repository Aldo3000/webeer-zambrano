<?php

/**
 * Controlador encargado de mostrar
 * el catálogo público de productos.
 */
class ShowroomController
{
    /**
     * Muestra todos los productos disponibles
     * para los clientes.
     */
    public static function index()
    {
        // Obtiene todos los productos registrados.
        $productos = Producto::all();
        // Obtiene todas las categorías registradas.
        $categorias = Categoria::all();
        // Crea una instancia del Router.
        $router = new Router();
        // Renderiza la vista del showroom.
        $router->render(
            'showroom/index',
            [
                // Envía los productos a la vista.
                'productos' => $productos,
                // Envía las categorías a la vista.
                'categorias' => $categorias
            ],
            false
        );
    }

    /**
     * Muestra el detalle de un producto.
     */
    public static function ver()
    {
        // Obtiene el ID enviado mediante la URL.
        // y verifica que sea un número entero válido.
        $id = filter_var(
            $_GET['id'] ?? null,
            FILTER_VALIDATE_INT
        );

        // Si el ID no es válido,
        // regresa al catálogo.
        if (!$id) {
            header('Location: /productos');
            exit;
        }

        // Busca el producto correspondiente
        // en la base de datos.
        $producto = Producto::findConRelaciones($id);

        // Si el producto no existe,
        // regresa al catálogo.
        if (!$producto) {
            header('Location: /productos');
            exit;
        }

        // Crea una instancia del Router.
        $router = new Router();

        // Muestra la vista del detalle del producto.
        // No utiliza el layout administrativo/general
        // porque el showroom tiene su propio diseño.
        $router->render(
            'showroom/ver',
            [
                'producto' => $producto
            ],
            false
        );
    }
}

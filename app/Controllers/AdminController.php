<?php

/**
 * Controlador encargado de mostrar
 * el panel principal de administración.
 */
class AdminController
{
    /**
     * Muestra el Dashboard principal.
     */
    public static function index()
    {
        // Obtener estadísticas generales.
        $totalProductos = Producto::totalRegistros();

        $totalCategorias = Categoria::totalRegistros();

        $totalMarcas = Marca::totalRegistros();

        // Crear una instancia del Router.
        $router = new Router();

        // Mostrar el Dashboard.
        $router->render('admin/index', [

            'totalProductos' => $totalProductos,

            'totalCategorias' => $totalCategorias,

            'totalMarcas' => $totalMarcas

        ]);
    }
}

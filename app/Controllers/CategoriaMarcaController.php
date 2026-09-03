<?php

class CategoriaMarcaController
{
    /**
     * Muestra la administración de relaciones.
     */
    public static function index()
    {
        $categorias = Categoria::all();
        $marcas = Marca::all();

        $categoriaId = filter_var(
            $_GET['categoria_id'] ?? null,
            FILTER_VALIDATE_INT
        );

        $marcasAsignadas = [];

        if ($categoriaId) {
            $marcasAsignadas =
                CategoriaMarca::obtenerMarcasPorCategoria($categoriaId);
        }

        $idsAsignados = [];

        foreach ($marcasAsignadas as $relacion) {
            $idsAsignados[] = $relacion->marca_id;
        }

        $errores = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $categoriaId = (int) ($_POST['categoria_id'] ?? 0);
            $marcasSeleccionadas = $_POST['marcas'] ?? [];

            if (!$categoriaId) {
                $errores[] = 'Selecciona una categoría.';
            }

            if (empty($errores)) {
                CategoriaMarca::eliminarPorCategoria($categoriaId);

                foreach ($marcasSeleccionadas as $marcaId) {
                    $relacion = new CategoriaMarca([
                        'categoria_id' => $categoriaId,
                        'marca_id' => (int) $marcaId
                    ]);

                    $relacion->guardar();
                }

                header(
                    'Location: /admin/categoria-marca?categoria_id='
                    . $categoriaId
                    . '&resultado=1'
                );
                exit;
            }
        }

        $router = new Router();

        $router->render('admin/categoria-marca/index', [
            'categorias' => $categorias,
            'marcas' => $marcas,
            'categoriaId' => $categoriaId,
            'idsAsignados' => $idsAsignados,
            'errores' => $errores
        ]);
    }
}
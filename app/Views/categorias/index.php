<?php

/**
 * Vista:
 * Listado de Categorías
 *
 * Variables disponibles:
 * ----------------------
 * $categorias
 */

$mensajes = [
    1 => 'Categoría creada correctamente.',
    2 => 'Categoría actualizada correctamente.',
    3 => 'Categoría eliminada correctamente.'
];
?>

<main class="contenedor">
    <!-- Encabezado de la página -->
    <header class="encabezado">
        <h1>Administración de Categorías</h1>
        <p>
            Desde este módulo podrás administrar todas las categorías
            registradas en el sistema.
        </p>
        <div>
            <?php
            $resultado = $_GET['resultado'] ?? '';
            if (isset($mensajes[$resultado])) {
                echo '<p class="alerta exito">' . htmlspecialchars($mensajes[$resultado]) . '</p>';
            }
            ?>
        </div>
    </header>

    <!-- Acciones principales -->
    <section class="acciones">
        <a
            href="/admin/categorias/crear"
            class="boton boton-verde">
            + Nueva Categoría
        </a>
    </section>
    <!-- Tabla de categorías -->
    <section class="tabla-categorias">
        <table class="tabla">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($categorias)) : ?>
                    <?php foreach ($categorias as $categoria) : ?>
                        <tr>
                            <td>
                                <?= htmlspecialchars($categoria->id) ?>
                            </td>
                            <td>
                                <?= htmlspecialchars($categoria->nombre) ?>
                            </td>
                            <td>
                                <a href="/admin/categorias/editar?id=<?= urlencode($categoria->id) ?>">
                                    Editar
                                </a>
                                |
                                <form
                                    method="POST"
                                    action="/admin/categorias/eliminar"
                                    style="display:inline;"
                                    onsubmit="return confirm('¿Deseas eliminar esta categoría?');">
                                    <input
                                        type="hidden"
                                        name="id"
                                        value="<?= htmlspecialchars($categoria->id) ?>">
                                    <button type="submit">
                                        Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="3">
                            No existen categorías registradas.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </section>

    <!-- Información adicional -->
    <footer class="resumen">
        <p>
            Total de categorías:
            <strong>
                <?= count($categorias) ?>
            </strong>
        </p>
    </footer>
</main>
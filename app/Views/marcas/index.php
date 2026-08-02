<?php

/**
 * Vista:
 * Listado de Marcas
 *
 * Variables disponibles:
 * ----------------------
 * $marcas
 */

$mensajes = [
    1 => 'Marca creada correctamente.',
    2 => 'Marca actualizada correctamente.',
    3 => 'Marca eliminada correctamente.'
];
?>

<main class="contenedor">
    <!-- Encabezado de la página -->
    <header class="encabezado">
        <h1>Administración de Marcas</h1>
        <p>
            Desde este módulo podrás administrar todas las marcas
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
            href="/admin/marcas/crear"
            class="boton boton-verde">
            + Nueva Marca
        </a>
    </section>

    <!-- Tabla de marcas -->
    <section class="tabla-marcas">
        <table class="tabla">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($marcas)) : ?>
                    <?php foreach ($marcas as $marca) : ?>
                        <tr>
                            <td>
                                <?= htmlspecialchars($marca->id) ?>
                            </td>
                            <td>
                                <?= htmlspecialchars($marca->nombre) ?>
                            </td>
                            <td>
                                <a href="/admin/marcas/editar?id=<?= urlencode($marca->id) ?>">
                                    Editar
                                </a>
                                |
                                <form
                                    method="POST"
                                    action="/admin/marcas/eliminar"
                                    style="display:inline;"
                                    onsubmit="return confirm('¿Deseas eliminar esta marca?');">
                                    <input
                                        type="hidden"
                                        name="id"
                                        value="<?= htmlspecialchars($marca->id) ?>">
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
                            No existen marcas registradas.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </section>

    <!-- Información adicional -->
    <footer class="resumen">
        <p>
            Total de marcas:
            <strong>
                <?= count($marcas) ?>
            </strong>
        </p>
    </footer>
</main>
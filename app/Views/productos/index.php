<?php

/**
 * Vista:
 * Listado de Productos
 *
 * Variables disponibles:
 * ----------------------
 * $productos
 */

?>

<main class="contenedor">
    <!-- Encabezado de la página -->
    <header class="encabezado">
        <h1>Administración de Productos</h1>
        <p>
            Desde este módulo podrás administrar todos los productos
            registrados en el sistema.
        </p>
    </header>
    <!-- Acciones principales -->
    <section class="acciones">
        <a
            href="/admin/productos/crear"
            class="boton boton-verde">
            + Nuevo Producto
        </a>
    </section>
    <!-- Tabla de productos -->
    <section class="tabla-productos">
        <table class="tabla">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>SKU</th>
                    <th>Nombre</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($productos)) : ?>
                    <?php foreach ($productos as $producto) : ?>
                        <tr>
                            <td>
                                <?= htmlspecialchars($producto->id) ?>
                            </td>
                            <td>
                                <?= htmlspecialchars($producto->sku) ?>
                            </td>
                            <td>
                                <?= htmlspecialchars($producto->nombre) ?>
                            </td>
                            <td>
                                $<?= number_format((float) $producto->precio_actual, 2) ?>
                            </td>
                            <td>
                                <?= htmlspecialchars($producto->stock) ?>
                            </td>
                            <td>
                                <?php if ($producto->stock == 0) : ?>
                                    Agotado
                                <?php elseif ($producto->stock <= 10) : ?>
                                    Stock bajo
                                <?php else : ?>
                                    Disponible
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="/admin/productos/editar?id=<?= urlencode($producto->id) ?>">
                                    Editar
                                </a>
                                |
                                <form
                                    method="POST"
                                    action="/admin/productos/eliminar"
                                    style="display:inline;">
                                    <input
                                        type="hidden"
                                        name="id"
                                        value="<?= htmlspecialchars($producto->id) ?>">
                                    <button type="submit">
                                        Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="7">
                            No existen productos registrados.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </section>
    <!-- Información adicional -->
    <footer class="resumen">
        <p>
            Total de productos:
            <strong>
                <?= count($productos) ?>
            </strong>
        </p>
    </footer>
</main>
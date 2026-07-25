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
        <a href="/admin/productos/crear">
            + Nuevo Producto
        </a>
    </section>
    <!-- Tabla de productos -->
    <section class="tabla-productos">
        <table border="1" cellpadding="8">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>SKU</th>
                    <th>Nombre</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($productos)) : ?>
                    <?php foreach ($productos as $producto) : ?>
                        <tr>
                            <td><?= $producto->id ?></td>
                            <td><?= $producto->sku ?></td>
                            <td><?= $producto->nombre ?></td>
                            <td>$<?= number_format($producto->precio_actual, 2) ?></td>
                            <td><?= $producto->stock ?></td>
                            <td>
                                <a href="/admin/productos/editar?id=<?= $producto->id ?>">
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
                                        value="<?= $producto->id ?>">
                                    <button type="submit">
                                        Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="6">
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
            <strong><?= count($productos) ?></strong>
        </p>
    </footer>
</main>
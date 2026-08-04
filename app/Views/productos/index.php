<?php

/**
 * Vista:
 * Listado de Productos
 *
 * Variables disponibles:
 * ----------------------
 * $productos
 */


$mensajes = [
    1 => 'Producto creado correctamente.',
    2 => 'Producto actualizado correctamente.',
    3 => 'Producto eliminado correctamente.'
];
?>

<main class="contenedor">
    <!-- Encabezado de la página -->
    <header class="encabezado">
        <h1>Administración de Productos</h1>
        <p>
            Desde este módulo podrás administrar todos los productos
            registrados en el sistema.
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
                    <th>Imagen</th>
                    <th>ID</th>
                    <th>SKU</th>
                    <th>Nombre</th>
                    <th>Categoria</th>
                    <th>Marca</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($productos)) : ?>
                    <?php foreach ($productos as $producto) : ?>
                        <?php
                        // Obtener la categoría del producto.
                        $categoria = $producto->categoria();

                        // Obtener la marca del producto.
                        $marca = $producto->marca();
                        ?>
                        <tr>
                            <td>

                                <?php if (!empty($producto->imagen_principal)) : ?>
                                    <img
                                        src="/imagenes/<?= htmlspecialchars($producto->imagen_principal) ?>"
                                        alt="<?= htmlspecialchars($producto->nombre) ?>"
                                        width="80">
                                <?php else : ?>
                                    Sin imagen
                                <?php endif; ?>
                            </td>
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
                                <?= htmlspecialchars($categoria ? $categoria->nombre : 'Sin categoría') ?>
                            </td>
                            <td>
                                <?= htmlspecialchars($marca ? $marca->nombre : 'Sin marca') ?>
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
                                    style="display:inline;"
                                    onsubmit="return confirm('¿Deseas eliminar este producto?');">
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
<?php

/**
 * Vista:
 * Detalle de Producto
 *
 * Variables disponibles:
 * ----------------------
 * $producto
 */

?>

<main class="detalleProducto">
    <div class="contenedor">
        <!-- Imagen del producto -->
        <div class="detalleProducto__imagen">
            <img
                src="/imagenes/<?= htmlspecialchars($producto->imagen_principal) ?>"
                alt="<?= htmlspecialchars($producto->nombre) ?>">
        </div>
        <!-- Información del producto -->
        <div class="detalleProducto__informacion">
            <h1>
                <?= htmlspecialchars($producto->nombre) ?>
            </h1>
            <div class="detalleProducto__datos">

                <p>
                    <strong>Marca:</strong>
                    <?= htmlspecialchars($producto->marca_nombre) ?>
                </p>

                <p>
                    <strong>Categoría:</strong>
                    <?= htmlspecialchars($producto->categoria_nombre) ?>
                </p>

            </div>
            <p>
                <?= htmlspecialchars($producto->descripcion) ?>
            </p>
            <p>
                Precio:
                <strong>
                    $<?= number_format(
                            (float) $producto->precio_actual,
                            2
                        ) ?>
                </strong>
            </p>
            <p>
                Stock:
                <?= htmlspecialchars($producto->stock) ?>
            </p>
            <!-- Botón preparado para el carrito -->
            <button
                type="button"
                class="botonAgregarCarrito">
                Agregar al carrito
            </button>
        </div>
    </div>
</main>
<?php

/**
 * Vista:
 * Carrito de compras
 *
 * Variables disponibles:
 * ----------------------
 * $productos
 * $total
 * $hayProblemasStock
 */

// Obtiene el mensaje de error almacenado en la sesión.
$carritoError = $_SESSION['carrito_error'] ?? null;
// Elimina el mensaje de la sesión
// para que solamente se muestre una vez.
unset($_SESSION['carrito_error']);
?>

<main class="carrito">
    <div class="contenedor">
        <h1>
            Carrito de compras
        </h1>
        <!-- Mensaje de error temporal -->
        <?php if ($carritoError) : ?>
            <p class="alerta error">
                <?= htmlspecialchars($carritoError) ?>
            </p>
        <?php endif; ?>
        <!-- Mensaje general cuando existe
             algún problema de stock -->
        <?php if ($hayProblemasStock) : ?>
            <p class="alerta error">
                Algunos productos de tu carrito
                ya no tienen suficiente stock disponible.
                Ajusta las cantidades antes de continuar.
            </p>
        <?php endif; ?>

        <?php if (empty($productos)) : ?>
            <p>
                Tu carrito está vacío.
            </p>
            <a href="/productos">
                ← Seguir comprando
            </a>
        <?php else : ?>
            <!-- Vaciar carrito -->
            <form
                method="POST"
                action="/carrito/vaciar"
                onsubmit="return confirm('¿Deseas vaciar todo el carrito?');">

                <button type="submit">
                    Vaciar carrito
                </button>

            </form>
            <section class="carritoProductos">
                <?php foreach ($productos as $producto) : ?>
                    <article class="productoCarrito">
                        <h2>
                            <?= htmlspecialchars($producto->nombre) ?>
                        </h2>
                        <p>
                            Precio:
                            $<?= number_format(
                                    (float) $producto->precio_actual,
                                    2
                                ) ?>
                        </p>
                        <!-- Aviso específico del producto
                             cuando la cantidad supera
                             el stock disponible -->
                        <?php if ($producto->stock_insuficiente) : ?>
                            <p class="alerta error">
                                La cantidad solicitada supera
                                el stock disponible.

                                Actualmente hay
                                <strong>
                                    <?= htmlspecialchars($producto->stock) ?>
                                </strong>
                                unidades disponibles.
                            </p>
                        <?php endif; ?>

                        <!-- Control de cantidad -->
                        <div>
                            <!-- Disminuir -->
                            <form
                                method="POST"
                                action="/carrito/disminuir"
                                style="display:inline;">
                                <input
                                    type="hidden"
                                    name="id"
                                    value="<?= htmlspecialchars($producto->id) ?>">
                                <button type="submit">
                                    −
                                </button>
                            </form>
                            <strong>
                                <?= htmlspecialchars($producto->cantidad) ?>
                            </strong>
                            <!-- Aumentar -->
                            <form
                                method="POST"
                                action="/carrito/aumentar"
                                style="display:inline;">
                                <input
                                    type="hidden"
                                    name="id"
                                    value="<?= htmlspecialchars($producto->id) ?>">
                                <button type="submit">
                                    +
                                </button>
                            </form>
                        </div>
                        <!-- Subtotal -->
                        <?php if (!$producto->stock_insuficiente) : ?>
                            <p>
                                Subtotal:
                                $<?= number_format(
                                        (float) $producto->precio_actual
                                            * (int) $producto->cantidad,
                                        2
                                    ) ?>
                            </p>
                        <?php else : ?>
                            <p class="alerta error">
                                Subtotal no válido hasta ajustar
                                la cantidad disponible.
                            </p>
                        <?php endif; ?>
                        <!-- Eliminar producto -->
                        <form
                            method="POST"
                            action="/carrito/eliminar">
                            <input
                                type="hidden"
                                name="id"
                                value="<?= htmlspecialchars($producto->id) ?>">
                            <button type="submit">
                                Eliminar
                            </button>
                        </form>
                    </article>
                <?php endforeach; ?>
                <!-- Total general -->
                <p>
                    Total:
                    <strong>
                        $<?= number_format(
                                (float) $total,
                                2
                            ) ?>
                    </strong>
                </p>
                <!-- Continuar al checkout -->
                <?php if (!$hayProblemasStock) : ?>

                    <a href="/checkout">
                        Continuar compra
                    </a>

                <?php else : ?>

                    <p>
                        Corrige las cantidades del carrito
                        antes de continuar con la compra.
                    </p>

                <?php endif; ?>
            </section>
        <?php endif; ?>
    </div>
</main>
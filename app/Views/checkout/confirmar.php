<?php

/**
 * Vista:
 * Confirmación del Checkout
 *
 * Variables disponibles:
 * ----------------------
 * $productos
 * $total
 * $checkoutDatos
 * $metodosPago
 * $metodoPagoSeleccionado
 * $errores
 */

?>

<main class="checkoutConfirmar">
    <div class="contenedor">
        <h1>
            Confirmar compra
        </h1>
        <?php if (!empty($errores)) : ?>
            <div class="alerta error">
                <ul>
                    <?php foreach ($errores as $error) : ?>
                        <li>
                            <?= htmlspecialchars($error) ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        <!--
        ============================================================
        DATOS DE ENVÍO
        ============================================================
        -->
        <section>
            <h2>
                Datos de envío
            </h2>
            <p>
                <strong>
                    Nombre:
                </strong>
                <?= htmlspecialchars(
                    $checkoutDatos['nombre'] ?? ''
                ) ?>
                <?= htmlspecialchars(
                    $checkoutDatos['apellido'] ?? ''
                ) ?>
            </p>
            <p>
                <strong>
                    Correo:
                </strong>
                <?= htmlspecialchars(
                    $checkoutDatos['correo'] ?? ''
                ) ?>
            </p>
            <p>
                <strong>
                    Teléfono:
                </strong>
                <?= htmlspecialchars(
                    $checkoutDatos['telefono'] ?? ''
                ) ?>
            </p>
            <p>
                <strong>
                    Dirección:
                </strong>
                <?= htmlspecialchars(
                    $checkoutDatos['calle'] ?? ''
                ) ?>
                <?= htmlspecialchars(
                    $checkoutDatos['numero'] ?? ''
                ) ?>
            </p>
            <p>
                <strong>
                    Colonia:
                </strong>
                <?= htmlspecialchars(
                    $checkoutDatos['colonia'] ?? ''
                ) ?>
            </p>
            <p>
                <strong>
                    Municipio:
                </strong>
                <?= htmlspecialchars(
                    $checkoutDatos['municipio'] ?? ''
                ) ?>
            </p>
            <p>
                <strong>
                    Estado:
                </strong>
                <?= htmlspecialchars(
                    $checkoutDatos['estado'] ?? ''
                ) ?>
            </p>
            <p>
                <strong>
                    Código postal:
                </strong>
                <?= htmlspecialchars(
                    $checkoutDatos['codigo_postal'] ?? ''
                ) ?>
            </p>
            <!--
            Regresa al formulario para modificar
            los datos de envío.
            -->
            <a href="/checkout">
                ← Modificar datos de envío
            </a>
        </section>
        <!--
        ============================================================
        PRODUCTOS
        ============================================================
        -->
        <section>
            <h2>
                Productos
            </h2>
            <?php foreach ($productos as $producto) : ?>
                <article>
                    <h3>
                        <?= htmlspecialchars(
                            $producto->nombre
                        ) ?>
                    </h3>
                    <p>
                        Cantidad:
                        <?= htmlspecialchars(
                            $producto->cantidad
                        ) ?>
                    </p>
                    <p>
                        Precio unitario:
                        $<?= number_format(
                                (float) $producto->precio_actual,
                                2
                            ) ?>
                    </p>
                    <p>
                        Subtotal:
                        $<?= number_format(
                                (float) $producto->precio_actual
                                    * (int) $producto->cantidad,
                                2
                            ) ?>
                    </p>
                </article>
            <?php endforeach; ?>
            <p>
                <strong>
                    Total:
                </strong>
                $<?= number_format(
                        (float) $total,
                        2
                    ) ?>
            </p>
        </section>
        <!--
        ============================================================
        MÉTODO DE PAGO
        ============================================================
        -->
        <section>
            <h2>
                Método de pago
            </h2>
            <form
                method="POST"
                action="/checkout/confirmar">
                <input
                    type="hidden"
                    name="checkout_token"
                    value="<?= htmlspecialchars(
                                $_SESSION['checkout_token'] ?? ''
                            ) ?>">
                <?php foreach ($metodosPago as $metodo) : ?>
                    <div>
                        <label>
                            <input
                                type="radio"
                                name="metodo_pago_id"
                                value="<?= htmlspecialchars(
                                            $metodo->id
                                        ) ?>"
                                <?= (
                                    (int) $metodoPagoSeleccionado
                                    === (int) $metodo->id
                                )
                                    ? 'checked'
                                    : ''
                                ?>>
                            <?= htmlspecialchars(
                                $metodo->nombre
                            ) ?>
                        </label>
                    </div>
                <?php endforeach; ?>
                <!--
                ====================================================
                ACCIONES
                ====================================================
                -->
                <div>
                    <a href="/checkout">
                        ← Regresar
                    </a>
                    <button type="submit">
                        Aceptar pedido
                    </button>
                </div>
            </form>
        </section>
    </div>
</main>
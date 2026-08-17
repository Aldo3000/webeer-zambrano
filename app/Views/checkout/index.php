<?php

/**
 * Vista:
 * Checkout
 *
 * Variables disponibles:
 * ----------------------
 * $productos
 * $total
 */

?>
<main class="checkout">
    <div class="contenedor">
        <h1>
            Checkout
        </h1>
        <p>
            Revisa tu pedido antes de continuar.
        </p>
        <!-- Resumen de productos -->
        <section class="checkoutResumen">
            <h2>
                Resumen de compra
            </h2>
            <?php foreach ($productos as $producto) : ?>
                <article class="checkoutProducto">
                    <h3>
                        <?= htmlspecialchars($producto->nombre) ?>
                    </h3>
                    <p>
                        Cantidad:
                        <?= htmlspecialchars($producto->cantidad) ?>
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
                                (float) $producto->subtotal,
                                2
                            ) ?>
                    </p>
                </article>
            <?php endforeach; ?>
            <div class="checkoutTotales">
                <p>
                    Subtotal:
                    <strong>
                        $<?= number_format(
                                (float) $total,
                                2
                            ) ?>
                    </strong>
                </p>
                <p>
                    Envío:
                    <strong>
                        Por calcular
                    </strong>
                </p>
                <p>
                    Total:
                    <strong>
                        $<?= number_format(
                                (float) $total,
                                2
                            ) ?>
                    </strong>
                </p>
            </div>
        </section>
        <!-- Datos de envío -->
        <section class="checkoutDatos">
            <h2>
                Datos de envío
            </h2>
            <?php if (!empty($checkoutErrores)) : ?>
                <div class="alerta error">
                    <ul>
                        <?php foreach ($checkoutErrores as $error) : ?>
                            <li>
                                <?= htmlspecialchars($error) ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            <form
                method="POST"
                action="/checkout">
                <!-- Nombre -->
                <div>
                    <label for="nombre">
                        Nombre
                    </label>
                    <input
                        type="text"
                        id="nombre"
                        name="nombre"
                        value="<?= htmlspecialchars(
                                    $checkoutDatos['nombre'] ?? ''
                                ) ?>">
                </div>
                <!-- Apellido -->
                <div>
                    <label for="apellido">
                        Apellido
                    </label>
                    <input
                        type="text"
                        id="apellido"
                        name="apellido"
                        value="<?= htmlspecialchars(
                                    $checkoutDatos['apellido'] ?? ''
                                ) ?>">
                </div>
                <!-- Correo -->
                <div>
                    <label for="correo">
                        Correo electrónico
                    </label>
                    <input
                        type="email"
                        id="correo"
                        name="correo"
                        value="<?= htmlspecialchars(
                                    $checkoutDatos['correo'] ?? ''
                                ) ?>">
                </div>
                <!-- Teléfono -->
                <div>
                    <label for="telefono">
                        Teléfono
                    </label>
                    <input
                        type="tel"
                        id="telefono"
                        name="telefono"
                        value="<?= htmlspecialchars(
                                    $checkoutDatos['telefono'] ?? ''
                                ) ?>">
                </div>
                <h3>
                    Dirección de envío
                </h3>
                <!-- Calle -->
                <div>
                    <label for="calle">
                        Calle
                    </label>
                    <input
                        type="text"
                        id="calle"
                        name="calle"
                        value="<?= htmlspecialchars(
                                    $checkoutDatos['calle'] ?? ''
                                ) ?>">
                </div>
                <!-- Número -->
                <div>
                    <label for="numero">
                        Número
                    </label>
                    <input
                        type="text"
                        id="numero"
                        name="numero"
                        value="<?= htmlspecialchars(
                                    $checkoutDatos['numero'] ?? ''
                                ) ?>">
                </div>
                <!-- Colonia -->
                <div>
                    <label for="colonia">
                        Colonia
                    </label>
                    <input
                        type="text"
                        id="colonia"
                        name="colonia"
                        value="<?= htmlspecialchars(
                                    $checkoutDatos['colonia'] ?? ''
                                ) ?>">
                </div>
                <!-- Municipio -->
                <div>
                    <label for="municipio">
                        Municipio
                    </label>
                    <input
                        type="text"
                        id="municipio"
                        name="municipio"
                        value="<?= htmlspecialchars(
                                    $checkoutDatos['municipio'] ?? ''
                                ) ?>">
                </div>
                <!-- Estado -->
                <div>
                    <label for="estado">
                        Estado
                    </label>
                    <input
                        type="text"
                        id="estado"
                        name="estado"
                        value="<?= htmlspecialchars(
                                    $checkoutDatos['estado'] ?? ''
                                ) ?>">
                </div>
                <!-- Código postal -->
                <div>
                    <label for="codigo_postal">
                        Código postal
                    </label>
                    <input
                        type="text"
                        id="codigo_postal"
                        name="codigo_postal"
                        value="<?= htmlspecialchars(
                                    $checkoutDatos['codigo_postal'] ?? ''
                                ) ?>">
                </div>
                <!-- Continuar -->
                <button type="submit">
                    Continuar
                </button>
            </form>
        </section>
        <!-- Información del cliente -->
        <section>

            <h2>
                Información del cliente
            </h2>

            <?php if ($cliente) : ?>

                <p>
                    Has iniciado sesión.
                </p>

                <p>
                    <?= htmlspecialchars($cliente->nombre) ?>
                    <?= htmlspecialchars($cliente->apellido) ?>
                </p>

                <p>
                    <?= htmlspecialchars($cliente->correo) ?>
                </p>

            <?php else : ?>

                <p>
                    Estás comprando como invitado.
                </p>

                <p>
                    En el siguiente paso podrás
                    introducir tus datos.
                </p>

            <?php endif; ?>

        </section>
    </div>
</main>
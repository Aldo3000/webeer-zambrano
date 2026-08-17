<?php

/**
 * Vista:
 * Pedido realizado correctamente.
 *
 * Variables disponibles:
 * ----------------------
 * $pedido
 */

?>

<main class="checkoutExitoso">
    <div class="contenedor">
        <section>
            <h1>
                ¡Pedido realizado correctamente!
            </h1>
            <p>
                Gracias por tu compra.
            </p>
            <p>
                Tu número de pedido es:
                <strong>
                    <?= htmlspecialchars(
                        $pedido['numero_pedido']
                    ) ?>
                </strong>
            </p>
            <p>
                Total:
                <strong>
                    $<?= number_format(
                        (float) $pedido['total'],
                        2
                    ) ?>
                </strong>
            </p>
            <div>
                <a href="/productos">
                    Seguir comprando
                </a>
            </div>
        </section>
    </div>
</main>
<?php

/**
 * Vista:
 * Detalle de pedido del cliente.
 *
 * Variables disponibles:
 * ----------------------
 * $pedido
 * $detalles
 */


/*
|--------------------------------------------------------------------------
| Estado actual del pedido
|--------------------------------------------------------------------------
*/

$estadoPedido =
    (int) $pedido->estado_pedido_id;

$estadoPago =
    (int) $pedido->estado_pago_id;


/*
|--------------------------------------------------------------------------
| Determinar si el pedido está cancelado
|--------------------------------------------------------------------------
*/

$pedidoCancelado =
    $estadoPedido === 6;


/*
|--------------------------------------------------------------------------
| Estados del pedido
|--------------------------------------------------------------------------
*/

$estadosSeguimiento = [
    1 => 'Pedido recibido',
    2 => 'Pago confirmado',
    3 => 'Preparando',
    4 => 'Enviado',
    5 => 'Entregado'
];



$estadosPago = [
    1 => 'Pendiente',
    2 => 'Pagado',
    3 => 'Fallido',
    4 => 'Reembolsado'
];

$metodosPago = [
    1 => 'Contra entrega',
    2 => 'Transferencia',
    3 => 'Pago Online'
];



?>

<main class="cuentaPedido">
    <div class="contenedor">
        <!-- Encabezado -->
        <section class="pedidoEncabezado">
            <h1>
                Pedido
                <?= htmlspecialchars(
                    $pedido->numero_pedido
                ) ?>
            </h1>
            <p>
                Fecha:
                <?= htmlspecialchars(
                    $pedido->created_at
                ) ?>
            </p>
        </section>
        <!-- Seguimiento -->
        <section class="pedidoSeguimiento">
            <h2>
                Seguimiento del pedido
            </h2>
            <?php if ($pedidoCancelado) : ?>
                <div class="pedidoCancelado">
                    <strong>
                        ✕ Pedido cancelado
                    </strong>
                    <p>
                        Este pedido fue cancelado.
                    </p>
                </div>
            <?php else : ?>
                <ol class="seguimiento">
                    <!-- Pedido recibido -->
                    <li class="completado">
                        <span>
                            ✓
                        </span>
                        <strong>
                            Pedido recibido
                        </strong>
                    </li>
                    <!-- Pago confirmado -->
                    <?php
                    $pagoConfirmado =
                        $estadoPago === 2;
                    ?>
                    <li
                        class="<?= $pagoConfirmado
                                    ? 'completado'
                                    : 'actual' ?>">
                        <span>
                            <?= $pagoConfirmado
                                ? '✓'
                                : '●' ?>
                        </span>
                        <strong>
                            Pago confirmado
                        </strong>
                    </li>
                    <!-- Preparando -->
                    <?php
                    $preparando =
                        $estadoPedido === 3;
                    $preparandoCompletado =
                        $estadoPedido >= 4;
                    ?>
                    <li
                        class="<?= $preparandoCompletado
                                    ? 'completado'
                                    : ($preparando
                                        ? 'actual'
                                        : '') ?>">
                        <span>
                            <?php if ($preparandoCompletado) : ?>
                                ✓
                            <?php elseif ($preparando) : ?>
                                ●
                            <?php else : ?>
                                ○
                            <?php endif; ?>
                        </span>
                        <strong>
                            Preparando
                        </strong>
                    </li>
                    <!-- Enviado -->
                    <?php
                    $enviado =
                        $estadoPedido === 4;
                    $enviadoCompletado =
                        $estadoPedido >= 5;
                    ?>
                    <li
                        class="<?= $enviadoCompletado
                                    ? 'completado'
                                    : ($enviado
                                        ? 'actual'
                                        : '') ?>">
                        <span>
                            <?php if ($enviadoCompletado) : ?>
                                ✓

                            <?php elseif ($enviado) : ?>
                                ●
                            <?php else : ?>
                                ○
                            <?php endif; ?>
                        </span>
                        <strong>
                            Enviado
                        </strong>
                    </li>
                    <!-- Entregado -->
                    <?php
                    $entregado =
                        $estadoPedido === 5;
                    ?>
                    <li
                        class="<?= $entregado
                                    ? 'actual'
                                    : '' ?>">
                        <span>
                            <?= $entregado
                                ? '●'
                                : '○' ?>
                        </span>
                        <strong>
                            Entregado
                        </strong>
                    </li>
                </ol>
            <?php endif; ?>
        </section>
        <!-- Estado del pago -->
        <section class="pedidoPago">
            <h2>
                Información del pago
            </h2>
            <p>
                Estado de pago:
                <strong>
                    <?= htmlspecialchars(
                        $estadosPago[$estadoPago] ?? 'Desconocido'
                    ) ?>
                </strong>
            </p>


            <p>
                Método de pago:
                <strong>
                    <?= htmlspecialchars(
                        $metodosPago[(int) $pedido->metodo_pago_id] ?? 'Desconocido'
                    ) ?>
                </strong>
            </p>
        </section>
        <!-- Productos -->
        <section class="pedidoProductos">
            <h2>
                Productos
            </h2>
            <?php foreach ($detalles as $detalle) : ?>
                <article class="pedidoProducto">
                    <h3>
                        <?= htmlspecialchars(
                            $detalle->nombre_producto
                        ) ?>
                    </h3>
                    <p>
                        SKU:
                        <?= htmlspecialchars(
                            $detalle->sku
                        ) ?>
                    </p>
                    <p>
                        Cantidad:
                        <?= (int) $detalle->cantidad ?>
                    </p>
                    <p>
                        Precio unitario:
                        $<?= number_format(
                                (float) $detalle->precio_unitario,
                                2
                            ) ?>
                    </p>
                    <p>
                        Subtotal:
                        $<?= number_format(
                                (float) $detalle->subtotal,
                                2
                            ) ?>
                    </p>
                </article>
            <?php endforeach; ?>
        </section>
        <!-- Resumen -->
        <section class="pedidoResumen">
            <h2>
                Resumen de compra
            </h2>
            <p>
                Subtotal:
                $<?= number_format(
                        (float) $pedido->subtotal,
                        2
                    ) ?>
            </p>
            <p>
                Envío:
                $<?php /* number_format(
                        (float) $pedido->envio,
                        2
                    ) */ ?>
                0.00
            </p>
            <p>
                Total:
                <strong>
                    $<?= number_format(
                            (float) $pedido->total,
                            2
                        ) ?>
                </strong>
            </p>
        </section>
        <!-- Dirección -->
        <section class="pedidoEnvio">
            <h2>
                Datos de envío
            </h2>
            <p>
                <?= htmlspecialchars(
                    $pedido->nombre
                ) ?>
                <?= htmlspecialchars(
                    $pedido->apellido
                ) ?>
            </p>
            <p>
                <?= htmlspecialchars(
                    $pedido->calle
                ) ?>
                <?= htmlspecialchars(
                    $pedido->numero
                ) ?>
            </p>
            <p>
                <?= htmlspecialchars(
                    $pedido->colonia
                ) ?>
            </p>
            <p>
                <?= htmlspecialchars(
                    $pedido->municipio
                ) ?>
                ,
                <?= htmlspecialchars(
                    $pedido->estado
                ) ?>
            </p>
            <p>
                C.P.
                <?= htmlspecialchars(
                    $pedido->codigo_postal
                ) ?>
            </p>
            <p>
                Teléfono:
                <?= htmlspecialchars(
                    $pedido->telefono
                ) ?>
            </p>

            <form
                method="POST"
                action="/cuenta/pedido/repetir">

                <input
                    type="hidden"
                    name="id"
                    value="<?= (int) $pedido->id ?>">

                <button type="submit">
                    Volver a comprar
                </button>

            </form>
        </section>

        <!-- Regresar -->
        <p>
            <a href="/cuenta/pedidos">
                ← Volver a mis pedidos
            </a>
        </p>
    </div>
</main>
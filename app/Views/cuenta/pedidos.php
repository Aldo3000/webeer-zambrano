<?php

/**
 * Vista:
 * Historial de pedidos del cliente.
 *
 * Variables disponibles:
 * ----------------------
 * $pedidos
 */

$estadosPedido = [
    1 => 'Pendiente',
    2 => 'Pagado',
    3 => 'Preparando',
    4 => 'Enviado',
    5 => 'Entregado',
    6 => 'Cancelado'
];

?>

<main class="cuentaPedidos">
    <div class="contenedor">
        <h1>
            Mis pedidos
        </h1>
        <section class="pedidosFiltros">
            <h2>
                Filtrar pedidos
            </h2>
            <nav>
                <a
                    href="/cuenta/pedidos"
                    class="<?= $filtro === ''
                                ? 'activo'
                                : '' ?>">
                    Todos
                </a>
                <a
                    href="/cuenta/pedidos?estado=pendiente"
                    class="<?= $filtro === 'pendiente'
                                ? 'activo'
                                : '' ?>">
                    Pendientes
                </a>
                <a
                    href="/cuenta/pedidos?estado=preparando"
                    class="<?= $filtro === 'preparando'
                                ? 'activo'
                                : '' ?>">
                    Preparando
                </a>
                <a
                    href="/cuenta/pedidos?estado=enviado"
                    class="<?= $filtro === 'enviado'
                                ? 'activo'
                                : '' ?>">
                    Enviados
                </a>
                <a
                    href="/cuenta/pedidos?estado=entregado"
                    class="<?= $filtro === 'entregado'
                                ? 'activo'
                                : '' ?>">
                    Entregados
                </a>
                <a
                    href="/cuenta/pedidos?estado=cancelado"
                    class="<?= $filtro === 'cancelado'
                                ? 'activo'
                                : '' ?>">
                    Cancelados
                </a>
            </nav>
        </section>
        <?php if (empty($pedidos)) : ?>
            <p>
                Todavía no tienes pedidos.
            </p>
            <a href="/productos">
                Ver productos
            </a>
        <?php else : ?>
            <section>
                <?php foreach ($pedidos as $pedido) : ?>
                    <article>
                        <h2>
                            Pedido
                            <?= htmlspecialchars(
                                $pedido->numero_pedido
                            ) ?>
                        </h2>
                        <p>
                            Fecha:
                            <?= htmlspecialchars(
                                $pedido->created_at
                            ) ?>
                        </p>
                        <p>
                            Total:
                            $<?= number_format(
                                    (float) $pedido->total,
                                    2
                                ) ?>
                        </p>
                        <p>
                            Estado del pedido:
                            <?= htmlspecialchars(
                                $estadosPedido[(int) $pedido->estado_pedido_id] ?? 'Desconocido'
                            ) ?>
                        </p>
                        <p>
                            Estado del pago:
                            <?= htmlspecialchars(
                            (int) $pedido->estado_pago_id
                            ) ?>
                        </p>
                        <a href="/cuenta/pedido?id=<?= (int) $pedido->id ?>">
                            Ver detalles
                        </a>
                    </article>
                <?php endforeach; ?>
            </section>
        <?php endif; ?>
        <p>
            <a href="/cuenta">
                ← Volver a mi cuenta
            </a>
        </p>
    </div>
</main>
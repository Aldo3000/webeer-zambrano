<?php

/**
 * Vista:
 * Administración de pedidos.
 *
 * Variables disponibles:
 * ----------------------
 * $pedidos
 */

?>

<main class="adminPedidos">
    <section class="pedidosFiltros">
        <form
            method="GET"
            action="/admin/pedidos">
            <div>
                <label for="busqueda">
                    Buscar pedido
                </label>
                <input
                    type="search"
                    id="busqueda"
                    name="busqueda"
                    placeholder="Número, nombre o correo..."
                    value="<?= htmlspecialchars(
                                $busqueda
                            ) ?>">
            </div>
            <div>
                <label for="estado">
                    Estado
                </label>
                <select
                    id="estado"
                    name="estado">
                    <option
                        value="">
                        Todos
                    </option>
                    <option
                        value="pendiente"
                        <?= $estado === 'pendiente'
                            ? 'selected'
                            : '' ?>>
                        Pendiente
                    </option>

                    <option
                        value="pagado"
                        <?= $estado === 'pagado'
                            ? 'selected'
                            : '' ?>>
                        Pagado
                    </option>

                    <option
                        value="preparando"
                        <?= $estado === 'preparando'
                            ? 'selected'
                            : '' ?>>
                        Preparando
                    </option>

                    <option
                        value="enviado"
                        <?= $estado === 'enviado'
                            ? 'selected'
                            : '' ?>>
                        Enviado
                    </option>

                    <option
                        value="entregado"
                        <?= $estado === 'entregado'
                            ? 'selected'
                            : '' ?>>
                        Entregado
                    </option>

                    <option
                        value="cancelado"
                        <?= $estado === 'cancelado'
                            ? 'selected'
                            : '' ?>>
                        Cancelado
                    </option>
                </select>
            </div>
            <button type="submit">
                Buscar
            </button>
            <?php if (
                $busqueda !== ''
                || $estado !== ''
            ) : ?>
                <a href="/admin/pedidos">
                    Limpiar filtros
                </a>
            <?php endif; ?>
        </form>
    </section>
    <div class="contenedor">
        <h1>
            Pedidos
        </h1>
        <section class="listaPedidos">
            <?php if (empty($pedidos)) : ?>
                <p>
                    No se encontraron pedidos
                    con los filtros seleccionados.
                </p>
            <?php else : ?>
                <?php
                $estadosPedido = [
                    1 => 'Pendiente',
                    2 => 'Pagado',
                    3 => 'Preparando',
                    4 => 'Enviado',
                    5 => 'Entregado',
                    6 => 'Cancelado'
                ];
                ?>
                <?php foreach ($pedidos as $pedido) : ?>
                    <article class="pedidoAdmin">
                        <h2>
                            <?= htmlspecialchars(
                                $pedido->numero_pedido
                            ) ?>
                        </h2>
                        <p>
                            Cliente:
                            <?= htmlspecialchars(
                                $pedido->nombre
                            ) ?>
                            <?= htmlspecialchars(
                                $pedido->apellido
                            ) ?>
                        </p>
                        <p>
                            Correo:
                            <?= htmlspecialchars(
                                $pedido->correo
                            ) ?>
                        </p>
                        <p>
                            Estado:
                            <strong>
                                <?= htmlspecialchars(
                                    $estadosPedido[(int)
                                        $pedido->estado_pedido_id] ?? 'Desconocido'
                                ) ?>
                            </strong>
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
                        <p>
                            Fecha:
                            <?= htmlspecialchars(
                                $pedido->created_at
                            ) ?>
                        </p>
                        <a
                            href="/admin/pedido?id=<?= (int) $pedido->id ?>">
                            Ver pedido
                        </a>
                    </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </section>
    </div>
</main>
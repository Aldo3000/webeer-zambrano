<?php

/**
 * Vista:
 * Detalle administrativo del cliente.
 *
 * Variables disponibles:
 * ----------------------
 * $cliente
 * $pedidos
 */

$activo = (int) $cliente->activo;

$adminExito = $_SESSION['admin_exito'] ?? null;
$adminError = $_SESSION['admin_error'] ?? null;

unset($_SESSION['admin_exito'], $_SESSION['admin_error']);

?>

<main class="adminCliente">
    <div class="contenedor">
        <h1>
            Cliente
        </h1>
        <section class="clienteEstado">

            <h2>
                Estado de la cuenta
            </h2>

            <p>
                Estado:

                <strong>
                    <?= $activo ? 'Activo' : 'Inactivo' ?>
                </strong>
            </p>

            <form
                method="POST"
                action="/admin/cliente/estado">

                <input
                    type="hidden"
                    name="id"
                    value="<?= (int) $cliente->id ?>">

                <input
                    type="hidden"
                    name="activo"
                    value="<?= $activo ? 0 : 1 ?>">

                <button type="submit">

                    <?= $activo
                        ? 'Desactivar cliente'
                        : 'Activar cliente' ?>

                </button>

            </form>

        </section>
        <section class="clienteInformacion">
            <h2>
                Información personal
            </h2>
            <p>
                Nombre:
                <?= htmlspecialchars(
                    $cliente->nombre
                ) ?>
                <?= htmlspecialchars(
                    $cliente->apellido
                ) ?>
            </p>

            <p>
                Correo:
                <?= htmlspecialchars(
                    $cliente->correo
                ) ?>
            </p>

            <p>
                Teléfono:
                <?= htmlspecialchars(
                    $cliente->telefono
                ) ?>
            </p>
            <a
                href="/admin/cliente/editar?id=<?= (int) $cliente->id ?>">
                Editar cliente
            </a>
        </section>
        <section class="clientePedidos">
            <h2>
                Pedidos del cliente
            </h2>
            <?php if (empty($pedidos)) : ?>
                <p>
                    Este cliente todavía
                    no tiene pedidos.
                </p>
            <?php else : ?>
                <?php foreach ($pedidos as $pedido) : ?>
                    <article>
                        <h3>
                            <?= htmlspecialchars(
                                $pedido->numero_pedido
                            ) ?>
                        </h3>
                        <p>
                            Total:
                            $<?= number_format(
                                    (float) $pedido->total,
                                    2
                                ) ?>
                        </p>
                        <p>
                            Estado:
                            <?= (int)
                            $pedido->estado_pedido_id ?>
                        </p>
                        <a
                            href="/admin/pedido?id=<?= (int) $pedido->id ?>">
                            Ver pedido
                        </a>
                    </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </section>
        <p>
            <a href="/admin/clientes">
                ← Volver a clientes
            </a>
        </p>
    </div>
</main>
<?php

$estadosPedido = [
    1 => 'Pendiente',
    2 => 'Pagado',
    3 => 'Preparando',
    4 => 'Enviado',
    5 => 'Entregado',
    6 => 'Cancelado'
];

$estadoActual =
    (int) $pedido->estado_pedido_id;



$adminExito = $_SESSION['admin_exito'] ?? null;

$adminError = $_SESSION['admin_error'] ?? null;

unset($_SESSION['admin_exito']);
unset($_SESSION['admin_error']);



?>

<section class="pedidoAdminEstado">
    <?php if ($adminExito) : ?>

        <p class="alerta exito">
            <?= htmlspecialchars($adminExito) ?>
        </p>

    <?php endif; ?>


    <?php if ($adminError) : ?>

        <p class="alerta error">
            <?= htmlspecialchars($adminError) ?>
        </p>

    <?php endif; ?>

    <h2>
        Estado del pedido
    </h2>

    <p>
        Estado actual:

        <strong>
            <?= htmlspecialchars(
                $estadosPedido[$estadoActual] ?? 'Desconocido'
            ) ?>
        </strong>
    </p>


    <form
        method="POST"
        action="/admin/pedido/estado">

        <input
            type="hidden"
            name="id"
            value="<?= (int) $pedido->id ?>">


        <label
            for="estado_pedido_id">

            Cambiar estado

        </label>


        <select
            id="estado_pedido_id"
            name="estado_pedido_id">

            <?php foreach (
                $estadosPedido
                as $id => $nombre
            ) : ?>

                <option
                    value="<?= $id ?>"
                    <?= $estadoActual === $id
                        ? 'selected'
                        : '' ?>>

                    <?= htmlspecialchars(
                        $nombre
                    ) ?>

                </option>

            <?php endforeach; ?>

        </select>


        <button type="submit">

            Actualizar estado

        </button>

    </form>

</section>
<?php

/**
 * Vista:
 * Administración de clientes.
 *
 * Variables disponibles:
 * ----------------------
 * $clientes
 * $busqueda
 */

?>

<main class="adminClientes">
    <div class="contenedor">
        <h1>
            Clientes
        </h1>
        <!-- Búsqueda -->
        <section class="clientesFiltros">
            <form
                method="GET"
                action="/admin/clientes">
                <label for="busqueda">
                    Buscar cliente
                </label>
                <input
                    type="search"
                    id="busqueda"
                    name="busqueda"
                    placeholder="Nombre, apellido o correo..."
                    value="<?= htmlspecialchars(
                                $busqueda
                            ) ?>">
                <button type="submit">
                    Buscar
                </button>
                <?php if ($busqueda !== '') : ?>
                    <a href="/admin/clientes">
                        Limpiar búsqueda
                    </a>
                <?php endif; ?>
            </form>
        </section>
        <!-- Clientes -->
        <section class="listaClientes">
            <?php if (empty($clientes)) : ?>
                <p>
                    No se encontraron clientes.
                </p>
            <?php else : ?>
                <?php foreach ($clientes as $cliente) : ?>
                    <article class="clienteAdmin">
                        <h2>
                            <?= htmlspecialchars(
                                $cliente->nombre
                            ) ?>

                            <?= htmlspecialchars(
                                $cliente->apellido
                            ) ?>

                        </h2>
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
                        <p>
                            Estado:

                            <strong>
                                <?= (int) $cliente->activo
                                    ? 'Activo'
                                    : 'Inactivo' ?>
                            </strong>
                        </p>
                        <a
                            href="/admin/cliente?id=<?= (int) $cliente->id ?>">
                            Ver cliente
                        </a>
                    </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </section>
    </div>
</main>
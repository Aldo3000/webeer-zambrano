<?php

/**
 * Vista:
 * Editar cliente.
 *
 * Variables:
 * ----------------------
 * $cliente
 * $errores
 */

?>

<main class="adminClienteEditar">
    <div class="contenedor">
        <h1>
            Editar cliente
        </h1>
        <?php if (!empty($errores)) : ?>
            <div class="alerta error">
                <ul>
                    <?php foreach ($errores as $error) : ?>
                        <li>
                            <?= htmlspecialchars(
                                $error
                            ) ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        <form
            method="POST"
            action="/admin/cliente/actualizar">
            <input
                type="hidden"
                name="id"
                value="<?= (int) $cliente->id ?>">
            <section>
                <h2>
                    Información personal
                </h2>
                <div>
                    <label for="nombre">
                        Nombre
                    </label>
                    <input
                        type="text"
                        id="nombre"
                        name="nombre"
                        value="<?= htmlspecialchars(
                            $cliente->nombre
                        ) ?>">
                </div>
                <div>
                    <label for="apellido">
                        Apellido
                    </label>
                    <input
                        type="text"
                        id="apellido"
                        name="apellido"
                        value="<?= htmlspecialchars(
                            $cliente->apellido
                        ) ?>">
                </div>
                <div>
                    <label for="correo">
                        Correo
                    </label>
                    <input
                        type="email"
                        id="correo"
                        name="correo"
                        value="<?= htmlspecialchars(
                            $cliente->correo
                        ) ?>">
                </div>
                <div>
                    <label for="telefono">
                        Teléfono
                    </label>
                    <input
                        type="tel"
                        id="telefono"
                        name="telefono"
                        value="<?= htmlspecialchars(
                            $cliente->telefono
                        ) ?>">
                </div>
            </section>
            <section>
                <h2>
                    Dirección
                </h2>
                <div>
                    <label for="calle">
                        Calle
                    </label>
                    <input
                        type="text"
                        id="calle"
                        name="calle"
                        value="<?= htmlspecialchars(
                            $cliente->calle
                        ) ?>">
                </div>
                <div>
                    <label for="numero">
                        Número
                    </label>
                    <input
                        type="text"
                        id="numero"
                        name="numero"
                        value="<?= htmlspecialchars(
                            $cliente->numero
                        ) ?>">
                </div>
                <div>
                    <label for="colonia">
                        Colonia
                    </label>
                    <input
                        type="text"
                        id="colonia"
                        name="colonia"
                        value="<?= htmlspecialchars(
                            $cliente->colonia
                        ) ?>">
                </div>
                <div>
                    <label for="municipio">
                        Municipio
                    </label>
                    <input
                        type="text"
                        id="municipio"
                        name="municipio"
                        value="<?= htmlspecialchars(
                            $cliente->municipio
                        ) ?>">
                </div>
                <div>
                    <label for="estado">
                        Estado
                    </label>
                    <input
                        type="text"
                        id="estado"
                        name="estado"
                        value="<?= htmlspecialchars(
                            $cliente->estado
                        ) ?>">
                </div>
                <div>
                    <label for="codigo_postal">
                        Código postal
                    </label>
                    <input
                        type="text"
                        id="codigo_postal"
                        name="codigo_postal"
                        value="<?= htmlspecialchars(
                            $cliente->codigo_postal
                        ) ?>">
                </div>
            </section>
            <button type="submit">
                Guardar cambios
            </button>
            <a
                href="/admin/cliente?id=<?= (int) $cliente->id ?>">
                Cancelar
            </a>
        </form>
    </div>
</main>
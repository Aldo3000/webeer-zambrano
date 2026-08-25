<?php

/**
 * Vista:
 * Perfil del cliente.
 *
 * Variables:
 * -----------
 * $usuario
 * $errores
 */

$perfilExito = $_SESSION['perfil_exito'] ?? null;
unset($_SESSION['perfil_exito']);

?>

<main class="perfil">
    <div class="contenedor">
        <h1>
            Mis datos
        </h1>
        <?php if (!empty($perfilExito)) : ?>
            <div class="alerta exito">
                <?= htmlspecialchars($perfilExito) ?>
            </div>
        <?php endif; ?>

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

        <section class="perfilSeccion">
            <h2>
                Datos personales
            </h2>
            <form
                method="POST"
                action="/cuenta/perfil">
                <input
                    type="hidden"
                    name="seccion"
                    value="personales">
                <div>
                    <label for="nombre">
                        Nombre
                    </label>
                    <input
                        type="text"
                        id="nombre"
                        name="nombre"
                        value="<?= htmlspecialchars(
                                    $usuario->nombre
                                ) ?>"
                        required>
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
                                    $usuario->apellido
                                ) ?>"
                        required>
                </div>

                <div>
                    <label for="correo">
                        Correo electrónico
                    </label>
                    <input
                        type="email"
                        id="correo"
                        name="correo"
                        value="<?= htmlspecialchars(
                                    $usuario->correo
                                ) ?>"
                        required>
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
                                    $usuario->telefono
                                ) ?>"
                        required>
                </div>
                <button type="submit">
                    Guardar datos personales
                </button>
            </form>
        </section>

        <section class="perfilSeccion">
            <h2>
                Dirección de envío
            </h2>
            <form
                method="POST"
                action="/cuenta/perfil">
                <input
                    type="hidden"
                    name="seccion"
                    value="direccion">
                <div>
                    <label for="calle">
                        Calle
                    </label>
                    <input
                        type="text"
                        id="calle"
                        name="calle"
                        value="<?= htmlspecialchars(
                                    $usuario->calle
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
                                    $usuario->numero
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
                                    $usuario->colonia
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
                                    $usuario->municipio
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
                                    $usuario->estado
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
                                    $usuario->codigo_postal
                                ) ?>">
                </div>
                <button type="submit">
                    Guardar dirección
                </button>
            </form>
        </section>
        <p>
            <a href="/cuenta">
                ← Volver a mi cuenta
            </a>
        </p>
    </div>
</main>
<?php

/**
 * Vista:
 * Inicio de Sesión
 *
 * Variables disponibles:
 * ----------------------
 * $correo
 * $errores
 */

?>

<main class="contenedor">

    <!-- Encabezado -->
    <header class="encabezado">
        <h1>
            Iniciar Sesión
        </h1>
        <p>
            Ingresa tus credenciales para acceder
            al panel de administración.
        </p>
    </header>


    <!-- Mostrar errores de validación -->
    <?php if (!empty($errores)) : ?>
        <section class="errores">
            <?php foreach ($errores as $error) : ?>
                <p class="alerta error">
                    <?= htmlspecialchars($error) ?>
                </p>
            <?php endforeach; ?>
        </section>
    <?php endif; ?>

    <!-- Formulario de inicio de sesión -->
    <section class="formulario">
        <form
            method="POST"
            action="/login">
            <!-- Correo electrónico -->
            <div>
                <label for="correo">
                    Correo electrónico
                </label>

                <input
                    type="email"
                    id="correo"
                    name="correo"
                    value="<?= htmlspecialchars($correo) ?>"
                    placeholder="correo@ejemplo.com"
                    required>
            </div>
            <!-- Contraseña -->
            <div>
                <label for="password">
                    Contraseña
                </label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Ingresa tu contraseña"
                    required>
            </div>
            <!-- Botón -->
            <div>
                <input
                    type="submit"
                    value="Iniciar Sesión"
                    class="boton boton-verde">
            </div>
        </form>
    </section>
</main>
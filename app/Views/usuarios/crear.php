<?php

/**
 * Vista:
 * Crear Usuario
 *
 * Variables disponibles:
 * ----------------------
 * $usuario
 * $errores
 */

?>
<main class="contenedor">
    <!-- Encabezado -->
    <header class="encabezado">
        <h1>
            Crear Usuario
        </h1>
        <p>
            Completa el siguiente formulario para
            registrar un nuevo usuario.
        </p>
    </header>
    <!-- Volver -->
    <section class="acciones">
        <a
            href="/admin/usuarios"
            class="boton">
            ← Volver al listado
        </a>
    </section>
    <!-- Errores -->
    <?php if (!empty($errores)) : ?>
        <section class="errores">
            <?php foreach ($errores as $error) : ?>
                <p class="alerta error">
                    <?= htmlspecialchars($error) ?>
                </p>
            <?php endforeach; ?>
        </section>
    <?php endif; ?>
    <!-- Formulario -->
    <section class="formulario">
        <form
            method="POST"
            action="/admin/usuarios/crear">
            <?php include __DIR__ . '/_formulario.php'; ?>
            <input
                type="submit"
                value="Guardar Usuario"
                class="boton boton-verde">
        </form>
    </section>
</main>
<?php

/**
 * Vista:
 * Crear Categoría
 *
 * Variables disponibles:
 * ----------------------
 * $categoria
 * $errores
 */

?>

<main class="contenedor">
    <!-- Encabezado -->
    <header class="encabezado">
        <h1>Crear Categoría</h1>
        <p>
            Completa el siguiente formulario para registrar
            una nueva categoría.
        </p>
    </header>

    <!-- Botón para regresar -->
    <section class="acciones">
        <a
            href="/admin/categorias"
            class="boton">
            ← Volver al listado
        </a>
    </section>

    <!-- Mostrar errores -->
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
        <form method="POST"
        action="/admin/categorias/crear">
            <?php include __DIR__ . '/_formulario.php'; ?>
            <input
                type="submit"
                value="Guardar Categoría"
                class="boton boton-verde">
        </form>
    </section>
</main>
<?php

/**
 * Vista:
 * Editar Categoría
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
        <h1>Editar Categoría</h1>
        <p>
            Modifica la información de la categoría
            y guarda los cambios realizados.
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

    <!-- Formulario -->
    <section class="formulario">
        <form method="POST">
            <?php include __DIR__ . '/_formulario.php'; ?>
            <input
                type="submit"
                value="Guardar Cambios"
                class="boton boton-verde">
        </form>
    </section>
</main>
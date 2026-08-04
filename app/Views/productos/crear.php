<?php

/**
 * Vista:
 * Crear Producto
 *
 * Variables disponibles:
 * ----------------------
 * $producto
 * $errores
 */
?>

<main class="contenedor">
    <header>
        <h1>Nuevo Producto</h1>
        <h2>Categorías</h2>

        <p>
            Completa la siguiente información para registrar
            un nuevo producto.
        </p>
    </header>
    <a href="/admin/productos">
        ← Volver
    </a>

    <?php if (!empty($errores)) : ?>
        <div>
            <?php foreach ($errores as $error) : ?>
                <p>
                    <?= htmlspecialchars($error) ?>
                </p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form
        method="POST"
        enctype="multipart/form-data"
        action="/admin/productos/crear">
        <?php include __DIR__ . '/_formulario.php'; ?>
        <button type="submit">
            Guardar Producto
        </button>
    </form>
</main>
<main class="contenedor">
    <h1>Editar Producto</h1>

    <a href="/admin/productos">
        Volver
    </a>

    <?php foreach ($errores as $error) : ?>
        <div>
            <?= $error ?>
        </div>
    <?php endforeach; ?>

    <form method="POST">

        <?php include __DIR__ . '/_formulario.php'; ?>

        <input
            type="submit"
            value="Guardar Cambios">

    </form>
</main>
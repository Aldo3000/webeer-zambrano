<h1>Categorías y marcas</h1>

<form method="GET">
    <label for="categoria_id">Categoría</label>

    <select name="categoria_id" id="categoria_id" onchange="this.form.submit()">
        <option value="">Selecciona una categoría</option>

        <?php foreach ($categorias as $categoria): ?>
            <option value="<?= $categoria->id ?>"
                <?= $categoriaId == $categoria->id ? 'selected' : '' ?>>
                <?= htmlspecialchars($categoria->nombre) ?>
            </option>
        <?php endforeach; ?>
    </select>
</form>

<?php if ($categoriaId): ?>

    <form method="POST">
        <input type="hidden" name="categoria_id" value="<?= $categoriaId ?>">

        <h2>Marcas disponibles</h2>

        <?php foreach ($marcas as $marca): ?>
            <label>
                <input
                    type="checkbox"
                    name="marcas[]"
                    value="<?= $marca->id ?>"
                    <?= in_array($marca->id, $idsAsignados) ? 'checked' : '' ?>
                >
                <?= htmlspecialchars($marca->nombre) ?>
            </label>
        <?php endforeach; ?>

        <button type="submit">Guardar relaciones</button>
    </form>

<?php endif; ?>
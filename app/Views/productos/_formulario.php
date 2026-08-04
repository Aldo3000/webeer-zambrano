<div>
    <label for="sku">
        SKU
    </label>
    <input
        type="text"
        id="sku"
        name="producto[sku]"
        value="<?= htmlspecialchars($producto->sku) ?>">
</div>

<div>
    <label for="nombre">
        Nombre
    </label>

    <input
        type="text"
        id="nombre"
        name="producto[nombre]"
        value="<?= htmlspecialchars($producto->nombre) ?>">
</div>

<div>
    <label for="descripcion">
        Descripción
    </label>
    <textarea
        id="descripcion"
        name="producto[descripcion]"><?= htmlspecialchars($producto->descripcion) ?></textarea>
</div>

<div>
    <label for="precio_original">
        Precio Original
    </label>
    <input
        type="number"
        step="0.01"
        id="precio_original"
        name="producto[precio_original]"
        value="<?= htmlspecialchars($producto->precio_original) ?>">
</div>

<div>
    <label for="precio_actual">
        Precio
    </label>

    <input
        type="number"
        step="0.01"
        id="precio_actual"
        name="producto[precio_actual]"
        value="<?= htmlspecialchars($producto->precio_actual) ?>">
</div>

<div>
    <label for="stock">
        Stock
    </label>
    <input
        type="number"
        id="stock"
        name="producto[stock]"
        value="<?= htmlspecialchars($producto->stock) ?>">
</div>

<div>
    <?php if (!empty($producto->imagen_principal)) : ?>
        <div>
            <p>
                Imagen actual
            </p>
            <img
                src="/imagenes/<?= htmlspecialchars($producto->imagen_principal) ?>"
                alt="<?= htmlspecialchars($producto->nombre) ?>"
                width="200">
        </div>
    <?php endif; ?>
    <label for="imagen">
        Imagen principal
    </label>

    <input
        type="file"
        id="imagen"
        name="imagen"
        accept="image/*">

</div>

<label for="categoria_id">
    Categoría
</label>

<select
    id="categoria_id"
    name="producto[categoria_id]">
    <!-- <option
        value="<?= $categoria->id ?>"
       <?= $producto->categoria_id == $categoria->id ? 'selected' : '' ?>>
        <?= htmlspecialchars($categoria->nombre) ?>
    </option> -->

    <option value="">
        -- Seleccione una categoría --
    </option>

    <?php foreach ($categorias as $categoria) : ?>
        <option
            value="<?= $categoria->id ?>">
            <?= htmlspecialchars($categoria->nombre) ?>
        </option>
    <?php endforeach; ?>
</select>


<label for="categoria_id">
    Marca
</label>

<select
    id="marca_id"
    name="producto[marca_id]">
    <!--<option
        value="<?= $marca->id ?>"
        <?= $producto->marca_id == $marca->id ? 'selected' : '' ?>>
        <?= htmlspecialchars($marca->nombre) ?>
    </option>-->

    <option value="">
        -- Seleccione una marca --
    </option>

    <?php foreach ($marcas as $marca) : ?>
        <option
            value="<?= $marca->id ?>">
            <?= htmlspecialchars($marca->nombre) ?>
        </option>
    <?php endforeach; ?>
</select>
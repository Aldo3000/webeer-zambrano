<?php

/**
 * Formulario reutilizable para crear y editar categorías.
 *
 * Variables disponibles:
 * ----------------------
 * $categoria
 */

?>

<div>
    <div>
        <label for="nombre">
            Nombre de la categoría
        </label>

        <input
            type="text"
            id="nombre"
            name="categoria[nombre]"
            value="<?= htmlspecialchars($categorias->nombre) ?>"
            placeholder="Ej. Cervezas"
            required>
    </div>
    <div>
        <label for="nombre">
            Descripcion de la categoría
        </label>

        <input
            type="text"
            id="descripcion"
            name="categoria[descripcion]"
            value="<?= htmlspecialchars($categorias->descripcion) ?>"
            placeholder="Ej. Galletas son gattelas"
            required>
    </div>
</div>
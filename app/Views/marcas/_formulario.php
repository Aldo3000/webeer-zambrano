<?php

/**
 * Formulario reutilizable para crear y editar marcas.
 *
 * Variables disponibles:
 * ----------------------
 * $marca
 */

?>

<div>

    <label for="nombre">
        Nombre de la marca
    </label>

    <input
        type="text"
        id="nombre"
        name="marca[nombre]"
        value="<?= htmlspecialchars($marca->nombre) ?>"
        placeholder="Ej. Corona"
        required>

</div>
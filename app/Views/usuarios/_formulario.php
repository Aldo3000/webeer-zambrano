<?php

/**
 * Formulario reutilizable para usuarios.
 *
 * Variables disponibles:
 * ----------------------
 * $usuario
 */

?>

<div>
    <div>
        <label for="nombre">
            Nombre
        </label>
        <input
            type="text"
            id="nombre"
            name="usuario[nombre]"
            value="<?= htmlspecialchars($usuario->nombre) ?>"
            required>
    </div>
    <div>
        <label for="apellido">
            Apellido
        </label>
        <input
            type="text"
            id="apellido"
            name="usuario[apellido]"
            value="<?= htmlspecialchars($usuario->apellido) ?>"
            required>
    </div>
    <div>
        <label for="correo">
            Correo electrónico
        </label>
        <input
            type="email"
            id="correo"
            name="usuario[correo]"
            value="<?= htmlspecialchars($usuario->correo) ?>"
            required>
    </div>
    <div>
        <label for="telefono">
            Teléfono
        </label>
        <input
            type="text"
            id="telefono"
            name="usuario[telefono]"
            value="<?= htmlspecialchars($usuario->telefono) ?>"
            required>
    </div>
    <div>
        <label for="password_hash">
            Contraseña
        </label>
        <input
            type="password"
            id="password_hash"
            name="usuario[password_hash]"
            required>
    </div>
</div>
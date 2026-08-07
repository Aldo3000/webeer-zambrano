<?php

/**
 * Vista:
 * Listado de Usuarios
 *
 * Variables disponibles:
 * ----------------------
 * $usuarios
 */

$mensajes = [
    1 => 'Usuario creado correctamente.'
];
?>

<main class="contenedor">
    <!-- Encabezado -->
    <header class="encabezado">
        <h1>
            Administración de Usuarios
        </h1>
        <p>
            Desde este módulo podrás administrar
            los usuarios del sistema.
        </p>
        <div>
            <?php
            $resultado = $_GET['resultado'] ?? '';
            if (isset($mensajes[$resultado])) {
                echo '<p class="alerta exito">'
                    . htmlspecialchars($mensajes[$resultado]) .
                    '</p>';
            }
            ?>
        </div>
    </header>
    <!-- Botón crear -->
    <section class="acciones">
        <a
            href="/admin/usuarios/crear"
            class="boton boton-verde">
            + Nuevo Usuario
        </a>
    </section>
    <!-- Tabla -->
    <section class="tabla-usuarios">
        <table class="tabla">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Correo</th>
                    <th>Teléfono</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($usuarios)) : ?>
                    <?php foreach ($usuarios as $usuario) : ?>
                        <tr>
                            <td>
                                <?= htmlspecialchars($usuario->id) ?>
                            </td>
                            <td>
                                <?= htmlspecialchars($usuario->nombre) ?>
                            </td>
                            <td>
                                <?= htmlspecialchars($usuario->apellido) ?>
                            </td>
                            <td>
                                <?= htmlspecialchars($usuario->correo) ?>
                            </td>
                            <td>
                                <?= htmlspecialchars($usuario->telefono) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="5">
                            No existen usuarios registrados.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </section>
    <!-- Resumen -->
    <footer>
        Total de usuarios:
        <strong>
            <?= count($usuarios) ?>
        </strong>
    </footer>
</main>
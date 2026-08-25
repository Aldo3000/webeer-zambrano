<?php

/**
 * Vista:
 * Panel principal del cliente.
 *
 * Variables disponibles:
 * ----------------------
 * $usuarioId
 * $nombre
 */

?>
<main class="cuenta">
    <div class="contenedor">
        <h1>
            Mi cuenta
        </h1>
        <section>
            <h2>
                Hola,
                <?= htmlspecialchars($nombre) ?>
            </h2>
            <p>
                Bienvenido a tu cuenta de WEBEERZAMBRANO.
            </p>
        </section>
        <section>
            <h2>
                Opciones
            </h2>
            <ul>
                <li>
                    <a href="/cuenta/perfil">
                        Mis datos
                    </a>
                </li>
                <li>
                    <a href="/cuenta/pedidos">
                        Mis pedidos
                    </a>
                </li>

            </ul>
        </section>
        <section>
            <h2>
                Comprar
            </h2>
            <a href="/productos">
                Ver productos
            </a>
        </section>
        <section>
            <h2>
                Sesión
            </h2>
            <a href="/logout">
                Cerrar sesión
            </a>
        </section>
    </div>
</main>
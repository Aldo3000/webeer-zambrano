<?php

/**
 * Vista:
 * Dashboard Administrativo
 */
//session_start();
?>

<main class="contenedor">
    <!-- Encabezado -->
    <header class="encabezado">
        <h1>
            Panel de Administración
        </h1>

        <p>
            Bienvenido al sistema de administración de
            WEBEER ZAMBRANO.
        </p>

        <p>
            Desde este panel podrás administrar los módulos
            principales de la aplicación.
        </p>
    </header>
    <section>
        <a
            href="/logout"
            class="boton boton-rojo">
            Cerrar sesión
        </a>
    </section>


    <!-- Información -->
    <section>
        <h2>
            Estadísticas generales
        </h2>
        <ul>
            <li>
                Productos registrados:
                <strong>
                    <?= $totalProductos ?>
                </strong>
            </li>

            <li>
                Categorías registradas:
                <strong>
                    <?= $totalCategorias ?>
                </strong>
            </li>

            <li>
                Marcas registradas:
                <strong>
                    <?= $totalMarcas ?>
                </strong>
            </li>
        </ul>
    </section>
    <!-- Accesos rápidos -->
    <!-- Accesos rápidos -->
    <section>
        <h2>
            Accesos rápidos
        </h2>
        <!-- Productos -->
        <article>
            <h3>
                Productos
            </h3>
            <p>
                Gestiona todos los productos registrados
                en el sistema.
            </p>
            <a href="/admin/productos">
                Ver Productos
            </a>
            |
            <a href="/admin/productos/crear">
                Nuevo Producto
            </a>
        </article>
        <hr>

        <!-- Categorías -->
        <article>
            <h3>
                Categorías
            </h3>
            <p>
                Administra las categorías disponibles
                para los productos.
            </p>
            <a href="/admin/categorias">
                Ver Categorías
            </a>
            |
            <a href="/admin/categorias/crear">
                Nueva Categoría
            </a>
        </article>
        <hr>

        <!-- Marcas -->
        <article>
            <h3>
                Marcas
            </h3>

            <p>
                Administra las marcas registradas
                en el sistema.
            </p>
            <a href="/admin/marcas">
                Ver Marcas
            </a>
            |
            <a href="/admin/marcas/crear">
                Nueva Marca
            </a>
        </article>
    </section>

</main>
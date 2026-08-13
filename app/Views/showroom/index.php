<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos | WEBEERZAMBRANO</title>
    <link rel="stylesheet" href="/build/css/app.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
</head>

<body>
    <header class="headerProductos">
        <div class="barraNav">
            <a href="index.html" class="logo_barraNav">
                <img src="/build/img/logoensvg.svg" alt="Logo WEBEERZAMBRANO">
            </a>

            <nav class="navofbarraNav">
                <a class="navegacion__boton" href="showroom.html">
                    Productos
                </a>
                <a class="navegacion__boton" href="sobrenosotros.html">
                    Sobre Nosotros
                </a>
                <a class="navegacion__boton" href="contacto.html">
                    Contacto
                </a>
            </nav>
        </div>

        <div class="contenidoHeaderProductos">
            <h1>
                Nuestros Productos
            </h1>

            <p>
                Encuentra cerveza, botanas, refrescos y mucho más.
            </p>
        </div>
    </header>

    <section class="searchBar">
        <div class="contenedor">
            <form class="searchBar__form">
                <i class="bi bi-search searchBar__icon"></i>
                <input type="search" class="searchBar__input" placeholder="Buscar cerveza, botanas, refrescos..." autocomplete="off">
            </form>
        </div>
    </section>
    <!-- Mini carrito -->
    <div class="miniCarrito">
        <!-- Botón / indicador del carrito -->
        <a
            href="/carrito"
            class="miniCarrito__boton">
            🛒
            <?php if ($carrito['cantidadTotal'] > 0) : ?>
                <span class="miniCarrito__cantidad">
                    <?= htmlspecialchars($carrito['cantidadTotal']) ?>
                </span>
            <?php endif; ?>
        </a>
        <!-- Resumen -->
        <div class="miniCarrito__resumen">
            <h2>
                Tu carrito
            </h2>
            <?php if (empty($carrito['productos'])) : ?>
                <p>
                    Tu carrito está vacío.
                </p>
            <?php else : ?>
                <?php foreach ($carrito['productos'] as $producto) : ?>
                    <div class="miniCarrito__producto">
                        <p>
                            <?= htmlspecialchars($producto->nombre) ?>
                        </p>
                        <p>
                            <?= htmlspecialchars($producto->cantidad) ?>
                            ×
                            $<?= number_format(
                                    (float) $producto->precio_actual,
                                    2
                                ) ?>
                        </p>
                    </div>
                <?php endforeach; ?>
                <p class="miniCarrito__total">
                    Total:
                    <strong>
                        $<?= number_format(
                                (float) $carrito['total'],
                                2
                            ) ?>
                    </strong>
                </p>
                <a
                    href="/carrito"
                    class="miniCarrito__ver">
                    Ver carrito
                </a>
            <?php endif; ?>
        </div>
    </div>
    <section class="categoriasProductos">
        <div class="contenedor">
            <div class="tituloCategorias">
                <h2>Categorías</h2>
                <div class="botonesCategorias">
                    <button class="anteriorCategoria">
                        ←
                    </button>
                    <button class="siguienteCategoria">
                        →
                    </button>
                </div>
            </div>

            <div class="contenedorCategorias">
                <!-- Mostrar todos los productos -->
                <button
                    class="categoria activa"
                    data-categoria-id="todos">
                    <span>
                        Todo
                    </span>
                </button>
                <!-- Categorías obtenidas desde la base de datos -->
                <?php if (!empty($categorias)) : ?>
                    <?php foreach ($categorias as $categoria) : ?>
                        <button
                            class="categoria"
                            data-categoria-id="<?= htmlspecialchars($categoria->id) ?>">
                            <span>
                                <?= htmlspecialchars($categoria->nombre) ?>
                            </span>
                        </button>
                    <?php endforeach; ?>
                <?php else : ?>
                    <p>
                        No hay categorías disponibles.
                    </p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <section class="productGrid">
        <div class="contenedor">
            <!--<div class="productGrid__header">
            <h2>Nuestros Productos</h2>
            <p>Encuentra cerveza, botanas, refrescos y mucho más.</p>
            Tal vez aqui modifiquemos este apartado para poner otra cosa
        </div>-->
            <div class="productGrid__container">
                <?php if (!empty($productos)) : ?>
                    <?php foreach ($productos as $producto) : ?>
                        <!-- Producto -->
                        <article class="productCard"
                            data-categoria-id="<?= htmlspecialchars($producto->categoria_id) ?>"
                            data-nombre="<?= htmlspecialchars(strtolower($producto->nombre)) ?>">
                            <div class="productCard__image">
                                <img
                                    src="/imagenes/<?= htmlspecialchars($producto->imagen_principal) ?>"
                                    alt="<?= htmlspecialchars($producto->nombre) ?>">
                            </div>
                            <div class="productCard__content">
                                <h3 class="productCard__title">
                                    <a href="/productos/ver?id=<?= urlencode($producto->id) ?>">
                                        <?= htmlspecialchars($producto->nombre) ?>
                                    </a>
                                </h3>
                                <p class="productCard__description">
                                    <?= htmlspecialchars($producto->descripcion) ?>
                                </p>
                                <div class="productCard__footer">
                                    <span class="productCard__price">
                                        $<?= number_format((float) $producto->precio_actual, 2) ?>
                                    </span>
                                    <form
                                        method="POST"
                                        action="/carrito/agregar">
                                        <input
                                            type="hidden"
                                            name="id"
                                            value="<?= htmlspecialchars($producto->id) ?>">
                                        <button
                                            class="productCard__button"
                                            type="submit">
                                            <i class="bi bi-plus-lg"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                <?php else : ?>
                    <p>
                        No hay productos disponibles.
                    </p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <footer class="footer">
        <div class="contenedor">
            <div class="contenidoFooter">
                <div class="columnaFooter">
                    <img src="build/img/logoensvg.svg" alt="Logo WEBEERZAMBRANO">
                    <p>
                        Tu tienda de conveniencia con entregas rápidas y cerveza siempre helada.
                    </p>
                </div>

                <div class="columnaFooter">
                    <h3>Enlaces</h3>
                    <a href="showroom.html">Productos</a>
                    <a href="sobrenosotros.html">Sobre Nosotros</a>
                    <a href="contacto.html">Contacto</a>
                </div>

                <div class="columnaFooter">
                    <h3>Contacto</h3>
                    <p>📞 (81) 1234-5678</p>
                    <p>📍 Burocratas, Monterrey, Nuevo León</p>
                    <p>🕒 9:00 AM - 11:00 PM</p>
                </div>

                <div class="columnaFooter">
                    <h3>Síguenos</h3>
                    <div class="redesSociales">
                        <a href="#">
                            <i class="bi bi-facebook"></i>
                        </a>
                        <a href="#">
                            <i class="bi bi-instagram"></i>
                        </a>
                        <a href="#">
                            <i class="bi bi-whatsapp"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="copyright">
                <p>
                    © 2026 WEBEERZAMBRANO. Todos los derechos reservados.
                </p>
            </div>

        </div>

    </footer>
    <script src="../../../build/js/app.js"></script>
</body>

</html>
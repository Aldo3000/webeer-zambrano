// ==============================
// SLIDER PRINCIPAL
// ==============================

const slides = document.querySelectorAll(".slide");
const controles = document.querySelectorAll(".control");
if (slides.length > 0 && controles.length > 0) {
    let actual = 0;
    setInterval(() => {
        slides[actual].classList.remove("activo");
        controles[actual].classList.remove("activo");
        actual++;
        if (actual >= slides.length) {
            actual = 0;
        }
        slides[actual].classList.add("activo");
        controles[actual].classList.add("activo");

    }, 5000);
}


// ==============================
// SHOWROOM DESTACADOS
// ==============================

const contenedorProductos = document.querySelector(".contenedorProductos");
const siguiente = document.querySelector(".siguiente");
const anterior = document.querySelector(".anterior");
if (contenedorProductos && siguiente && anterior) {
    siguiente.addEventListener("click", () => {
        contenedorProductos.scrollBy({
            left: 320,
            behavior: "smooth"
        });
    });

    anterior.addEventListener("click", () => {
        contenedorProductos.scrollBy({
            left: -320,
            behavior: "smooth"
        });
    });
}


// ==============================
// CATEGORÍAS PRODUCTOS
// ==============================

const contenedorCategorias = document.querySelector(".contenedorCategorias");
const siguienteCategoria = document.querySelector(".siguienteCategoria");
const anteriorCategoria = document.querySelector(".anteriorCategoria");
const categorias = document.querySelectorAll(".categoria");

if (
    contenedorCategorias &&
    siguienteCategoria &&
    anteriorCategoria
) {

    siguienteCategoria.addEventListener("click", () => {
        contenedorCategorias.scrollBy({
            left: 220,
            behavior: "smooth"
        });
    });

    anteriorCategoria.addEventListener("click", () => {
        contenedorCategorias.scrollBy({
            left: -220,
            behavior: "smooth"
        });
    });
}

if (categorias.length > 0) {
    categorias.forEach(categoria => {
        categoria.addEventListener("click", () => {
            categorias.forEach(c => c.classList.remove("activa"));
            categoria.classList.add("activa");
        });
    });

}

const cards = document.querySelectorAll(".productCard");
cards.forEach((card, index) => {
    card.style.opacity = "0";
    card.style.transform = "translateY(30px)";
    setTimeout(() => {
        card.style.transition = ".5s ease";
        card.style.opacity = "1";
        card.style.transform = "translateY(0)";

    }, index * 120);

});


const preguntas = document.querySelectorAll(".pregunta");

if (preguntas.length > 0) {
    preguntas.forEach(pregunta => {
        const boton = pregunta.querySelector(".tituloPregunta");
        boton.addEventListener("click", () => {
            pregunta.classList.toggle("activa");
        });
    });
}


// ==========================================================
// FILTRO DE PRODUCTOS
// ==========================================================

// Obtiene el campo de búsqueda.
const buscador = document.querySelector('.searchBar__input');

// Obtiene todos los botones de categorías.
const botonesCategorias =
    document.querySelectorAll('.categoria');

// Obtiene todas las tarjetas de productos.
const tarjetasProductos =
    document.querySelectorAll('.productCard');

// Categoría seleccionada actualmente.
// Por defecto se muestran todos los productos.
let categoriaSeleccionada = 'todos';


// ==========================================================
// FUNCIÓN PRINCIPAL DE FILTRADO
// ==========================================================

function filtrarProductos() {

    // Obtiene el texto escrito en el buscador.
    const textoBusqueda =
        buscador
            ? buscador.value.toLowerCase().trim()
            : '';

    // Recorre todas las tarjetas de productos.
    tarjetasProductos.forEach((producto) => {

        // Obtiene la categoría del producto.
        const categoriaProducto =
            producto.dataset.categoriaId;

        // Obtiene el nombre del producto.
        const nombreProducto =
            producto.dataset.nombre || '';

        // Verifica si pertenece a la categoría seleccionada.
        const coincideCategoria =
            categoriaSeleccionada === 'todos' ||
            categoriaProducto === categoriaSeleccionada;

        // Verifica si el nombre coincide con la búsqueda.
        const coincideBusqueda =
            nombreProducto.includes(textoBusqueda);

        // El producto solamente se muestra
        // si cumple ambas condiciones.
        if (coincideCategoria && coincideBusqueda) {

            producto.style.display = '';

        } else {

            producto.style.display = 'none';

        }

    });
}


// ==========================================================
// EVENTO DEL BUSCADOR
// ==========================================================

if (buscador) {

    // Detecta cada cambio realizado en el campo.
    buscador.addEventListener('input', () => {

        // Ejecuta nuevamente el filtro.
        filtrarProductos();

    });

}


// ==========================================================
// EVENTOS DE LAS CATEGORÍAS
// ==========================================================

botonesCategorias.forEach((boton) => {

    boton.addEventListener('click', () => {

        // Obtiene el ID de la categoría seleccionada.
        categoriaSeleccionada =
            boton.dataset.categoriaId;

        // Quita la clase "activa"
        // de todas las categorías.
        botonesCategorias.forEach((boton) => {
            boton.classList.remove('activa');
        });

        // Agrega la clase "activa"
        // a la categoría seleccionada.
        boton.classList.add('activa');

        // Ejecuta nuevamente el filtro.
        filtrarProductos();

    });

});
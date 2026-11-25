const buscador = document.getElementById("buscador");

const tarjetas = document.querySelectorAll(".producto");

const filtros = document.querySelectorAll(".filter");

const sinResultados = document.getElementById("sinResultados");

let categoriaSeleccionada = "todos";

function filtrar() {

    const texto = buscador.value.toLowerCase().trim();

    let visibles = 0;

    tarjetas.forEach(tarjeta => {

        const nombre = tarjeta.dataset.nombre.toLowerCase();

        const categoria = tarjeta.dataset.categoria.toLowerCase();

        const coincideTexto =
            nombre.includes(texto) ||
            categoria.includes(texto);

        const coincideCategoria =
            categoriaSeleccionada === "todos" ||
            categoria === categoriaSeleccionada.toLowerCase();

        if (coincideTexto && coincideCategoria) {

            tarjeta.style.display = "flex";

            visibles++;

        } else {

            tarjeta.style.display = "none";

        }

    });

    if (sinResultados) {

        sinResultados.style.display =
            visibles === 0 && tarjetas.length > 0 ? "block" : "none";

    }

}

buscador.addEventListener("input", filtrar);

filtros.forEach(boton => {

    boton.addEventListener("click", () => {

        filtros.forEach(f => f.classList.remove("active"));

        boton.classList.add("active");

        categoriaSeleccionada = boton.dataset.categoria;

        filtrar();

    });

});

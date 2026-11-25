const formulario = document.getElementById("formulario");
const procesoId = document.getElementById("proceso_id").value;
const estado = document.getElementById("estado_guardado");
const punto = document.getElementById("save_dot");
const botonFinalizar = document.getElementById("btn_finish");

const ids = [
    "curp", "nombres", "apellidos", "fecha_nacimiento",
    "sexo", "estado_civil", "telefono_celular",
    "correo_electronico", "codigo_postal", "estado",
    "municipio", "colonia", "calle", "paso"
];

let temporizador;
let cadenaGuardado = Promise.resolve();
let guardadoPendiente = false;
let finalizando = false;

function obtenerDatos() {
    const datos = new FormData();

    datos.append("id", procesoId);
    datos.append("accion", "guardar");

    ids.forEach(id => {
        datos.append(id, document.getElementById(id).value);
    });

    return datos;
}

async function enviarDatos(accion = "guardar") {
    const datos = obtenerDatos();
    datos.set("accion", accion);

    estado.textContent = "Guardando cambios...";
    punto.style.background = "#d99a37";

    const respuesta = await fetch(
        "continuar.php?id=" + encodeURIComponent(procesoId),
        {
            method: "POST",
            body: datos,
            credentials: "same-origin"
        }
    );

    const resultado = await respuesta.json();

    if (!respuesta.ok || !resultado.ok) {
        throw new Error(resultado.mensaje || "No se pudieron guardar los datos.");
    }

    guardadoPendiente = false;
    estado.textContent = accion === "completar"
        ? "Expediente enviado correctamente."
        : "¡Cambios guardados automáticamente!";

    punto.style.background = "#239b75";

    return resultado;
}

function programarGuardado() {
    if (finalizando) return;

    clearTimeout(temporizador);
    guardadoPendiente = true;

    estado.textContent = "Tienes cambios sin guardar...";
    punto.style.background = "#d99a37";

    temporizador = setTimeout(() => {
        // Mantener los guardados en orden para evitar que
        // una petición anterior sobrescriba datos más recientes.
        cadenaGuardado = cadenaGuardado
            .catch(() => {})
            .then(() => enviarDatos("guardar"))
            .catch(error => {
                estado.textContent = error.message;
                punto.style.background = "#d65d5d";
                guardadoPendiente = true;
            });
    }, 800);
}

ids.forEach(id => {
    const campo = document.getElementById(id);
    campo.addEventListener("input", programarGuardado);
    campo.addEventListener("change", programarGuardado);
});

formulario.addEventListener("submit", async function(event) {
    event.preventDefault();

    if (finalizando) return;

    clearTimeout(temporizador);

    if (!formulario.reportValidity()) return;

    finalizando = true;
    botonFinalizar.disabled = true;
    botonFinalizar.textContent = "Enviando expediente...";

    try {
        // Esperar cualquier guardado anterior.
        await cadenaGuardado;

        // Enviar los valores actuales y finalizar en una sola petición.
        await enviarDatos("completar");

        window.location.href = "pendientes.php";

    } catch (error) {
        finalizando = false;
        botonFinalizar.disabled = false;
        botonFinalizar.textContent = "✓ Enviar expediente a revisión";

        estado.textContent = error.message;
        punto.style.background = "#d65d5d";

        alert(error.message);
    }
});

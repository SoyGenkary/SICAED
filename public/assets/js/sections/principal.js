import { cargarSeccion } from "./../modules/sectionLoader.js";

export function inicializarPrincipal() {
  const resumenes = document.querySelector(".principal__resumen");
  const tablaVehiculos = document.querySelector(".vehiculos-recientes");

  if (resumenes && tablaVehiculos) {
    const detallesCantidad = resumenes.querySelectorAll(".resumen");
    detallesCantidad.forEach((linkDetalles) => {
      linkDetalles.addEventListener("click", verDetalles);
    });

    const detallesTabla = tablaVehiculos.querySelector("tbody a");
    detallesTabla.addEventListener("click", (e) => {
      cargarSeccion("browser");
    });
  }
}

function verDetalles(e) {
  const elementoClickeado = e.currentTarget;
  const clases = elementoClickeado.classList;

  if (clases.length >= 2) {
    const segundaClase = clases[1];

    if (segundaClase === "resumen-vehiculos") {
      cargarSeccion("status");
    } else if (segundaClase === "resumen-conductores") {
      cargarSeccion("browser");
    } else if (segundaClase === "resumen-mantenimientos") {
      cargarSeccion("browser");
    }
  }
}

import { loadSection } from "./../modules/sectionLoader.js";

export function initializePrincipal() {
  const summaries = document.querySelector(".principal__resumen");
  const tableVehicles = document.querySelector(".vehiculos-recientes");

  if (summaries && tableVehicles) {
    const detailsCount = summaries.querySelectorAll(".resumen");
    detailsCount.forEach((linkDetails) => {
      linkDetails.addEventListener("click", viewDetails);
    });

    const detailsTable = tableVehicles.querySelector("tbody a");
    detailsTable.addEventListener("click", (e) => {
      loadSection("browser");
    });
  }
}

function viewDetails(e) {
  const clickElement = e.currentTarget;
  const classes = clickElement.classList;

  if (classes.length >= 2) {
    const secondClass = classes[1];

    if (secondClass === "resumen-vehiculos") {
      loadSection("status");
    } else if (secondClass === "resumen-conductores") {
      loadSection("browser");
    } else if (secondClass === "resumen-mantenimientos") {
      loadSection("browser");
    }
  }
}

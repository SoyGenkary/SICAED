import { apiRequest } from "./../API/api.js";

/**
 * Inicializamos la seccion de Estado Detallado
 */
export async function initializeStatus() {
  await showData();
}

/**
 * Consultamos los datos para el posterior muestreo al usuario
 * @returns data - Los datos obtenidos del resumen de la base de datos
 */
async function consultData(type) {
  const formData = new FormData();

  // Separamos la logica en secciones
  switch (type) {
    case "estadisticas":
      formData.append("section", "vehiculos");
      formData.append("action", "getCount");
      const response = await apiRequest(formData);

      if (response["success"]) {
        return response["data"];
      }
      break;
  }
}

/**
 * Muetreo de los datos de cada seccion correspondiente
 * @param {JSON} data - Los datos a mostrar
 */
async function showData() {
  // Estadisticas
  const dataEstadisticas = await consultData("estadisticas");
  configureStatistics(dataEstadisticas);

  // Resumenes
  configureSummaries(dataEstadisticas);
}

/**
 * Configura las estadisticas con los datos correspondientes
 * @param {JSON} data - JSON con los datos de las estadisticas
 */
function configureStatistics(data) {
  const ctx = document.querySelector(".myChart");
  if (ctx) {
    const myChart = new Chart(ctx, {
      type: "doughnut",
      data: {
        labels: ["Sin Servicio", "Con Servicio"],
        datasets: [
          {
            data: [
              data[0]["activos"] ? data[0]["activos"] : 0,
              data[0]["matenimientos"] ? data[0]["matenimientos"] : 0,
            ],
            backgroundColor: ["#4CAF50", "#FFC107"],
            borderColor: ["#4CAF50", "#FFC107"],
            borderWidth: 1,
          },
        ],
      },
      options: {
        responsive: true,
      },
    });
  }
}

/**
 * Configura los datos resumidos de los vehiculos
 */
function configureSummaries(data) {
  const summaryItems = document.querySelectorAll(".summary-item");

  if (summaryItems) {
    summaryItems.forEach((summaryItem) => {
      if (summaryItem.classList.contains("summary-item--active")) {
        // SIN SERVICIO
        summaryItem.querySelector(".summary-item__value").textContent = data[0][
          "activos"
        ]
          ? data[0]["activos"]
          : 0;
      } else if (summaryItem.classList.contains("summary-item--maintenance")) {
        // CON SERVICIO
        summaryItem.querySelector(".summary-item__value").textContent = data[0][
          "matenimientos"
        ]
          ? data[0]["matenimientos"]
          : 0;
      } else {
        // TOTALES
        summaryItem.querySelector(".summary-item__value").textContent = data[0][
          "vehiculos"
        ]
          ? data[0]["vehiculos"]
          : 0;
      }
    });
  }
}

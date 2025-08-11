import { createInput, createSelect } from "./../utils/DOMElements.js";
import {
  formatInputLicensePlate,
  formatInputDNI,
  formatInputPhoneNumber,
} from "./../utils/inputFormatters.js";
import { searchData, apiRequest } from "./../API/api.js";
import { ntfConfirm, ntfProcessSuccessful } from "../utils/utils.js";

let initialBrowserFormHTML = ""; // Para guardar el HTML inicial del formulario del buscador
let conditionalCheckbox = false; // Para manejar el checkbox de condiciones múltiples en el buscador
const placeholderBtnBrowser = ["Buscar", "Agregar Condición"];

async function confirmarBorrado() {
  const response = await ntfConfirm(
    "¿Seguro que deseas borrar estos registros?",
    "Esta acción es irreversible!"
  );
  return response;
}

/**
 * Inicializa el menú de acciones de selección para las tablas de resultados.
 */
export function initializeSelectionMenu() {
  const selectionMenu = document.getElementById("selectionMenu");
  const selectionCount = document.getElementById("selectionCount");
  const isolateBtn = document.getElementById("isolateSelectionBtn");
  const deleteBtn = document.getElementById("deleteSelectionBtn");
  const resultsContainer = document.querySelector(".results__container");

  if (!selectionMenu || !resultsContainer) return;

  const updateMenuVisibility = () => {
    const checkedBoxes = resultsContainer.querySelectorAll(
      ".selectedResult:checked"
    );
    const count = checkedBoxes.length;

    if (count > 0) {
      selectionCount.textContent = `${count} seleccionado(s)`;
      selectionMenu.classList.add("show");
    } else {
      selectionMenu.classList.remove("show");
      // Si el menú se oculta, asegurarse de que todas las filas vuelvan a ser visibles.
      if (isolateBtn.dataset.action === "showAll") {
        isolateBtn.textContent = "Aislar Selección";
        isolateBtn.dataset.action = "isolate";
        resultsContainer
          .querySelectorAll("tbody tr.hidden-row")
          .forEach((row) => {
            row.classList.remove("hidden-row");
          });
      }
    }
  };

  const handleSelectAll = (selectAllCheckbox) => {
    const tableResults = selectAllCheckbox.closest(".results_table");
    if (tableResults) {
      const isChecked = selectAllCheckbox.checked;
      tableResults
        .querySelectorAll(".selectedResult")
        .forEach((resultCheckbox) => {
          resultCheckbox.checked = isChecked;
        });
    }
  };

  // Único manejador de eventos para toda la lógica de selección
  resultsContainer.addEventListener("change", (e) => {
    const target = e.target;
    if (target.matches(".selectedResult")) {
      updateMenuVisibility();
    } else if (target.matches(".selectedAllResults")) {
      // ! BORRAR TODO LO RELACIONADO AL SELECTED ALL
      handleSelectAll(target); // Primero, actualiza todos los checkboxes
      updateMenuVisibility(); // Luego, actualiza la visibilidad del menú
    }
  });

  isolateBtn.addEventListener("click", () => {
    const allRows = resultsContainer.querySelectorAll("tbody tr");
    const action = isolateBtn.dataset.action;

    allRows.forEach((row) => {
      const checkbox = row.querySelector(".selectedResult");
      if (!checkbox) return;

      if (action === "isolate") {
        if (!checkbox.checked) {
          row.classList.add("hidden-row");
        }
      } else {
        row.classList.remove("hidden-row");
      }
    });

    if (action === "isolate") {
      isolateBtn.textContent = "Mostrar Todo";
      isolateBtn.dataset.action = "showAll";
    } else {
      isolateBtn.textContent = "Aislar Selección";
      isolateBtn.dataset.action = "isolate";
    }
  });

  deleteBtn.addEventListener("click", async () => {
    const allRows = resultsContainer.querySelectorAll("tbody tr");
    const selectedResults = [{ vehiculos: [] }, { personal: [] }];
    let type = "";

    allRows.forEach((row) => {
      const checkbox = row.querySelector(".selectedResult");
      if (!checkbox) return;

      if (checkbox.checked) {
        type = checkbox.closest(".results__content").parentElement;

        if (type && type.classList.contains("vehiculos")) {
          selectedResults[0]["vehiculos"].push(checkbox);
        } else if (type && type.classList.contains("personal")) {
          selectedResults[1]["personal"].push(checkbox);
        }
      }
    });

    const response = await confirmarBorrado();
    if (response) {
      selectedResults.forEach((result) => {
        const key = Object.keys(result)[0];
        const values = Object.values(result)[0];
        if (key === "vehiculos" && values.length > 0) {
          values.forEach((row) => {
            const formData = new FormData();
            formData.append("section", "vehiculos");
            formData.append("action", "deleteByID");

            const id = row.closest("tr")?.dataset.id;

            if (id) {
              formData.append("id_vehiculo", id);
              apiRequest(formData);
            }
          });
        } else if (key === "personal" && values.length > 0) {
          values.forEach((row) => {
            const formData = new FormData();
            formData.append("section", "personal");
            formData.append("action", "deleteByID");

            const id = row.closest("tr")?.dataset.id;

            if (id) {
              formData.append("id_personal", id);
              apiRequest(formData);
            }
          });
        }
      });

      ntfProcessSuccessful(
        "Proceso Exitoso!",
        "Se han eliminado los registros de forma satisfactoria!"
      );

      selectionMenu.classList.remove("show");

      selectedResults.forEach((result) => {
        Object.values(result).forEach((group) => {
          group.forEach((checkbox) => {
            const row = checkbox.closest("tr");
            if (row) row.remove();
          });
        });
      });
    }
  });
}

/**
 * Reinicia el estilo y contenido del formulario del buscador a su estado original.
 * @param {HTMLElement} browserContainer - El contenedor del grupo de inputs del buscador (.browser__group).
 */
export function resetarFormularioBrowser(browserContainer) {
  if (browserContainer && initialBrowserFormHTML) {
    browserContainer.innerHTML = initialBrowserFormHTML;
    browserContainer.style.gap = "";
  } else if (browserContainer) {
    console.warn(
      "No se pudo resetear el formulario del buscador: no hay HTML inicial guardado o browserContainer no es válido."
    );
  }
}

/**
 * Aplica estilos a un input del buscador.
 * @param {HTMLInputElement|HTMLSelectElement} inputElement - El elemento al que se le aplicarán los estilos.
 * @param {Object} [estilos={}] - Un objeto con los estilos a aplicar.
 */
export function aplicarEstilosInputBrowser(inputElement, estilos = {}) {
  if (!inputElement) return;
  const defaultStyles = {
    borderRadius: "0.5rem",
    paddingLeft: "1rem",
    width: "100%",
  };

  const finalStyles = { ...defaultStyles, ...estilos };
  Object.entries(finalStyles).forEach(([prop, val]) => {
    inputElement.style[prop] = val;
  });
}

/**
 * Prepara el contenedor del buscador para campos personalizados o el estado global.
 * @param {HTMLElement} browserGroup - El contenedor .browser__group.
 * @param {string} placeholderOriginalInput - Placeholder para el input principal.
 * @param {string} nameOriginalInput - Name para el input principal.
 * @param {object} [stylesInputOriginal={}] - Estilos para el input principal.
 * @param {object} [attributesInputOriginal={}] - Atributos para el input principal.
 * @returns {HTMLInputElement|null} El input principal creado/modificado o null.
 */
export function prepararContenedorBuscador(
  browserGroup,
  placeholderOriginalInput,
  nameOriginalInput,
  stylesInputOriginal = {},
  attributesInputOriginal = {}
) {
  if (!browserGroup) return null;

  browserGroup.innerHTML = "";
  browserGroup.style.gap = "20px";

  let inputPrincipal = null;

  if (nameOriginalInput && nameOriginalInput !== "dummyInputMantenimiento") {
    inputPrincipal = createInput({
      type: attributesInputOriginal.type || "text",
      name: nameOriginalInput,
      classes: ["search__input"],
      container: browserGroup,
      position: "beforeend",
      placeholder: placeholderOriginalInput,
      attributes: attributesInputOriginal,
    });
    if (inputPrincipal) {
      aplicarEstilosInputBrowser(inputPrincipal, { ...stylesInputOriginal });
    }
  }

  const btnSearch = createInput({
    type: "submit",
    name: "browserSearch",
    classes: [],
    container: browserGroup,
    position: "beforeend",
    attributes: { id: "btnSearch", value: placeholderBtnBrowser[0] },
  });
  if (btnSearch) {
    aplicarEstilosInputBrowser(btnSearch, {
      width: "auto",
      paddingLeft: "1rem",
      paddingRight: "1rem",
    });
    btnSearch.classList.add("administrator__form-submit");
  }

  return inputPrincipal;
}

/**
 * Cambia la estructura del formulario del buscador según el tipo de dato seleccionado.
 * @param {string} tipoDeDato - El valor seleccionado.
 * @param {HTMLElement} browserGroup - El elemento .browser__group.
 */
export function cambiarFormularioBrowser(tipoDeDato, browserGroup) {
  if (!browserGroup) return;

  if (!initialBrowserFormHTML && browserGroup.innerHTML.trim() !== "") {
    const formContainer = browserGroup.closest(".browser__container");
    const tipoDeDatoSelect = formContainer?.querySelector("#tipoDeDato");
    if (
      tipoDeDatoSelect?.value === "global" ||
      tipoDeDatoSelect?.value === "default" ||
      !tipoDeDatoSelect
    ) {
      initialBrowserFormHTML = browserGroup.innerHTML;
      console.log(
        "HTML inicial del buscador guardado desde cambiarFormularioBrowser."
      );
    }
  }

  switch (tipoDeDato.toLowerCase()) {
    case "default":
    case "global":
      resetarFormularioBrowser(browserGroup);
      if (
        conditionalCheckbox &&
        browserGroup.querySelector("#addConditional") === null
      ) {
        prepararContenedorBuscador(
          browserGroup,
          "Escribe aquí para buscar de forma global...",
          "search__input"
        );
      }
      break;
    case "personal:nombre": {
      prepararContenedorBuscador(browserGroup, "Segundo Nombre", "secondname");
      const nameInput = createInput({
        type: "text",
        name: "primaryname",
        container: browserGroup,
        position: "afterbegin",
        placeholder: "Primer Nombre",
      });
      if (nameInput) aplicarEstilosInputBrowser(nameInput);
      break;
    }
    case "personal:apellido": {
      prepararContenedorBuscador(browserGroup, "Segundo Apellido", "lastname");
      const lastNameInput = createInput({
        type: "text",
        name: "primarylastname",
        container: browserGroup,
        position: "afterbegin",
        placeholder: "Primer Apellido",
      });
      if (lastNameInput) aplicarEstilosInputBrowser(lastNameInput);
      break;
    }
    case "personal:documento": {
      const dniValueInput = prepararContenedorBuscador(
        browserGroup,
        "Documento de Identidad",
        "DNI",
        {},
        {
          pattern: "\\d{1,2}\\.\\d{3}\\.\\d{3}",
          title: "Formato: XX.XXX.XXX o X.XXX.XXX",
        }
      );

      if (dniValueInput) {
        dniValueInput.addEventListener("input", (e) => {
          e.target.value = formatInputDNI(e.target.value);
        });
      }

      const dniTypeSelect = createSelect({
        name: "tipoDNI",
        id: "tipoDNI",
        container: browserGroup,
        position: "afterbegin",
        options: { "V-": "V-", "E-": "E-" },
      });

      if (dniTypeSelect)
        aplicarEstilosInputBrowser(dniTypeSelect, {
          width: "min-content",
          textAlign: "center",
        });
      break;
    }
    case "personal:contacto": {
      prepararContenedorBuscador(
        browserGroup,
        "Email o Número de Teléfono",
        "contactValue"
      );
      const contactTypeSelect = createSelect({
        name: "tipoContacto",
        id: "tipoContacto",
        container: browserGroup,
        position: "afterbegin",
        options: { email: "Correo", number: "Teléfono" },
      });
      if (contactTypeSelect) {
        aplicarEstilosInputBrowser(contactTypeSelect, {
          width: "min-content",
          textAlign: "center",
        });
      }
      break;
    }
    case "personal:estado":
      prepararContenedorBuscador(
        browserGroup,
        "Estado (Ej: Distrito Capital)",
        "estadoPersonal"
      );
      break;
    case "personal:municipio":
      prepararContenedorBuscador(
        browserGroup,
        "Municipio (Ej: Libertador)",
        "municipioPersonal"
      );
      break;
    case "personal:fecha-agregado": {
      const dateInput = prepararContenedorBuscador(
        browserGroup,
        "",
        "dateAdd",
        {},
        { type: "date" }
      );
      if (dateInput) dateInput.placeholder = "Ingresa la Fecha Específica";

      const timeInput = createInput({
        type: "time",
        name: "timeAdd",
        container: browserGroup,
        referencia: dateInput,
      });
      if (timeInput) aplicarEstilosInputBrowser(timeInput);
      break;
    }
    case "vehiculo:marca":
      prepararContenedorBuscador(
        browserGroup,
        "Marca del Vehículo",
        "marcaVehiculo"
      );
      break;
    case "vehiculo:modelo":
      prepararContenedorBuscador(
        browserGroup,
        "Modelo del Vehículo",
        "modeloVehiculo"
      );
      break;
    case "vehiculo:matricula":
      const matriculaInput = prepararContenedorBuscador(
        browserGroup,
        "Matrícula (Ej: ABC-123)",
        "matriculaVehiculo",
        {},
        {
          maxlength: "8",
        }
      );

      if (matriculaInput) {
        matriculaInput.addEventListener("input", (e) => {
          e.target.value = formatInputLicensePlate(e.target.value);
        });
      }
      break;
    case "vehiculo:vin":
      prepararContenedorBuscador(
        browserGroup,
        "Vin del Vehículo",
        "vinVehiculo"
      );
      break;
    case "vehiculo:sede":
      prepararContenedorBuscador(
        browserGroup,
        "Sede del Vehículo",
        "sedeVehiculo"
      );
      break;
    case "vehiculo:estado":
      prepararContenedorBuscador(
        browserGroup,
        "Estado (Ej: Distrito Capital)",
        "estadoVehiculo"
      );
      break;
    case "vehiculo:municipio":
      prepararContenedorBuscador(
        browserGroup,
        "Municipio (Ej: Libertador)",
        "municipioVehiculo"
      );
      break;
    case "vehiculo:kilometraje":
      prepararContenedorBuscador(
        browserGroup,
        "Kilometraje del Vehículo",
        "kilometrajeVehiculo"
      );
      break;
    case "vehiculo:mantenimiento": {
      // 1. Prepara el contenedor con los botones de búsqueda ("Buscar", "Agregar Condición").
      // Esta función primero limpia el contenedor. Se usa un input oculto como marcador de posición.
      prepararContenedorBuscador(
        browserGroup,
        "", // Sin placeholder
        "dummyInputMantenimiento", // Nombre del input oculto
        { display: "none" },
        { type: "hidden" }
      );

      // 2. Ahora, crea y añade el <select> específico para este filtro al inicio del contenedor.
      const selectMantenimiento = createSelect({
        name: "estadoMantenimiento",
        id: "estadoMantenimiento",
        classes: ["search__input"],
        container: browserGroup,
        position: "afterbegin", // Se inserta al principio.
        options: { si: "Sí", no: "No" },
        addDefaultPlaceholder: true,
        defaultPlaceholderText: "¿Se le realizo servicio?",
      });

      // 3. Aplica el estilo base al <select> sin forzar un espaciado izquierdo incorrecto.
      if (selectMantenimiento) {
        aplicarEstilosInputBrowser(selectMantenimiento);
      }
      break;
    }
    case "vehiculo:fecha-agregado": {
      const dateInputVehiculo = prepararContenedorBuscador(
        browserGroup,
        "",
        "dateAddVehiculo",
        {},
        { type: "date" }
      );
      if (dateInputVehiculo)
        dateInputVehiculo.placeholder = "Fecha de Agregado del Vehículo";

      const timeInputVehiculo = createInput({
        type: "time",
        name: "timeAddVehiculo",
        container: browserGroup,
        referencia: dateInputVehiculo,
      });
      if (timeInputVehiculo) aplicarEstilosInputBrowser(timeInputVehiculo);
      break;
    }
    default:
      console.warn(
        `Tipo de dato no reconocido para el buscador: ${tipoDeDato}`
      );
      resetarFormularioBrowser(browserGroup);
  }
}

function formatearTelefonoInput(e) {
  e.target.value = formatInputPhoneNumber(e.target.value);
}

/**
 * Maneja el cambio en el tipo de contacto.
 * @param {Event} e - El evento de cambio.
 */
export function manejarCambioTipoContacto(e) {
  const browserGroup = e.target.closest(".browser__group");
  const contactValueInput = browserGroup?.querySelector(
    'input[name="contactValue"]'
  );
  if (contactValueInput) {
    if (e.target.value === "number") {
      contactValueInput.value = "";
      contactValueInput.type = "tel";
      contactValueInput.placeholder = "Ej: 0412-1234567";
      contactValueInput.pattern = "0\\d{3}-\\d{7}";
      contactValueInput.title = "Formato: 0XXX-XXXXXXX";
      contactValueInput.addEventListener("input", formatearTelefonoInput);
    } else {
      contactValueInput.value = "";
      contactValueInput.type = "email";
      contactValueInput.placeholder = "Ej: correo@ejemplo.com";
      contactValueInput.removeAttribute("pattern");
      contactValueInput.removeAttribute("title");
      contactValueInput.removeEventListener("input", formatearTelefonoInput);
    }
  }
}

/**
 * Maneja la selección de todos los resultados.
 * @param {Event} e - El evento de cambio.
 */
export function manejarSeleccionTodosResultados(e) {
  const tableResults = e.target.closest(".results_table");
  if (tableResults) {
    const isChecked = e.target.checked;
    tableResults
      .querySelectorAll(".selectedResult")
      .forEach((resultCheckbox) => {
        resultCheckbox.checked = isChecked;
      });
  }
}

/**
 * Maneja el envío del formulario de búsqueda de forma asíncrona (AJAX).
 * @param {Event} e - El evento de envío del formulario.
 */
export async function handleBrowserSearch(e) {
  e.preventDefault(); // Evita la recarga de la página
  const form = e.target;
  const formData = new FormData(form);

  // Añade los parámetros necesarios para que el backend identifique la acción
  formData.append("section", "browser");
  formData.append("action", "search");

  try {
    // Llama a la API y espera los resultados
    const results = await searchData(formData);
    if (results.success) {
      // Actualiza las tablas de vehículos y personal con los datos obtenidos
      updateResultsTable("vehiculos", results.vehiculos);
      updateResultsTable("personal", results.personal);
    } else {
      alert("La búsqueda falló. Por favor, intente de nuevo.");
    }
  } catch (error) {
    console.error("Error en el proceso de búsqueda:", error);
  }
}

/**
 * Limpia y actualiza una tabla de resultados con los nuevos datos.
 * @param {string} entityType - El tipo de entidad ('vehiculos' o 'personal').
 * @param {Array} data - El array de objetos con los datos para las filas.
 */
function updateResultsTable(entityType, data) {
  const tableContainer = document.querySelector(
    `.${entityType} .results_table`
  );
  if (!tableContainer) return;

  const tbody = tableContainer.querySelector("tbody");
  tbody.innerHTML = ""; // Limpia las filas existentes

  if (!data || data.length === 0) {
    const colCount = tableContainer.querySelectorAll("thead th").length;
    tbody.innerHTML = `<tr><td colspan="${colCount}" style="text-align: center; padding: 20px;">No se encontraron resultados para esta búsqueda.</td></tr>`;
    return;
  }

  data.forEach((item) => {
    let rowHtml = "";
    if (entityType === "vehiculos") {
      rowHtml = `
                <tr data-id="${item.id_vehiculo}">
                    <td class="select"><input type="checkbox" name="selectedResult" class="selectedResult"/></td>
                    <td class="info">
                        <div class="image">
                            <img src="${
                              item.ruta_img || "./assets/img/icons/vehicle.png"
                            }" alt="${item.modelo}" loading="lazy"/>
                        </div>
                        <div class="basic__info">
                            <span class="matricula">${item.matricula}</span>
                            <span class="serial-vin">${item.serial_vin}</span>
                        </div>
                    </td>
                    <td class="model"><span class="model-date">${
                      item.modelo
                    }</span></td>
                    <td class="brand"><span class="brand-date">${
                      item.marca
                    }</span></td>
                    <td class="state"><span class="state-date">${
                      item.estado || "N/A"
                    }</span></td>
                    <td class="municipality"><span class="municipality-date">${
                      item.municipio || "N/A"
                    }</span></td>
                    <td class="sede"><span class="sede-date">${
                      item.sede
                    }</span></td>
                    <td class="mileage"><span class="mileage-date">${new Intl.NumberFormat(
                      "de-DE"
                    ).format(item.kilometraje)} km</span></td>
                    <td class="date"><span class="time-date">${
                      item.fecha_agregado
                    }</span></td>
                    <td class="assignment">
                        <span class="assignment-date ${
                          item.asignado
                            ? "assignment--assignment"
                            : "assignment--unassigned"
                        }">
                            ${item.asignado ? "Asignado" : "No Asignado"}
                        </span>
                    </td>
                    <td class="maintenance"><span class="maintenance-date">${
                      item.en_mantenimiento ? "Sí" : "No"
                    }</span></td>
                </tr>`;
    } else if (entityType === "personal") {
      rowHtml = `
                <tr data-id="${item.id_personal}">
                    <td class="select"><input type="checkbox" name="selectedResult" class="selectedResult"/></td>
                    <td class="names">
                      <span class="names_date">${item.nombres}</span>
                    </td>
                    <td class="lastnames">
                      <span class="lastname-date">${item.apellidos}</span>
                    </td>
                    <td class="dni"><span class="dni-date">${
                      item.documento_identidad
                    }</span></td>
                    <td class="state"><span class="state-date">${
                      item.estado || "N/A"
                    }</span></td>
                    <td class="municipality"><span class="municipality-date">${
                      item.municipio || "N/A"
                    }</span></td>
                    <td class="phone"><span class="phone-date">${
                      item.telefono || "N/A"
                    }</span></td>
                    <td class="email"><span class="email-date">${
                      item.email || "N/A"
                    }</span></td>
                    <td class="assignment"><span class="assignment-date">${
                      item.cargo
                    }</span></td>
                </tr>`;
    }
    tbody.insertAdjacentHTML("beforeend", rowHtml);
  });
}

/**
 * Funcion para ocultar el buscador
 * @param {HTMLElement} btnShowContainer - Boton de ocultar
 */
export function ocultarBuscador(btnShowContainer) {
  const headerBrowser = btnShowContainer.closest(".header__browser");
  const formBrowserGlobal = headerBrowser?.parentElement?.querySelector(
    ".browser__container"
  );
  if (formBrowserGlobal) {
    const isClosed = formBrowserGlobal.classList.toggle("close");
    if (isClosed) {
      formBrowserGlobal.addEventListener(
        "transitionend",
        function onTransitionEnd() {
          formBrowserGlobal.style.display = "none";
          formBrowserGlobal.removeEventListener(
            "transitionend",
            onTransitionEnd
          );
        },
        { once: true }
      );
    } else {
      formBrowserGlobal.style.display = "flex";
    }
  }
}

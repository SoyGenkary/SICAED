import {
  crearSelect,
  SELECTS_MUNICIPIO,
  SELECTS_ESTADO,
} from "./../utils/DOMElements.js";

import {
  cargarEstadosYMunicipios,
  obtenerDatosAdministrador,
  modificarDatosAdministrador,
  eliminarDatosAdministrador,
  subirDatosAdministrador,
} from "./../API/api.js";
import { ntfProcesoErroneo, ntfProcesoExitoso } from "../utils/utils.js";
import {
  formatMatriculaInput,
  formatInputDNI,
  formatInputPhoneNumber,
} from "./../utils/inputFormatters.js";

/**
 * Ocultamos todos los formularios menos el que hemos seleccionado
 * @param {string} formId - ID del formulario
 * @returns
 */
export function mostrarFormularioModal(formId) {
  const modal = document.querySelector(".administrator__modal");
  if (!modal) return;

  const formContainer = modal.querySelector(".administrator__form-container");
  const allForms = formContainer.querySelectorAll(".administrator__modal-form");
  const searchContainer = formContainer.querySelector(
    ".administrator__search-container"
  );

  allForms.forEach((form) => (form.style.display = "none"));
  if (searchContainer) searchContainer.style.display = "none";

  const formToShow = formContainer.querySelector(`#${formId}`);
  if (formToShow) {
    formToShow.style.display = "block";
    formToShow.dataset.currentAction =
      modal.querySelector(".administrator__action-button.active")?.dataset
        .action || "add";
  } else {
    console.warn(`Formulario con id ${formId} no encontrado.`);
  }
}

function formatoD(e) {
  e.target.value = formatInputDNI(e.target.value);
}

/**
 * Aplicamos un placeholder personalizado para un campo que requiera C.I
 * @param {HTMLElement} inputSearch - Input al que se aplicara el placeholder
 */
function placeholderDNI(inputSearch) {
  inputSearch.placeholder = "Ingrese el Documento de Identidad (C.I)";
  inputSearch.removeEventListener("input", formatoM);
  inputSearch.addEventListener("input", formatoD);
}

export function formatoM(e) {
  e.target.value = formatMatriculaInput(e.target.value);
}

/**
 * Aplicamos un placeholder personalizado para un campo que requiera Matricula
 * @param {HTMLElement} inputSearch - Input al que se aplicara el placeholder
 */
async function placeholderVehiculos(inputSearch) {
  inputSearch.placeholder = "Ingrese la Matrícula/VIN";
  inputSearch.removeEventListener("input", formatoD);
}

/**
 * Aplicamos un placeholder personalizado para un campo que requiera un ID
 * @param {HTMLElement} inputSearch - Input al que se aplicara el placeholder
 */
function placeholderID(inputSearch) {
  inputSearch.placeholder = "Ingrese el ID";
  inputSearch.removeEventListener("input", formatoD);
  inputSearch.removeEventListener("input", formatoM);
}

/**
 * Aplicamos un placeholder especifico dependiendo de la seccion
 * @param {string} section - Seccion actual del modal
 * @param {HTMLElement} inputSearch - Input al que se aplicara el placeholder
 */
export function placeholderSearchInput(section, inputSearch) {
  inputSearch.placeholder = "";
  switch (section) {
    case "vehiculos":
      placeholderVehiculos(inputSearch);
      break;
    case "mantenimientos":
      placeholderID(inputSearch);
      break;
    case "conductores":
      placeholderDNI(inputSearch);
      break;
    case "personal":
      placeholderDNI(inputSearch);
      break;
  }
}

function formatoVehiculo(selectVehiculos, inputSearch) {
  selectVehiculos.addEventListener("change", (e) => {
    inputSearch.value = "";
    if (e.target.value === "vin") {
      inputSearch.removeEventListener("input", formatoM);
    } else if (e.target.value === "matricula") {
      inputSearch.addEventListener("input", formatoM);
    }
  });
}

/**
 * Creamos un select dependiendo de la seccion para elegir el tipo de C.I
 * @param {string} section - Seccion actual del modal
 * @param {HTMLElement} formSearch - Formulario de busqueda
 */
function crearSelectBuscadorModal(section, formSearch) {
  const selects = formSearch.querySelector("select");
  selects?.remove();

  if (["conductores", "personal"].includes(section)) {
    crearSelect({
      name: "prefijoCedulaPersonal",
      id: "prefijoCedulaPersonal",
      classes: ["inputStyle"],
      contenedor: formSearch,
      options: {
        "V-": "V-",
        "E-": "E-",
      },
    }).style.width = "fit-content";
  } else if (section === "vehiculos") {
    const selectVehiculos = crearSelect({
      name: "prefijoIdentificadorVehiculoSearch",
      id: "prefijoIdentificadorVehiculoSearch",
      classes: ["inputStyle"],
      contenedor: formSearch,
      options: {
        vin: "Vin",
        matricula: "Matricula",
      },
    });

    selectVehiculos.style.width = "fit-content";

    const inputSearch = formSearch.querySelector("#searchIdInput");
    formatoVehiculo(selectVehiculos, inputSearch);
  }
}

/**
 * Configuramos la logica detras de las acciones del modal (agregar, borrar, modificar)
 * @param {string} action - Accion actual del modal
 * @param {string} section - Seccion actual del modal
 * @returns
 */
export function configurarAccionModal(action, section) {
  // Comprobamos la existencia del modal adminsitrador
  const modal = document.querySelector(".administrator__modal");
  if (!modal) return;

  // Rastreamos los formularios y sus componentes
  const formContainer = modal.querySelector(".administrator__form-container");
  const allForms = formContainer.querySelectorAll(".administrator__modal-form");
  const searchContainer = formContainer.querySelector(
    ".administrator__search-container"
  );
  const searchForm = searchContainer.querySelector(".group-search");
  const currentForm = formContainer.querySelector(
    `#form${section.charAt(0).toUpperCase() + section.slice(1)}`
  );
  const submitButton = currentForm?.querySelector(
    ".administrator__form-submit"
  );
  const searchTitle = searchContainer?.querySelector("#searchTitle");
  const searchActionText = searchContainer?.querySelector("#searchActionText");
  const searchIdLabel = searchContainer?.querySelector("#searchIdLabel");
  const inputSearch = searchContainer?.querySelector("#searchIdInput");

  // A todos los formularios los ocultamos
  allForms.forEach((form) => {
    form.style.display = "none";
    const btn = form.querySelector(".administrator__form-submit");
    if (btn) {
      btn.style.backgroundColor = "";
    }
    form
      .querySelectorAll('input:not([type="submit"]), select, textarea')
      .forEach((el) => (el.disabled = false));

    // Resetear campos condicionales
    resetearCamposCondicionales(form);
  });

  // Ocultamos el contenedor de busqueda (para borrar/modificar)
  if (searchContainer) searchContainer.style.display = "none";

  // Configuramos cada caso de accion
  if (action === "add") {
    if (currentForm) {
      // Configuramos el buscador para agregar
      currentForm.style.display = "block";
      currentForm.reset();
      currentForm
        .querySelectorAll('input:not([type="submit"]), select, textarea')
        .forEach((el) => (el.disabled = false));

      // Disparar eventos de cambio para checkboxes
      dispararEventosCheckbox(currentForm);

      if (submitButton) {
        submitButton.value = `Registrar ${section.slice(0, -1)}`;
        submitButton.style.backgroundColor = "";
      }

      // Actualizar previews si es la sección de vehículos
      if (section === "vehiculos") {
        actualizarPreviewsVehiculo(currentForm);
      }
    }
  } else if (action === "modify") {
    if (searchContainer && searchTitle && searchActionText && searchIdLabel) {
      // Configuramos el buscador para modificar
      searchContainer.style.display = "block";
      searchActionText.textContent = "Modificar";
      searchIdLabel.textContent = `ID del ${section.slice(0, -1)} a Modificar:`;
      searchContainer.dataset.currentAction = action;
      searchContainer.dataset.currentSection = section;
      inputSearch.value = "";
      placeholderSearchInput(section, inputSearch);
      crearSelectBuscadorModal(section, searchForm);
    }
    if (currentForm) {
      currentForm
        .querySelectorAll('input:not([type="submit"]), select, textarea')
        .forEach((el) => (el.disabled = false));
      if (submitButton) {
        submitButton.style.backgroundColor = "";
        submitButton.value = `Modificar ${section.slice(0, -1)}`;
      }
    }
  } else if (action === "delete") {
    if (searchContainer && searchTitle && searchActionText && searchIdLabel) {
      // Configuramos el buscador para borrar
      searchContainer.style.display = "block";
      searchActionText.textContent = "Borrar";
      searchIdLabel.textContent = `ID del ${section.slice(0, -1)} a Borrar:`;
      searchContainer.dataset.currentAction = action;
      searchContainer.dataset.currentSection = section;
      inputSearch.value = "";
      placeholderSearchInput(section, inputSearch);
      crearSelectBuscadorModal(section, searchForm);
    }
  }

  if (currentForm) {
    currentForm.dataset.currentAction = action;
  }
}

/**
 * Resetemos las selecciones de checkbox opcionales del formulario
 * @param {HTMLElement} form - Formulario
 */
function resetearCamposCondicionales(form) {
  const conditionalFieldsVehiculo = form.querySelector(
    "#camposAsignarPersonalVehiculo"
  );
  if (conditionalFieldsVehiculo) {
    conditionalFieldsVehiculo.style.display = "none";
    conditionalFieldsVehiculo
      .querySelectorAll("input, select")
      .forEach((input) => (input.value = ""));
    const checkboxVehiculo = form.querySelector("#asignarPersonalVehiculo");
    if (checkboxVehiculo) checkboxVehiculo.checked = false;
  }

  const conditionalFieldsConductor = form.querySelector(
    "#camposAsignarVehiculoConductor"
  );
  if (conditionalFieldsConductor) {
    conditionalFieldsConductor.style.display = "none";
    conditionalFieldsConductor
      .querySelectorAll("input")
      .forEach((input) => (input.value = ""));
    const checkboxConductor = form.querySelector("#asignarVehiculoConductor");
    if (checkboxConductor) checkboxConductor.checked = false;
  }

  const conditionalPersonalExistente = form.querySelector(
    "#camposPersonalExistenteConductor"
  );
  if (conditionalPersonalExistente) {
    conditionalPersonalExistente.style.display = "none";
    conditionalPersonalExistente
      .querySelectorAll("input, select")
      .forEach((input) => (input.value = ""));
    const checkboxPersonalExistente = form.querySelector(
      "#usarPersonalExistenteConductor"
    );
    if (checkboxPersonalExistente) checkboxPersonalExistente.checked = false;
  }

  const camposNuevosDatosConductor = form.querySelector(
    "#camposNuevosDatosConductor"
  );
  if (camposNuevosDatosConductor) {
    camposNuevosDatosConductor.style.display = "block";
  }
}

/**
 * Ejecutamos los eventos manualmente de los checkboxs
 * @param {HTMLElement} form - Formulario
 */
function dispararEventosCheckbox(form) {
  const chkAsignarPersonal = form.querySelector("#asignarPersonalVehiculo");
  if (chkAsignarPersonal) chkAsignarPersonal.dispatchEvent(new Event("change"));

  const chkAsignarVehiculo = form.querySelector("#asignarVehiculoConductor");
  if (chkAsignarVehiculo) chkAsignarVehiculo.dispatchEvent(new Event("change"));

  const chkUsarPersonalExistente = form.querySelector(
    "#usarPersonalExistenteConductor"
  );
  if (chkUsarPersonalExistente)
    chkUsarPersonalExistente.dispatchEvent(new Event("change"));
}

/**
 * Establecemos un caso por defecto al abrir el modal
 * @param {HTMLElement} form - Formulario
 */
function actualizarPreviewsVehiculo(form) {
  const previewMatricula = form.querySelector("#previewMatriculaVehiculo");
  const previewEstado = form.querySelector("#previewEstadoVehiculo");
  if (previewMatricula) previewMatricula.textContent = "MATRICULA";
  if (previewEstado) previewEstado.textContent = "ESTADO";
}

/**
 * Cargamos todos los estados y municipios desde la base de datos e
 * inicializamos toda la logica de seleccion de estados y municipios
 */
export async function inicializarSelectsUbicacion() {
  const data = await cargarEstadosYMunicipios();
  if (!data) return;

  // Llenar todos los selects de estado
  SELECTS_ESTADO.forEach((estadoId, idx) => {
    const selectEstado = document.getElementById(estadoId);
    const selectMunicipio = document.getElementById(SELECTS_MUNICIPIO[idx]);
    if (!selectEstado || !selectMunicipio) return;

    // Limpiar y llenar estados
    selectEstado.innerHTML = `<option value="" disabled selected>--Elige el Estado--</option>`;
    data.estados.forEach((estado) => {
      const option = document.createElement("option");
      option.value = estado.id_estado;
      option.textContent = estado.estado;
      selectEstado.appendChild(option);
    });

    // Limpiar municipios
    selectMunicipio.innerHTML = `<option value="" disabled selected>--Elige el Municipio--</option>`;

    // Evento para cargar municipios según estado seleccionado
    selectEstado.addEventListener("change", function () {
      const estadoId = this.value;
      selectMunicipio.innerHTML = `<option value="" disabled selected>--Elige el Municipio--</option>`;
      data.municipios
        .filter((mun) => mun.id_estado === estadoId)
        .forEach((mun) => {
          const option = document.createElement("option");
          option.value = mun.id_municipio;
          option.textContent = mun.municipio;
          selectMunicipio.appendChild(option);
        });
    });
  });
}

/**
 * Reformatea la vista previa del estado en la matricula de la seccion Vehiculos (para la busqueda)
 */
export function reformatearEstadoPreview(select) {
  const modalFormVehiculos = document.querySelector("#formVehiculos");
  const previewEstadoText = modalFormVehiculos.querySelector(
    "#previewEstadoVehiculo"
  );
  // Por tema de bug, dejamos este timeout
  setTimeout(() => {
    previewEstadoText.textContent =
      select.options[select.selectedIndex].text.toUpperCase();
  });
}

/**
 * Maneja el envío del formulario de búsqueda del administrador.
 * @param {Event} e - El objeto de evento.
 */
export async function handleAdminSearch(e) {
  e.preventDefault();
  const searchContainer = e.target.closest(".administrator__search-container");
  if (!searchContainer) return;

  const action = searchContainer.dataset.currentAction;
  const section = searchContainer.dataset.currentSection;
  const searchInput = e.target.querySelector("#searchIdInput");
  const searchId = searchInput.value;

  console.log(`Buscando para ${action} en ${section}, ID: ${searchId}`);

  const formData = new FormData();
  formData.append("section", section);
  formData.append("action", "get");
  formData.append("searchIdInput", searchId);

  const prefijoCedula = searchContainer.querySelector(
    "select#prefijoCedulaPersonal"
  );
  const prefijoIdentificadorVehiculo = searchContainer.querySelector(
    "select#prefijoIdentificadorVehiculoSearch"
  );
  if (prefijoCedula)
    formData.append("prefijoCedulaPersonal", prefijoCedula.value);
  if (prefijoIdentificadorVehiculo) {
    formData.append(
      "prefijoIdentificadorVehiculoSearch",
      prefijoIdentificadorVehiculo.value
    );
  }

  // búsqueda de datos
  const itemData = await obtenerDatosAdministrador(formData);

  if (itemData["success"]) {
    showAndPopulateForm(searchContainer, section, action, itemData["data"]);
  } else {
    ntfProcesoErroneo("Oops...", itemData["message"]);
    searchInput.focus();
  }
}

/**
 * Maneja el envío del formulario principal del modal (crear/modificar/borrar).
 * @param {Event} e - El objeto de evento.
 * @param {HTMLFormElement} form - El formulario que se está enviando.
 */
export async function handleAdminFormSubmit(e, form) {
  e.preventDefault();
  const section = form.dataset.sectionmodal;
  let action = form.dataset.currentAction;

  // Si los campos están deshabilitados, la acción real es "borrar".
  const firstField = form.querySelector(
    'input:not([type="submit"]), select, textarea'
  );
  if (firstField && firstField.disabled) {
    action = "delete";
    // Habilita los campos temporalmente para que FormData los pueda leer.
    enableFormFields(form, true);
  }

  const formData = new FormData(form);
  formData.append("action", action);
  formData.append("section", section);
  let response = "";

  if (action === "add" && formData) {
    response = await subirDatosAdministrador(formData);
  } else if (action === "delete" && formData) {
    response = await eliminarDatosAdministrador(formData);
    enableFormFields(form, false);
  } else if (action === "modify" && formData) {
    response = await modificarDatosAdministrador(formData);
  }

  if (response["success"]) {
    // Restaura el estado del formulario y del modal.
    resetAdminModal(form, section, action);
    ntfProcesoExitoso("¡Proceso Exitoso!", response["message"]);
  } else {
    ntfProcesoErroneo("Oops...", response["message"]);
  }
}

/**
 * Oculta el contenedor de búsqueda y muestra el formulario principal con los datos.
 * @param {HTMLElement} searchContainer - El contenedor del formulario de búsqueda.
 * @param {string} section - La sección actual (ej. "vehiculos").
 * @param {string} action - La acción a realizar (ej. "modify").
 * @param {object} itemData - Los datos para poblar el formulario.
 */
function showAndPopulateForm(searchContainer, section, action, itemData) {
  const formId = `form${section.charAt(0).toUpperCase() + section.slice(1)}`;
  const formToShow = document.getElementById(formId);

  if (formToShow) {
    searchContainer.style.display = "none";
    formToShow.style.display = "block";
    enableFormFields(formToShow, true);
    populateForm(formToShow, itemData);
    applySectionSpecificFormatting(formToShow, section, itemData);
    configureFormForAction(formToShow, section, action);
  }
}

/**
 * Rellena los campos de un formulario con los datos proporcionados.
 * @param {HTMLFormElement} form - El formulario a rellenar.
 * @param {object} data - Un objeto con pares clave-valor correspondientes a los IDs de los campos.
 */
async function populateForm(form, data) {
  /* 
    Mapeo de equivalencias para la sección "personal".
    Como el Staff y los Conductores comparten el mismo
    controlador y vienen de la misma tabla de la base
    de datos pero usan diferentes id, entonces hacemos
    una transformacion
  */
  const personalMap = {
    nombreConductor: "nombrePersonal",
    apellidoConductor: "apellidoPersonal",
    cedulaConductor: "cedulaPersonal",
    telefonoConductor: "telefonoPersonal",
    emailConductor: "emailPersonal",
    estadoSedeConductor: "estadoSedePersonal",
    municipioConductor: "municipioPersonal",
    prefijoCedulaConductor: "prefijoCedulaPersonal",
  };

  // Rellenamos todo el formulario con los datos cargados
  for (const key in data) {
    let field = form.querySelector(`#${key}`);
    // Si no existe el campo, busca si hay un mapeo equivalente
    if (
      !field &&
      form.dataset.sectionmodal === "personal" &&
      personalMap[key]
    ) {
      field = form.querySelector(`#${personalMap[key]}`);
    }
    if (field) {
      if (field.type === "checkbox") {
        field.checked = data[key];
        // Dispara el evento change para actualizar la UI dependiente del checkbox.
        field.dispatchEvent(new Event("change", { bubbles: true }));
      } else {
        field.value = data[key];
        if (SELECTS_ESTADO.some((id) => field.id === id)) {
          field.dispatchEvent(new Event("change", { bubbles: true }));
          if (document.querySelector("#previewEstadoVehiculo")) {
            reformatearEstadoPreview(field);
          }
        }
      }
    }
  }
}

/**
 * Aplica formato especial a campos específicos según la sección del formulario cuando se cargan los datos.
 * @param {HTMLFormElement} form - El formulario.
 * @param {string} section - La sección actual.
 * @param {object} itemData - Los datos del ítem.
 */
function applySectionSpecificFormatting(form, section, itemData) {
  const formatField = (selector, formatter, value) => {
    const input = form.querySelector(selector);
    if (input && value) {
      input.value = formatter(value);
    }
  };

  switch (section) {
    case "vehiculos":
      const previewMatricula = form.querySelector("#previewMatriculaVehiculo");
      const previewEstado = form.querySelector("#previewEstadoVehiculo");
      if (previewMatricula)
        previewMatricula.textContent =
          itemData.matriculaVehiculo || "MATRICULA";
      if (previewEstado)
        previewEstado.textContent =
          itemData.estadoSedeVehiculo?.toUpperCase() || "ESTADO";
      formatField(
        "#cedulaPersonalAsignado",
        formatInputDNI,
        itemData.cedulaPersonalAsignado
      );
      break;
    case "conductores":
      formatField(
        "#matriculaVehiculoAsignado",
        formatMatriculaInput,
        itemData.matriculaVehiculoAsignado
      );
      formatField("#cedulaConductor", formatInputDNI, itemData.cedulaConductor);
      formatField(
        "#telefonoConductor",
        formatInputPhoneNumber,
        itemData.telefonoConductor
      );
      formatField(
        "#cedulaPersonalExistenteConductor",
        formatInputDNI,
        itemData.cedulaPersonalExistenteConductor
      );
      break;
    case "personal":
      formatField("#cedulaPersonal", formatInputDNI, itemData.cedulaPersonal);
      formatField(
        "#telefonoPersonal",
        formatInputPhoneNumber,
        itemData.telefonoPersonal
      );
      break;
  }
}

/**
 * Configura el botón de envío y los campos del formulario según la acción (modificar/borrar).
 * @param {HTMLFormElement} form - El formulario.
 * @param {string} section - La sección actual.
 * @param {string} action - La acción (modify/delete).
 */
function configureFormForAction(form, section, action) {
  const submitButton = form.querySelector(".administrator__form-submit");
  if (!submitButton) return;

  const singularSection = section.slice(0, -1); // "vehiculos" -> "vehiculo"

  if (action === "modify") {
    submitButton.value = `Modificar ${singularSection}`;
    submitButton.style.backgroundColor = ""; // Restaura color por defecto
  } else if (action === "delete") {
    submitButton.value = `Confirmar Borrado de ${singularSection}`;
    submitButton.style.backgroundColor = "var(--color-danger)";
    enableFormFields(form, false); // Deshabilita todos los campos
  }
}

/**
 * Habilita o deshabilita todos los campos de un formulario.
 * @param {HTMLFormElement} form - El formulario a modificar.
 * @param {boolean} enable - `true` para habilitar, `false` para deshabilitar.
 */
function enableFormFields(form, enable) {
  form
    .querySelectorAll('input:not([type="submit"]), select, textarea')
    .forEach((el) => (el.disabled = !enable));
}

/**
 * Restablece el formulario y la UI del modal a su estado inicial.
 * @param {HTMLFormElement} form - El formulario a resetear.
 * @param {string} section - La sección del formulario.
 */
function resetAdminModal(form, section, action) {
  form.reset();

  // Resetea elementos de UI específicos de la sección.
  if (section === "vehiculos") {
    actualizarPreviewsVehiculo(form);
  }

  // Restablece la acción del modal a "add".
  const modal = document.querySelector(".administrator__modal");
  if (modal) {
    const currentAdminSection = modal.dataset.currentAdminSection || section;
    // Asume que existe una función para configurar el estado del modal.
    configurarAccionModal("add", currentAdminSection);

    modal
      .querySelectorAll(".administrator__action-button.active")
      .forEach((btn) => btn.classList.remove("active"));
    const addButton = modal.querySelector(
      `.administrator__action-button[data-action="add"]`
    );
    if (addButton) addButton.classList.add("active");
  }
}

/**
 * Abrir el modal especifico
 * @param {HTMLElement} btnOpenModalAdministrator - Boton de abrir modal
 */
export function abrirModalAdministrador(btnOpenModalAdministrator) {
  const modalAdministrator = document.querySelector(".administrator__modal");
  // Inicializamos los selects de ubicación
  inicializarSelectsUbicacion();
  if (modalAdministrator) {
    modalAdministrator.classList.remove("close");
    const sectionName = btnOpenModalAdministrator.dataset.adminsection;
    const modalTitle = modalAdministrator.querySelector(
      ".administrator__modal-title"
    );

    if (modalTitle) {
      modalTitle.textContent = `Administrar: ${
        sectionName.charAt(0).toUpperCase() + sectionName.slice(1)
      }`;
    }

    const formId = `form${
      sectionName.charAt(0).toUpperCase() + sectionName.slice(1)
    }`;
    mostrarFormularioModal(formId);

    const actionButtons = modalAdministrator.querySelectorAll(
      ".administrator__action-button"
    );
    actionButtons.forEach((btn) => btn.classList.remove("active"));
    modalAdministrator
      .querySelector('.administrator__action-button[data-action="add"]')
      .classList.add("active");

    configurarAccionModal("add", sectionName);
    modalAdministrator.dataset.currentAdminSection = sectionName;
  }
}

export function cerrarModalAdministrador(btnCloseModalAdministrator) {
  const modalAdministrator = btnCloseModalAdministrator.closest(
    ".administrator__modal"
  );
  if (modalAdministrator) {
    modalAdministrator.classList.add("close");
    const defaultSectionToOpen = "vehiculos";
    configurarAccionModal("add", defaultSectionToOpen);
    modalAdministrator
      .querySelectorAll(".administrator__action-button")
      .forEach((btn) => btn.classList.remove("active"));
    const addButton = modalAdministrator.querySelector(
      '.administrator__action-button[data-action="add"]'
    );
    if (addButton) addButton.classList.add("active");
    modalAdministrator.dataset.currentAdminSection = defaultSectionToOpen;
    const modalTitle = modalAdministrator.querySelector(
      ".administrator__modal-title"
    );
    if (modalTitle) {
      modalTitle.textContent = `Administrar: ${
        defaultSectionToOpen.charAt(0).toUpperCase() +
        defaultSectionToOpen.slice(1)
      }`;
    }
    mostrarFormularioModal(
      `form${
        defaultSectionToOpen.charAt(0).toUpperCase() +
        defaultSectionToOpen.slice(1)
      }`
    );
  }
}

export function botonesAccionModal(adminActionButton) {
  const modal = adminActionButton.closest(".administrator__modal");
  const currentSection = modal?.dataset.currentAdminSection;
  const action = adminActionButton.dataset.action;

  if (modal && currentSection && action) {
    modal
      .querySelectorAll(".administrator__action-button")
      .forEach((btn) => btn.classList.remove("active"));
    adminActionButton.classList.add("active");
    configurarAccionModal(action, currentSection);
  }
}

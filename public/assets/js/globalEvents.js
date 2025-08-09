import { contenedorPrincipal } from "./utils/DOMElements.js";
import {
  handleBrowserSearch,
  cambiarFormularioBrowser,
  manejarSeleccionTodosResultados,
  manejarCambioTipoContacto,
} from "./sections/browser.js";
import {
  reformatearEstadoPreview,
  handleAdminSearch,
  handleAdminFormSubmit,
  abrirModalAdministrador,
  cerrarModalAdministrador,
  botonesAccionModal,
  formatoM,
} from "./sections/administrator.js";
import {
  formatMatriculaInput,
  formatInputDNI,
  formatInputPhoneNumber,
} from "./utils/inputFormatters.js";
import { cargarDetallesDeBusqueda } from "./sections/detailEntity.js";
import {
  ocultarBuscador,
  initializeSelectionMenu,
} from "./sections/browser.js";
import {
  inicializarMenuPerfil,
  inicializarPerfil,
} from "./sections/profile.js";
import { inicializarPrincipal } from "./sections/principal.js";
import { initializeStatus } from "./sections/status.js";
import { initializeManagement } from "./sections/management.js";

/**
 * Inicializacion de todos los eventos dinamicos
 */
export function registrarEventosGlobales() {
  // Eventos del contenedor principal
  if (contenedorPrincipal) {
    contenedorPrincipal.addEventListener("sectionloaded", (event) => {
      const { sectionName } = event.detail;
      if (sectionName === "profile") {
        inicializarPerfil();
      } else if (sectionName === "principal") {
        inicializarPrincipal();
      } else if (sectionName === "browser") {
        initializeSelectionMenu();
      } else if (sectionName === "management") {
        initializeManagement();
      } else if (sectionName === "status") {
        initializeStatus();
      }
    });

    // Eventos de clic en el contenedor principal
    contenedorPrincipal.addEventListener("click", manejarClicksContenedor);

    // Eventos de input en el contenedor principal
    contenedorPrincipal.addEventListener("input", manejarInputsContenedor);

    // Eventos de cambio en el contenedor principal
    contenedorPrincipal.addEventListener("change", manejarCambiosContenedor);

    // Eventos de submit en el contenedor principal
    contenedorPrincipal.addEventListener("submit", manejarSubmitsContenedor);
  }

  // Eventos del menú de perfil
  inicializarMenuPerfil();
}

/**
 * Manejamos todos los eventos de tipo cambio del contenedor principal
 * @param {Event} e - Evento change
 */
function manejarCambiosContenedor(e) {
  const target = e.target;

  if (target.matches("#tipoDeDato")) {
    const browserGroup = target
      .closest(".browser__container")
      ?.querySelector(".browser__group");
    if (browserGroup) {
      cambiarFormularioBrowser(target.value, browserGroup);
    }
    return;
  }

  if (target.matches(".results__container .selectedAllResults")) {
    manejarSeleccionTodosResultados(e);
    return;
  }

  if (target.matches("#tipoContacto")) {
    manejarCambioTipoContacto(e);
    return;
  }

  // Manejo de campos condicionales en formularios (asignaciones de personal o vehiculo)
  if (target.matches("#asignarPersonalVehiculo")) {
    const camposAdicionales = document.getElementById(
      "camposAsignarPersonalVehiculo"
    );
    const cedulaInput = camposAdicionales?.querySelector(
      "#cedulaPersonalAsignado"
    );
    const rolInput = camposAdicionales?.querySelector("#rolPersonalAsignado");
    if (camposAdicionales) {
      camposAdicionales.style.display = target.checked ? "block" : "none";
      if (target.checked) {
        if (cedulaInput) cedulaInput.required = true;
        if (rolInput) rolInput.required = true;
      } else {
        if (cedulaInput) {
          cedulaInput.required = false;
          cedulaInput.value = "";
        }
        if (rolInput) {
          rolInput.required = false;
          rolInput.value = "";
        }
        camposAdicionales
          .querySelectorAll("select")
          .forEach((select) => (select.selectedIndex = 0));
      }
    }
  }

  if (target.matches("#asignarVehiculoConductor")) {
    const camposAdicionales = document.getElementById(
      "camposAsignarVehiculoConductor"
    );
    const matriculaInput = camposAdicionales?.querySelector(
      "#matriculaVehiculoAsignado"
    );
    if (camposAdicionales) {
      camposAdicionales.style.display = target.checked ? "block" : "none";
      if (target.checked) {
        if (matriculaInput) matriculaInput.required = true;
      } else {
        if (matriculaInput) {
          matriculaInput.required = false;
          matriculaInput.value = "";
        }
      }
    }
  }

  if (target.matches("#usarPersonalExistenteConductor")) {
    const formConductor = target.closest("#formConductores");
    if (!formConductor) return;

    const camposPersonalExistente = formConductor.querySelector(
      "#camposPersonalExistenteConductor"
    );
    const camposNuevosDatos = formConductor.querySelector(
      "#camposNuevosDatosConductor"
    );
    const cedulaExistenteInput = camposPersonalExistente?.querySelector(
      "#cedulaPersonalExistenteConductor"
    );
    const inputsNuevosDatos = camposNuevosDatos?.querySelectorAll(
      'input:not([type="checkbox"]):not([type="file"]), select'
    );

    inputsNuevosDatos?.forEach((input) => {
      if (
        input.hasAttribute("required") &&
        !input.hasAttribute("data-original-required")
      ) {
        input.setAttribute("data-original-required", "true");
      }
    });

    if (camposPersonalExistente && camposNuevosDatos) {
      if (target.checked) {
        camposPersonalExistente.style.display = "block";
        camposNuevosDatos.style.display = "none";
        if (cedulaExistenteInput) cedulaExistenteInput.required = true;
        inputsNuevosDatos?.forEach((input) => {
          input.required = false;
        });
      } else {
        camposPersonalExistente.style.display = "none";
        camposNuevosDatos.style.display = "block";
        if (cedulaExistenteInput) {
          cedulaExistenteInput.required = false;
          cedulaExistenteInput.value = "";
        }
        inputsNuevosDatos?.forEach((input) => {
          if (input.hasAttribute("data-original-required")) {
            input.required = true;
          }
        });
      }
    }
  }

  // Mantenimiento && Personal
  const tipoIdentificador = target.closest("#prefijoIdentificadorVehiculo");
  if (tipoIdentificador) {
    const inputAsignado = document.querySelector("#matriculaVehiculoAsignado");
    const inputIdenficador = document.querySelector("#vehiculoMantenimiento");

    if (tipoIdentificador.value === "vin") {
      inputIdenficador.value = "";
      inputIdenficador.removeEventListener("input", formatoM);

      inputAsignado.value = "";
      inputAsignado.removeEventListener("input", formatoM);
    } else if (tipoIdentificador.value === "matricula") {
      inputIdenficador.value = "";
      inputIdenficador.addEventListener("input", formatoM);

      inputAsignado.value = "";
      inputAsignado.addEventListener("input", formatoM);
    }
  }
}

/**
 * Manejamos todos los eventos de tipo click del contenedor principal
 * @param {Event} e - Evento click
 */
function manejarClicksContenedor(e) {
  const target = e.target;

  // Manejar clic en las filas de resultados del buscador
  const clickedRow = target.closest("tr[data-id]");
  if (clickedRow) {
    cargarDetallesDeBusqueda(clickedRow, target);
  }

  // SECCION NAVEGADOR - Ocultar buscador
  const btnShowContainer = target.closest("#btnShowContainer");
  if (btnShowContainer) {
    ocultarBuscador(btnShowContainer);
  }

  // SECCION ADMINISTRADOR - Abrir Modal
  const btnOpenModalAdministrator = target.closest(
    ".administrator__button[data-adminsection]"
  );
  if (btnOpenModalAdministrator) {
    abrirModalAdministrador(btnOpenModalAdministrator);
  }
  const modalAdministrator = document.querySelector(".administrator__modal");
  if (target.matches(".administrator__modal")) {
    modalAdministrator.classList.remove("active");
    modalAdministrator.classList.add("close");
  }

  // SECCION ADMINISTRADOR - Cerrar Modal
  const btnCloseModalAdministrator = target.closest(
    ".administrator__modal-close"
  );
  if (btnCloseModalAdministrator) {
    cerrarModalAdministrador(btnCloseModalAdministrator);
  }

  // SECCION ADMINISTRADOR - Botones de Acción (Agregar, Borrar, Modificar)
  const adminActionButton = target.closest(
    ".administrator__action-button[data-action]"
  );
  if (adminActionButton) {
    botonesAccionModal(adminActionButton);
  }

  // SECCION ADMINISTRADOR - ATAJOS
  const adminAtajoUbicacion = target.closest(".acortadores");
  if (adminAtajoUbicacion) {
    const inputUbicacion = document.querySelector(".descripcionUbicacion");
    const atajos = adminAtajoUbicacion.querySelectorAll(".atajo");
    atajos.forEach((atajo) => {
      if (target == atajo) {
        inputUbicacion.value = atajo.textContent;
      }
    });
  }
}

/**
 * Manejamos todos los eventos de tipo input del contenedor principal
 * @param {Event} e - Evento input
 */
function manejarInputsContenedor(e) {
  const target = e.target;
  const modalFormVehiculos = target.closest("#formVehiculos");

  if (modalFormVehiculos) {
    const currentAction = modalFormVehiculos.dataset.currentAction;
    if (currentAction === "add" || currentAction === "modify") {
      const inputMatricula =
        modalFormVehiculos.querySelector("#matriculaVehiculo");
      const inputEstado = modalFormVehiculos.querySelector(
        "#estadoSedeVehiculo"
      );
      const previewMatriculaText = modalFormVehiculos.querySelector(
        "#previewMatriculaVehiculo"
      );
      const previewEstadoText = modalFormVehiculos.querySelector(
        "#previewEstadoVehiculo"
      );

      if (target === inputMatricula && previewMatriculaText) {
        const formattedValue = formatMatriculaInput(target.value);
        target.value = formattedValue;
        previewMatriculaText.textContent = formattedValue || "MATRICULA";
      }
      if (target === inputEstado && previewEstadoText) {
        reformatearEstadoPreview(target);
      }
    }
  }

  if (
    target.matches(
      "#cedulaConductor, #cedulaPersonal, #cedulaPersonalAsignado, #cedulaPersonalExistenteConductor, #modify-cedula"
    )
  ) {
    target.value = formatInputDNI(target.value);
  }
  if (
    target.matches("#telefonoConductor, #telefonoPersonal, #modify-telefono")
  ) {
    target.value = formatInputPhoneNumber(target.value);
  }
}

/**
 * Manejamos los evento de submit al manejador correspondiente.
 * @param {Event} e - Evento submit.
 */
function manejarSubmitsContenedor(e) {
  // Buscador
  if (e.target.matches("#browser__container")) {
    handleBrowserSearch(e);
    return;
  }

  // Administrador
  // -- Buscador
  if (e.target.closest("#administratorSearchForm")) {
    handleAdminSearch(e);
  }

  // Administrador
  // -- Submit
  if (e.target.matches(".administrator__modal-form")) {
    handleAdminFormSubmit(e, e.target);
  }
}

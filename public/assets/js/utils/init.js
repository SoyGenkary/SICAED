import { actualizarEstadoOnline } from "./utils.js";
import { opcionesEnlaces } from "./DOMElements.js";
import {
  cargarSeccion,
  cargarUltimaSeccion,
} from "./../modules/sectionLoader.js";

/**
 * Agregar Eventos al Notificador de Estado de Red
 */
function eventosEstadoOnline() {
  actualizarEstadoOnline();

  window.addEventListener("online", actualizarEstadoOnline);
  window.addEventListener("offline", actualizarEstadoOnline);
}

/**
 * Evento para la navegacion entre secciones
 */
function eventosNavegacionSecciones() {
  opcionesEnlaces.forEach((enlace) => {
    enlace.addEventListener("click", (e) => {
      e.preventDefault();
      const sectionSelected = enlace.dataset.section;
      if (sectionSelected) {
        cargarSeccion(sectionSelected);
      }
    });
  });
}

/**
 * Evento para minimizar el navbar
 */
function eventosNavbar() {
  const btnHideNavbar = document.querySelector("#btnCloseNavbar");
  if (btnHideNavbar) {
    btnHideNavbar.addEventListener("click", () => {
      const navbar = document.querySelector(".nav");
      if (navbar) {
        navbar.classList.toggle("close");
        btnHideNavbar.classList.toggle("active");
      }
    });
  }
}

/**
 * Inicializar todos los eventos
 */
export function inicializarPagina() {
  // Inicializar estado online
  eventosEstadoOnline();

  // Cargamos la ultima seccion inizializada
  cargarUltimaSeccion();

  // Configurar eventos de navegación
  eventosNavegacionSecciones();

  // Configurar botón de cerrar navbar
  eventosNavbar();
}

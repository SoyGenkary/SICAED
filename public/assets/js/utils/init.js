import { updateStatus } from "./utils.js";
import { sectionsLinks } from "./DOMElements.js";
import { loadSection, loadLastSection } from "./../modules/sectionLoader.js";

/**
 * Agregar Eventos al Notificador de Estado de Red
 */
function configStatus() {
  updateStatus();

  setInterval(() => {
    updateStatus();
  }, 3000);
}

/**
 * Evento para la navegacion entre secciones
 */
function eventsNavigationSections() {
  sectionsLinks.forEach((section) => {
    section.addEventListener("click", (e) => {
      e.preventDefault();

      const sectionSelected = section.dataset.section;
      if (!sectionSelected) return;

      loadSection(sectionSelected);
    });
  });
}

/**
 * Evento para minimizar el navbar
 */
function eventsNavBar() {
  const btnHideNavbar = document.querySelector("#btnCloseNavbar");
  if (!btnHideNavbar) return;

  btnHideNavbar.addEventListener("click", () => {
    const navbar = document.querySelector(".nav");
    if (navbar) return;

    navbar.classList.toggle("close");
    btnHideNavbar.classList.toggle("active");
  });
}

/**
 * Inicializar todos los eventos
 */
export function inicializarPagina() {
  // Inicializar estado online
  configStatus();

  // Cargamos la ultima seccion inizializada
  loadLastSection();

  // Configurar eventos de navegación
  eventsNavigationSections();

  // Configurar botón de cerrar navbar
  eventsNavBar();
}

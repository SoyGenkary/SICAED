import {
  contenedorPrincipal, // Sigue importando el contenedor principal por defecto
  LAST_SECTION_KEY,
  DEFAULT_SECTION,
  sectionsLinks,
} from "./../utils/DOMElements.js";
import { inicializarDetailEntity } from "./../sections/detailEntity.js";
import { actualizarEnlaceActivo } from "./../utils/utils.js";

/**
 * Genera la ruta del archivo de la sección.
 * @param {string} nameSection
 * @returns {string}
 */
function generarRutaArchivo(nameSection) {
  return nameSection.includes(".php")
    ? `sections/${nameSection}`
    : `sections/${nameSection}.php`;
}

/**
 * Intenta obtener el contenido de una sección.
 * @param {string} filePath
 * @returns {Promise<string>}
 */
async function obtenerContenidoSeccion(filePath) {
  const response = await fetch(filePath);
  if (!response.ok) {
    throw new Error(
      `Error ${response.status}: ${response.statusText} (Archivo: ${filePath})`
    );
  }
  return await response.text();
}

/**
 * Inserta el contenido HTML en el contenedor especificado.
 * @param {HTMLElement} containerElement - El elemento DOM donde se insertará el HTML.
 * @param {string} html - El contenido HTML a insertar.
 */
function mostrarContenido(containerElement, html) {
  containerElement.innerHTML = html;
}

/**
 * Muestra un mensaje de error en el contenedor especificado.
 * @param {HTMLElement} containerElement - El elemento DOM donde se mostrará el error.
 * @param {string} mensaje - El mensaje de error a mostrar.
 */
function mostrarError(containerElement, mensaje) {
  containerElement.innerHTML = `
    <div class="error-container">
      <h2>Error al cargar la sección</h2>
      <p>${mensaje}</p>
    </div>
  `;
}

/**
 * Guarda la sección actual en localStorage.
 * @param {string} nameSection
 */
function guardarSeccionEnStorage(nameSection, key = LAST_SECTION_KEY) {
  localStorage.setItem(key, nameSection);
}

/**
 * Lanza un evento personalizado indicando que la sección fue cargada.
 * @param {HTMLElement} containerElement - El elemento DOM desde donde se lanza el evento.
 * @param {string} nameSection - El nombre de la sección cargada.
 */
function notificarCarga(containerElement, nameSection) {
  containerElement.dispatchEvent(
    new CustomEvent("sectionloaded", {
      detail: { sectionName: nameSection },
      bubbles: true,
    })
  );
}

/**
 * Carga una sección y maneja errores, actualiza enlace activo, localStorage, y eventos.
 * @param {string} nameSection - El nombre de la sección a cargar.
 * @param {boolean} [isInitialLoadFromStorage=false] - Indica si es una carga inicial desde el almacenamiento.
 * @param {HTMLElement} [targetContainer=null] - (Opcional) El elemento DOM donde se cargará la sección. Si no se especifica, usa 'contenedorPrincipal'.
 */
export async function loadSection(
  nameSection,
  isInitialLoadFromStorage = false,
  targetContainer = null // Nuevo parámetro opcional
) {
  // Determina qué contenedor usar: el especificado o el predeterminado
  const currentContainer = targetContainer || contenedorPrincipal;

  if (!currentContainer) {
    console.error(
      "El contenedor de destino no existe o no se pudo determinar."
    );
    return;
  }

  if (currentContainer == document.querySelector("#detailResult-container")) {
    contenedorPrincipal.style.display = "none";
    const btnBackButtonModal =
      currentContainer.querySelector("#closeModalDetail");

    if (btnBackButtonModal) {
      btnBackButtonModal.addEventListener(
        "click",
        (e) => {
          e.preventDefault();
          currentContainer.innerHTML = "";
          contenedorPrincipal.style.display = "block";
        },
        { once: true }
      );
    }
  } else {
    document.querySelector("#detailResult-container").innerHTML = "";
    contenedorPrincipal.style.display = "block";
  }

  const filePath = generarRutaArchivo(nameSection);

  try {
    const contenido = await obtenerContenidoSeccion(filePath);
    mostrarContenido(currentContainer, contenido); // Pasar el contenedor a mostrarContenido

    if (currentContainer == document.querySelector("#detailResult-container")) {
      const btnBackButtonModal =
        currentContainer.querySelector("#closeModalDetail");

      if (btnBackButtonModal) {
        btnBackButtonModal.addEventListener(
          "click",
          (e) => {
            e.preventDefault();
            currentContainer.innerHTML = "";
            contenedorPrincipal.style.display = "block";
          },
          { once: true }
        );
      }

      inicializarDetailEntity();

      guardarSeccionEnStorage(nameSection, "resultado");
    }

    // Solo guarda en localStorage y actualiza el enlace activo si se carga en el contenedor principal
    if (currentContainer === contenedorPrincipal) {
      guardarSeccionEnStorage(nameSection);
      actualizarEnlaceActivo(nameSection);
    }

    notificarCarga(currentContainer, nameSection); // Pasar el contenedor a notificarCarga
  } catch (error) {
    console.error(`Error al cargar la sección '${nameSection}':`, error);
    mostrarError(currentContainer, error.message); // Pasar el contenedor a mostrarError

    // La lógica de recarga por defecto solo aplica si el error ocurre en el contenedor principal
    if (currentContainer === contenedorPrincipal) {
      if (isInitialLoadFromStorage || nameSection !== DEFAULT_SECTION) {
        localStorage.removeItem(LAST_SECTION_KEY);
        if (nameSection !== DEFAULT_SECTION) {
          console.warn(
            `Fallo al cargar '${nameSection}'. Cargando sección por defecto: '${DEFAULT_SECTION}'.`
          );
          // Llamada recursiva a cargarSeccion, asegurándose de usar el contenedor principal
          await loadSection(DEFAULT_SECTION, false, contenedorPrincipal);
        }
      }
    }
  }
}

/**
 * Cargar última sección visitada o sección por defecto
 * Esta función siempre cargará en el contenedor principal.
 */
export function loadLastSection() {
  let lastSection = localStorage.getItem(LAST_SECTION_KEY) || "status";

  const exist = Array.from(sectionsLinks).some(
    (enlace) => enlace.dataset.section === lastSection
  );

  if (!exist) lastSection = "status";

  loadSection(lastSection, true, contenedorPrincipal); // Asegura que siempre use el contenedor principal
}

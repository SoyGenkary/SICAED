import {
  mainContainer, // Sigue importando el contenedor principal por defecto
  LAST_SECTION_KEY,
  DEFAULT_SECTION,
  sectionsLinks,
} from "./../utils/DOMElements.js";
import { InitializeDetailEntity } from "./../sections/detailEntity.js";
import { updateSectionSelected } from "./../utils/utils.js";

/**
 * Genera la ruta del archivo de la sección.
 * @param {string} nameSection
 * @returns {string}
 */
function generateFilePath(nameSection) {
  return nameSection.includes(".php")
    ? `sections/${nameSection}`
    : `sections/${nameSection}.php`;
}

/**
 * Intenta obtener el contenido de una sección.
 * @param {string} filePath
 * @returns {Promise<string>}
 */
async function getSectionContent(filePath) {
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
function showContent(containerElement, html) {
  containerElement.innerHTML = html;
}

/**
 * Muestra un mensaje de error en el contenedor especificado.
 * @param {HTMLElement} containerElement - El elemento DOM donde se mostrará el error.
 * @param {string} message - El mensaje de error a mostrar.
 */
function showError(containerElement, message) {
  containerElement.innerHTML = `
    <div class="error-container">
      <h2>Error al cargar la sección</h2>
      <p>${message}</p>
    </div>
  `;
}

/**
 * Guarda la sección actual en localStorage.
 * @param {string} nameSection
 */
function saveSectionToStorage(nameSection, key = LAST_SECTION_KEY) {
  localStorage.setItem(key, nameSection);
}

/**
 * Lanza un evento personalizado indicando que la sección fue cargada.
 * @param {HTMLElement} containerElement - El elemento DOM desde donde se lanza el evento.
 * @param {string} nameSection - El nombre de la sección cargada.
 */
function notifyLoad(containerElement, nameSection) {
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
  targetContainer = null
) {
  // Determina qué contenedor usar: el especificado o el predeterminado
  const currentContainer = targetContainer || mainContainer;

  if (!currentContainer) {
    console.error(
      "El contenedor de destino no existe o no se pudo determinar."
    );
    return;
  }

  if (currentContainer == document.querySelector("#detailResult-container")) {
    mainContainer.style.display = "none";
    const btnBackButtonModal =
      currentContainer.querySelector("#closeModalDetail");

    if (btnBackButtonModal) {
      btnBackButtonModal.addEventListener(
        "click",
        (e) => {
          e.preventDefault();
          currentContainer.innerHTML = "";
          mainContainer.style.display = "block";
        },
        { once: true }
      );
    }
  } else {
    document.querySelector("#detailResult-container").innerHTML = "";
    mainContainer.style.display = "block";
  }

  const filePath = generateFilePath(nameSection);

  try {
    const content = await getSectionContent(filePath);
    showContent(currentContainer, content); // Pasar el contenedor a mostrarContenido

    if (currentContainer == document.querySelector("#detailResult-container")) {
      const btnBackButtonModal =
        currentContainer.querySelector("#closeModalDetail");

      if (btnBackButtonModal) {
        btnBackButtonModal.addEventListener(
          "click",
          (e) => {
            e.preventDefault();
            currentContainer.innerHTML = "";
            mainContainer.style.display = "block";
          },
          { once: true }
        );
      }

      InitializeDetailEntity();

      saveSectionToStorage(nameSection, "resultado");
    }

    // Solo guarda en localStorage y actualiza el enlace activo si se carga en el contenedor principal
    if (currentContainer === mainContainer) {
      saveSectionToStorage(nameSection);
      updateSectionSelected(nameSection);
    }

    notifyLoad(currentContainer, nameSection); // Pasar el contenedor a notificarCarga
  } catch (error) {
    showError(currentContainer, error.message); // Pasar el contenedor a mostrarError

    // La lógica de recarga por defecto solo aplica si el error ocurre en el contenedor principal
    if (currentContainer === mainContainer) {
      if (isInitialLoadFromStorage || nameSection !== DEFAULT_SECTION) {
        localStorage.removeItem(LAST_SECTION_KEY);
        if (nameSection !== DEFAULT_SECTION) {
          await loadSection(DEFAULT_SECTION, false, mainContainer);
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

  loadSection(lastSection, true, mainContainer);
}

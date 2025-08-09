// ELEMENTOS Y CONSTANTES GLOBALES
export const statusContainer = document.querySelector(
  ".main .header .header__onLine"
);
export const mainContainer = document.getElementById("content-container");
export const sectionsLinks = document.querySelectorAll(".option a");
export const LAST_SECTION_KEY = "lastVisitedSection";
export const DEFAULT_SECTION = "principal";

// IDs de los selects de estado y municipio en todos los formularios
export const SELECTS_ESTADO = [
  "estadoSedeVehiculo",
  "estadoSedeConductor",
  "estadoSedePersonal",
];
export const SELECTS_MUNICIPIO = [
  "municipioVehiculo",
  "municipioConductor",
  "municipioPersonal",
];

/**
 * Crea y agrega un elemento input.
 * @param {object} config - Objeto de configuración.
 * @param {string} config.type - Tipo del input.
 * @param {string} config.name - Nombre del input.
 * @param {string[]} [config.classes=['search__input']] - Array de clases.
 * @param {HTMLElement} config.contenedor - Contenedor donde se agregará.
 * @param {string} [config.posicion='afterbegin'] - Posición de inserción.
 * @param {HTMLElement} [config.referencia=null] - Elemento de referencia para insertBefore.
 * @param {object} [config.atributos={}] - Atributos adicionales.
 * @param {string} [config.placeholder=''] - Placeholder del input.
 * @returns {HTMLInputElement|null}
 */
export function createInput({
  type,
  name,
  classes = ["search__input"],
  container,
  position = "afterbegin",
  reference = null,
  attributes = {},
  placeholder = "",
}) {
  if (!type || !name || !container) return;

  const newElement = document.createElement("input");
  newElement.type = type;
  newElement.name = name;
  newElement.placeholder = placeholder;

  classes.forEach((cls) => newElement.classList.add(cls));

  Object.entries(attributes).forEach(([key, value]) =>
    newElement.setAttribute(key, value)
  );

  if (
    reference &&
    reference.parentNode === container &&
    position === "beforeend"
  ) {
    reference.insertAdjacentElement("afterend", newElement);
  } else if (reference && reference.parentNode === container) {
    container.insertBefore(newElement, reference.nextSibling);
  } else {
    container.insertAdjacentElement(position, newElement);
  }
  return newElement;
}

/**
 * Crea y agrega un elemento select.
 * @param {object} config - Objeto de configuración.
 * @param {string} config.name - Nombre del select.
 * @param {string} config.id - ID del select.
 * @param {string[]} [config.classes=['search__input']] - Array de clases.
 * @param {HTMLElement} config.contenedor - Contenedor donde se agregará.
 * @param {string} [config.posicion='afterbegin'] - Posición de inserción.
 * @param {object} [config.options={}] - Opciones del select {value: text}.
 * @param {boolean} [config.addDefaultPlaceholder=false] - Si se añade una opción placeholder deshabilitada.
 * @param {string} [config.defaultPlaceholderText='-- Seleccione --'] - Texto para la opción placeholder.
 * @returns {HTMLSelectElement|null}
 */
export function createSelect({
  name,
  id,
  classes = ["search__input"],
  container,
  position = "afterbegin",
  options = {},
  addDefaultPlaceholder = false,
  defaultPlaceholderText = "-- Seleccione --",
}) {
  if (!name || !id || !container) return;

  const newElement = document.createElement("select");
  newElement.name = name;
  newElement.id = id;

  classes.forEach((cls) => newElement.classList.add(cls));

  if (addDefaultPlaceholder) {
    const placeholderOption = document.createElement("option");
    placeholderOption.value = "";
    placeholderOption.textContent = defaultPlaceholderText;
    placeholderOption.disabled = true;
    placeholderOption.selected = true;
    newElement.appendChild(placeholderOption);
  }

  Object.entries(options).forEach(([value, text]) => {
    const option = document.createElement("option");
    option.value = value;
    option.textContent = text;
    newElement.appendChild(option);
  });

  container.insertAdjacentElement(position, newElement);
  return newElement;
}

// STATE CONSTANTS
export const statusContainer = document.querySelector(
  ".main .header .header__onLine"
);

// LAYOUT CONSTANTS
export const mainContainer = document.getElementById("content-container");
export const sectionLinks = document.querySelectorAll(".option a");

// SECTION CONSTANTS
export const LAST_SECTION_KEY = "lastVisitedSection";
export const DEFAULT_SECTION = "main";

// FORM CONSTANTS
// IDs of the state and municipality selects in all forms
export const stateSelectIds = [
  "estadoSedeVehiculo",
  "estadoSedeConductor",
  "estadoSedePersonal",
];
export const municipalitySelectIds = [
  "municipioVehiculo",
  "municipioConductor",
  "municipioPersonal",
];

/**
 * Helper: Apply classes to an element.
 */
function applyClasses(element, classes) {
  classes.forEach((cls) => element.classList.add(cls));
}

/**
 * Helper: Apply attributes to an element.
 */
function applyAttributes(element, attributes) {
  Object.entries(attributes).forEach(([key, value]) =>
    element.setAttribute(key, value)
  );
}

/**
 * Create and append an input element.
 * @param {object} config
 * @param {string} config.type
 * @param {string} config.name
 * @param {string[]} [config.classes=['search__input']]
 * @param {HTMLElement} config.container
 * @param {string} [config.position='afterbegin']
 * @param {HTMLElement} [config.reference=null]
 * @param {object} [config.attributes={}]
 * @param {string} [config.placeholder='']
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
  if (!type || !name || !(container instanceof HTMLElement)) return null;

  const inputElement = document.createElement("input");
  Object.assign(inputElement, { type, name, placeholder });

  applyClasses(inputElement, classes);
  applyAttributes(inputElement, attributes);

  if (reference && reference.parentNode === container) {
    position === "beforeend"
      ? reference.insertAdjacentElement("afterend", inputElement)
      : container.insertBefore(inputElement, reference.nextSibling);
  } else {
    container.insertAdjacentElement(position, inputElement);
  }

  return inputElement;
}

/**
 * Create and append a select element.
 * @param {object} config
 * @param {string} config.name
 * @param {string} config.id
 * @param {string[]} [config.classes=['search__input']]
 * @param {HTMLElement} config.container
 * @param {string} [config.position='afterbegin']
 * @param {object} [config.options={}]
 * @param {boolean} [config.addDefaultPlaceholder=false]
 * @param {string} [config.defaultPlaceholderText='-- Select --']
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
  defaultPlaceholderText = "-- Select --",
}) {
  if (!name || !id || !(container instanceof HTMLElement)) return null;

  const selectElement = document.createElement("select");
  Object.assign(selectElement, { name, id });
  applyClasses(selectElement, classes);

  if (addDefaultPlaceholder) {
    const placeholderOption = new Option(
      defaultPlaceholderText,
      "",
      true,
      true
    );
    placeholderOption.disabled = true;
    selectElement.appendChild(placeholderOption);
  }

  Object.entries(options).forEach(([value, text]) =>
    selectElement.appendChild(new Option(text, value))
  );

  container.insertAdjacentElement(position, selectElement);
  return selectElement;
}

// ELEMENTOS Y CONSTANTES GLOBALES
export const opciones = document.querySelectorAll(".option");
export const statusContainer = document.querySelector(
  ".main .header .header__onLine"
);
export const contenedorPrincipal = document.getElementById("content-container");
export const opcionesEnlaces = document.querySelectorAll(".option a");
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
export function crearInput({
  type,
  name,
  classes = ["search__input"],
  contenedor,
  posicion = "afterbegin",
  referencia = null,
  atributos = {},
  placeholder = "",
}) {
  if (!type || !name || !contenedor) return null;
  const nuevoElemento = document.createElement("input");
  nuevoElemento.type = type;
  nuevoElemento.name = name;
  nuevoElemento.placeholder = placeholder;
  classes.forEach((cls) => nuevoElemento.classList.add(cls));
  Object.entries(atributos).forEach(([key, value]) =>
    nuevoElemento.setAttribute(key, value)
  );

  if (
    referencia &&
    referencia.parentNode === contenedor &&
    posicion === "beforeend"
  ) {
    referencia.insertAdjacentElement("afterend", nuevoElemento);
  } else if (referencia && referencia.parentNode === contenedor) {
    contenedor.insertBefore(nuevoElemento, referencia.nextSibling);
  } else {
    contenedor.insertAdjacentElement(posicion, nuevoElemento);
  }
  return nuevoElemento;
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
export function crearSelect({
  name,
  id,
  classes = ["search__input"],
  contenedor,
  posicion = "afterbegin",
  options = {},
  addDefaultPlaceholder = false,
  defaultPlaceholderText = "-- Seleccione --",
}) {
  if (!name || !id || !contenedor) return null;
  const nuevoElemento = document.createElement("select");
  nuevoElemento.name = name;
  nuevoElemento.id = id;
  classes.forEach((cls) => nuevoElemento.classList.add(cls));

  if (addDefaultPlaceholder) {
    const placeholderOption = document.createElement("option");
    placeholderOption.value = "";
    placeholderOption.textContent = defaultPlaceholderText;
    placeholderOption.disabled = true;
    placeholderOption.selected = true;
    nuevoElemento.appendChild(placeholderOption);
  }

  Object.entries(options).forEach(([value, text]) => {
    const option = document.createElement("option");
    option.value = value;
    option.textContent = text;
    nuevoElemento.appendChild(option);
  });

  contenedor.insertAdjacentElement(posicion, nuevoElemento);
  return nuevoElemento;
}

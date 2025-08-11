import { statusContainer, sectionLinks } from "./DOMElements.js";

/**
 * Actualiza el estado de conexion
 */
export function updateStatus() {
  if (!statusContainer) return;

  const circle = statusContainer.querySelector(".circle-status");
  const statusText = statusContainer.querySelector(".statusOnLine");
  if (!circle || !statusText) return;

  circle.classList.toggle("online", navigator.onLine);
  circle.classList.toggle("offline", !navigator.onLine);

  statusText.textContent = navigator.onLine
    ? "En línea."
    : "Sin conexión a internet.";
}

/**
 * Actualiza la parte visual activa para cargar la seccion
 * @param {string} sectionName - Nombre de la seccion a activar
 */
export function updateSectionSelected(sectionName) {
  sectionLinks.forEach((section) => {
    const item = section.closest(".option");
    const data = section?.dataset.section;

    item.classList.toggle("active", data === sectionName);
  });
}

/**
 * Saltar Notificacion de Dato no Encontrado
 */
export function ntfProcessError(title, message) {
  Swal.fire({
    title: title,
    text: message,
    icon: "error",
  });
}

/**
 * Saltar Notificacion de Dato no Encontrado
 */
export function ntfProcessSuccessful(title, message) {
  Swal.fire({
    title: title,
    text: message,
    icon: "success",
  });
}

/**
 * Saltar notificiacion para ingresar una contraseña
 */
export async function ntfLoginPassword(title = "Verificación de cuenta...") {
  const { value: password } = await Swal.fire({
    title: title,
    input: "password",
    inputLabel: "Contraseña:",
    inputPlaceholder: "Ingresa tu contraseña...",
  });
  if (password) {
    return password;
  } else {
    ntfProcessError("Oops...", "Ingresa una contraseña valida!");
    return false;
  }
}

/**
 * Saltar notificacion para confirmar una accion
 */
export function ntfConfirm(title, message) {
  return Swal.fire({
    title: title,
    text: message,
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#0a0",
    cancelButtonColor: "#d33",
    confirmButtonText: "Ok, Adelante!",
  }).then((result) => {
    return result.isConfirmed;
  });
}

import { statusContainer, opciones } from "./DOMElements.js";

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
 * Actualiza la seccion activa para cargar la seccion
 * @param {string} sectionName - Nombre de la seccion a activada
 */
export function actualizarEnlaceActivo(sectionName) {
  opciones.forEach((op) => {
    const enlace = op.querySelector("a[data-section]");
    op.classList.toggle("active", enlace?.dataset.section === sectionName);
  });
}

/**
 * Saltar Notificacion de Dato no Encontrado
 */
export function ntfProcesoErroneo(title, message) {
  Swal.fire({
    title: title,
    text: message,
    icon: "error",
  });
}

/**
 * Saltar Notificacion de Dato no Encontrado
 */
export function ntfProcesoExitoso(title, message) {
  Swal.fire({
    title: title,
    text: message,
    icon: "success",
  });
}

/**
 * Saltar notificiacion para ingresar una contraseña
 */
export async function ntfIngresarContrasenia(
  title = "Verificación de cuenta..."
) {
  const { value: password } = await Swal.fire({
    title: title,
    input: "password",
    inputLabel: "Contraseña:",
    inputPlaceholder: "Ingresa tu contraseña...",
  });
  if (password) {
    return password;
  } else {
    ntfProcesoErroneo("Oops...", "Ingresa una contraseña valida!");
    return false;
  }
}

/**
 * Saltar notificacion para confirmar una accion
 */
export function ntfConfirmar(title, message) {
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

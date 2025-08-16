import { initializeLayout } from "../utils/init.js";
import { registrarEventosGlobales } from "../globalEvents.js";

export function initializeSICAED() {
  initializeLayout();
  registrarEventosGlobales();
}

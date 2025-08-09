import { initializeLayout } from "../utils/init.js";
import { registrarEventosGlobales } from "../globalEvents.js";

export function inicializarSICAED() {
  initializeLayout();
  registrarEventosGlobales();
}

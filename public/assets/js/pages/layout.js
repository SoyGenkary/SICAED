import { inicializarPagina } from "../utils/init.js";
import { registrarEventosGlobales } from "../globalEvents.js";

export function inicializarSICAED() {
  inicializarPagina();
  registrarEventosGlobales();
}

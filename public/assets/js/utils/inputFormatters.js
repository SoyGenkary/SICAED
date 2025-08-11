/**
 * Formatea el valor del input como XXX-XXXX (solo letras y números, mayúsculas).
 * @param {string} value
 * @returns {string}
 */
export function formatInputLicensePlate(value) {
  const cleaned = value
    .toUpperCase()
    .replace(/[^A-Z0-9]/g, "")
    .slice(0, 7);
  if (cleaned.length <= 3) {
    return cleaned;
  }
  return `${cleaned.slice(0, 3)}${cleaned.slice(3)}`;
}

/**
 * Formatea el valor del input como XX.XXX.XXX.
 * @param {string} value
 * @returns {string}
 */
export function formatInputDNI(value) {
  const cleaned = value.replace(/\D/g, "");
  const limited = cleaned.slice(0, 8);
  const match = limited.match(/^(\d{0,2})(\d{0,3})(\d{0,3})$/);
  if (!match) return "";
  let formatted = match[1];
  if (match[2]) formatted += "." + match[2];
  if (match[3]) formatted += "." + match[3];
  return formatted;
}

/**
 * Formatea el valor del input como 0XXX-XXXXXXX.
 * @param {string} value
 * @returns {string}
 */
export function formatInputPhoneNumber(value) {
  const cleaned = value.replace(/\D/g, "");
  let limited = cleaned.slice(0, 11);
  if (limited[0] !== "0") {
    limited = "0" + limited;
  }
  const match = limited.match(/^(\d{4})(\d{0,7})$/);
  if (match) {
    return `${match[1]}${match[2] ? "-" + match[2] : ""}`;
  }
  return cleaned;
}

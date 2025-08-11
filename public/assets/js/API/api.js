// ------------- UTILIDADES -------------

export async function apiRequest(formData) {
  const response = await fetch("/SICAED/routes/api.php", {
    method: "POST",
    body: formData,
  });
  if (!response.ok) throw new Error(`Error: ${response.status}`);
  return await response.json();
}

// ------------- VARIADOS -------------

export async function actualizarDatosPerfil(formData) {
  return await apiRequest(formData);
}

export async function verificarContraseniaPerfil(formData) {
  return await apiRequest(formData);
}

export async function cargarEstadosYMunicipios() {
  const response = await fetch("/SICAED/app/Services/StatesService.php");
  return await response.json();
}

// -------- ADMINISTRADOR --------

export async function subirDatosAdministrador(formData) {
  return await apiRequest(formData);
}

export async function obtenerDatosAdministrador(formData) {
  return await apiRequest(formData);
}

export async function eliminarDatosAdministrador(formData) {
  return await apiRequest(formData);
}

export async function modificarDatosAdministrador(formData) {
  return await apiRequest(formData);
}

// ------------ BUSCADOR ---------------
export async function searchData(formData) {
  return await apiRequest(formData);
}

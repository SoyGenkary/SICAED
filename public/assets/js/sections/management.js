import { apiRequest } from "./../API/api.js";
import {
  ntfProcesoErroneo,
  ntfProcesoExitoso,
  ntfConfirmar,
  ntfIngresarContrasenia,
} from "../utils/utils.js";

export function initializeManagement() {
  eventsBtnModals();
}

function eventsBtnModals() {
  const container = document.querySelector(".management-section");
  if (container) {
    container.addEventListener("click", async (e) => {
      const target = e.target;

      const row = target.closest("tr");
      const id = row?.querySelector("td:first-child")?.dataset.id;

      let formData = new FormData();

      const modalsContainer = document.querySelector(".modals-actions");
      const modalViewDetail = document.querySelector(".modal.view-detail");
      const modalModifyDetail = document.querySelector(".modal.modify-detail");
      const modalDeleteDetail = document.querySelector(".modal.delete-detail");

      if (target.closest("#btnActionView")) {
        formData.append("section", "user");
        formData.append("action", "getByID");
        formData.append("id_user", id);

        const response = await apiRequest(formData);

        if (response["success"]) {
          // Rellenamos los datos
          if (response.perfil) {
            document.getElementById("view-id").textContent =
              response.perfil.id_usuario || "";
            document.getElementById("view-nombre").textContent =
              response.perfil.nombre || "";
            document.getElementById("view-telefono").textContent =
              response.perfil.telefono || "";
            document.getElementById("view-cedula").textContent =
              response.perfil.cedula || "";
            document.getElementById("view-email").textContent =
              response.perfil.email || "";
            document.getElementById("view-fecha-registro").textContent =
              response.perfil.fecha_registro || "";
            document.getElementById("view-ultimo-login").textContent =
              response.perfil.ultimo_login || "";
            document.getElementById("view-rol").textContent =
              response.perfil.rol || "";
            document.getElementById("view-photo").src =
              response.perfil.ruta_img || "./assets/img/icons/avatar.png";

            // Historial
            if (response.historial && Array.isArray(response.historial)) {
              const historyContainer =
                document.querySelector(".view-user-history");
              historyContainer.innerHTML = ""; // Limpia el historial anterior

              response.historial.forEach((item) => {
                // Extrae el tipo de acción después de USUARIO:
                let tipo = "";
                if (
                  item.accion.startsWith("USUARIO:") ||
                  item.accion.startsWith("VEHICULO:") ||
                  item.accion.startsWith("PERSONAL:")
                ) {
                  tipo = item.accion.split(":")[1];
                }
                // Define la clase según el tipo
                let colorClass = "";
                switch (tipo) {
                  case "LOGIN":
                    colorClass = "historial-login";
                    break;
                  case "LOGOUT":
                    colorClass = "historial-logout";
                    break;
                  case "MODIFY":
                    colorClass = "historial-modify";
                    break;
                  case "ACTUALIZAR":
                    colorClass = "historial-modify";
                    break;
                  case "ELIMINAR":
                    colorClass = "historial-eliminar";
                    break;
                  case "CREAR":
                    colorClass = "historial-crear";
                    break;
                  default:
                    colorClass = "historial-otro";
                }

                const li = document.createElement("li");
                li.className = `historial-item ${colorClass}`;
                li.innerHTML = `<span class="historial-accion">${item.accion}:</span> <span class="historial-desc">${item.descripcion}</span> <span class="historial-fecha">(${item.fecha})</span>`;
                historyContainer.appendChild(li);
              });
            }
          }
          if (modalViewDetail && modalsContainer) {
            modalsContainer.classList.add("active");
            modalViewDetail.classList.add("active");
          }
        } else {
          ntfProcesoErroneo("Oops...", response["message"]);
        }
      }

      if (target.closest("#btnActionModify")) {
        modalModifyDetail.setAttribute("data-user-id", id);
        if (modalModifyDetail && modalsContainer) {
          modalsContainer.classList.add("active");
          modalModifyDetail.classList.add("active");
        }
      }

      // Boton de accion borrar
      if (target.closest("#btnActionDelete")) {
        document.getElementById("delete-nombre").textContent =
          row.querySelector("#nameProfile").textContent;

        document.getElementById("delete-correo").textContent =
          row.querySelector("#emailProfile").textContent;

        modalDeleteDetail.setAttribute("data-user-id", id);

        if (modalDeleteDetail && modalsContainer) {
          modalsContainer.classList.add("active");
          modalDeleteDetail.classList.add("active");
        }
      }

      // Boton borrar cuenta
      if (target.closest("#btnDeleteProfile")) {
        const id = modalDeleteDetail.getAttribute("data-user-id");

        const confirm = await ntfConfirmar(
          "¿Estas seguro de realizar esta acción?",
          "Es completamente irreversible."
        );

        if (confirm) {
          formData.append("section", "user");
          formData.append("action", "deleteAccount");
          formData.append("id_user", id);

          const response = await apiRequest(formData);

          if (response.success) {
            ntfProcesoExitoso("¡Eliminado!", response.message);
            location.reload();
          } else {
            ntfProcesoErroneo("Error", response.message);
          }
        } else {
          return;
        }

        modalDeleteDetail.classList.remove("active");
        modalsContainer.classList.remove("active");
      }

      // Boton modificar cuenta
      if (target.closest("#btnChangeProfile")) {
        e.preventDefault();
        const id = modalModifyDetail.getAttribute("data-user-id");
        formData = new FormData(document.querySelector("#modify-form"));

        const confirm = await ntfConfirmar(
          "¿Estas seguro de realizar esta acción?",
          "Es completamente irreversible."
        );

        if (confirm) {
          formData.append("section", "user");
          formData.append("action", "modify");
          formData.append("id_user", id);

          const response = await apiRequest(formData);

          if (response.success) {
            ntfProcesoExitoso("¡Modificado!", response.message);
            location.reload();
          } else {
            ntfProcesoErroneo("Error", response.message);
          }
        } else {
          return;
        }

        modalModifyDetail.classList.remove("active");
        modalsContainer.classList.remove("active");
      }

      // Evento para eliminar clave maestra
      if (target.closest("#btnActionDeleteClave")) {
        const idClave = target.closest("button").dataset.id;

        // Primero pide la clave maestra al usuario
        const claveIngresada = await ntfIngresarContrasenia();
        if (!claveIngresada) return;

        // Verifica la clave maestra antes de eliminar
        let formDataVerificar = new FormData();
        formDataVerificar.append("section", "masterkey");
        formDataVerificar.append("action", "verify");
        formDataVerificar.append("clave", claveIngresada);

        const respuestaVerificacion = await apiRequest(formDataVerificar);

        if (!respuestaVerificacion.success) {
          ntfProcesoErroneo(
            "Error",
            "Clave maestra incorrecta. No se puede eliminar."
          );
          return;
        }

        // Confirmación final antes de eliminar
        const confirm = await ntfConfirmar(
          "¿Estás seguro de eliminar esta clave maestra?",
          "Esta acción es irreversible."
        );

        if (confirm) {
          let formDataClave = new FormData();
          formDataClave.append("section", "masterkey");
          formDataClave.append("action", "delete");
          formDataClave.append("id_clave", idClave);

          const response = await apiRequest(formDataClave);

          if (response.success) {
            ntfProcesoExitoso("¡Eliminada!", response.message);
            location.reload();
          } else {
            ntfProcesoErroneo("Error", response.message);
          }
        }
        return;
      }

      // Evento para agregar nueva clave maestra
      if (target.closest("#btnActionAddClave")) {
        const claveIngresada = await ntfIngresarContrasenia(
          "Ingresa la nueva clave maestra:"
        );
        if (!claveIngresada) return;

        let formDataClave = new FormData();
        formDataClave.append("section", "masterkey");
        formDataClave.append("action", "add");
        formDataClave.append("clave", claveIngresada);

        const response = await apiRequest(formDataClave);

        if (response.success) {
          ntfProcesoExitoso("¡Agregada!", response.message);
          location.reload();
        } else {
          ntfProcesoErroneo("Error", response.message);
        }
        return;
      }

      if (target.closest(".close-btn")) {
        const modalTarget = target.closest(".modal");
        if (modalTarget) {
          modalTarget.classList.remove("active");
          modalsContainer.classList.remove("active");
        }
      }

      if (target.matches(".modals-actions")) {
        const modalTarget = target.querySelectorAll(".modal");
        if (modalTarget) {
          modalTarget.forEach((modal) => {
            modal.classList.remove("active");
          });
          modalsContainer.classList.remove("active");
        }
      }
    });
  }
}

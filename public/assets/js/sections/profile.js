import {
  actualizarDatosPerfil,
  apiRequest,
  verificarContraseniaPerfil,
} from "./../API/api.js";
import {
  ntfProcessSuccessful,
  ntfProcessError,
  ntfLoginPassword,
  ntfConfirm,
} from "./../utils/utils.js";
import {
  formatInputDNI,
  formatInputPhoneNumber,
} from "./../utils/inputFormatters.js";

// Funciones de inicialización específicas por sección
export function inicializarPerfil() {
  const photoInput = document?.getElementById("photoPerfil");
  const photoPreview = document?.querySelector(
    ".perfil-section .photo__container img"
  );
  const btnUploadPhoto = document.getElementById("btnUploadPhotoPerfil");
  const btnDeletePhoto = document.getElementById("btnDeletePhotoPerfil");
  const btnDeleteAccount = document.getElementById("btnDeleteAccount");
  const btnEditProfile = document.getElementById("btnEditProfile");
  const profilePhotoForm = document.getElementById("setting__photo");
  const profileForm = document.getElementById("details__account");
  const formInputs = profileForm?.querySelectorAll("input");
  const defaultAvatarSrc = "../img/avatar.png";

  if (photoInput && photoPreview) {
    photoInput.addEventListener("change", function () {
      const file = this.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
          photoPreview.src = e.target.result;
        };
        reader.readAsDataURL(file);
      }
    });
  }

  if (btnUploadPhoto) {
    btnUploadPhoto.addEventListener("click", async (e) => {
      e.preventDefault();
      let formData = new FormData(profilePhotoForm);
      formData.append("section", "user");
      formData.append("action", "photo/add");
      formData.append("photoPerfil", photoPerfil);
      formData.append("id", profileForm.querySelector("#id_user").value);

      const response = await actualizarDatosPerfil(formData);
      if (response["success"]) {
        ntfProcessSuccessful(
          "¡Proceso Exitoso!",
          "Se ha actualizado de forma correcta la foto de perfil!"
        );
        setTimeout(() => {
          location.reload();
        }, 1000);
      } else {
        ntfProcessError(
          "Oops...",
          "No se ha podido actualizar la foto de perfil!"
        );
      }
    });
  }

  if (btnDeletePhoto && photoPreview && photoInput) {
    btnDeletePhoto.addEventListener("click", async (e) => {
      photoPreview.src = defaultAvatarSrc;
      photoInput.value = "";

      e.preventDefault();
      let formData = new FormData(profilePhotoForm);
      formData.append("section", "user");
      formData.append("action", "photo/delete");
      formData.append("id", profileForm.querySelector("#id_user").value);

      const response = await actualizarDatosPerfil(formData);
      if (response["success"]) {
        ntfProcessSuccessful(
          "¡Proceso Exitoso!",
          "Se ha borrado de forma correcta tu foto de perfil!"
        );
        setTimeout(() => {
          location.reload();
        }, 1000);
      } else {
        ntfProcessError("Oops...", "No se ha podido borrar tu foto de perfil!");
      }
    });
  }

  if (btnEditProfile && profileForm && formInputs) {
    const cedulaInput = profileForm.querySelector("#cedulaUser");
    const telefonoInput = profileForm.querySelector("#telefonoUser");

    if (cedulaInput && telefonoInput) {
      cedulaInput.addEventListener("input", (e) => {
        e.target.value = formatInputDNI(e.target.value);
      });
      telefonoInput.addEventListener("input", (e) => {
        e.target.value = formatInputPhoneNumber(e.target.value);
      });
    }
    btnEditProfile.addEventListener("click", async (e) => {
      if (!btnEditProfile.classList.contains("guardar")) {
        const passwordAccount = await ntfLoginPassword();

        if (!passwordAccount) {
          ntfProcessError("Oops...", "Contraseña Incorrecta!");
          return;
        }

        const iduser = document.getElementById("id_user");

        let formData = new FormData();
        formData.append("action", "verify");
        formData.append("section", "user");
        formData.append("id_user", iduser.value);
        formData.append("password", passwordAccount);

        const response = await verificarContraseniaPerfil(formData);

        if (response["success"]) {
          ntfProcessSuccessful(
            response["message"],
            "Ya puedes cambiar los datos de tu cuenta..."
          );
        } else {
          ntfProcessError("Hubo un error!", response["message"]);
          return;
        }
      }
      const isEditing = !formInputs[0].disabled;
      formInputs.forEach((input) => {
        if (!input.matches("#cargoUser")) {
          input.disabled = isEditing;
        }
      });

      if (isEditing) {
        btnEditProfile.textContent = "Modificar Perfil";
        btnEditProfile.classList.remove("guardar");

        // Habilita los campos antes de enviar (ya que no se envian campos no habilitados)
        formInputs.forEach((input) => {
          if (!input.matches("#cargoUser")) {
            input.disabled = false;
          }
        });

        // Verificamos los datos ingresados (ya que el navegador no los valida si estan deshabilitados asi se habiliten desoues)
        if (!profileForm.checkValidity()) {
          profileForm.reportValidity(); // Muestra los mensajes de error del navegador
          return; // No envía si hay errores
        }

        // Crea el FormData y agrega el campo extra (ya que no envia buttons (al menos que sean submits))
        const formData = new FormData(profileForm);
        formData.append("section", "user");
        formData.append("action", "modify");

        const response = await actualizarDatosPerfil(formData);

        if (response["success"]) {
          ntfProcessSuccessful(
            "¡Proceso Exitoso!",
            "Datos Cambiados Correctamente!"
          );
        } else {
          ntfProcessError(
            "Oops...",
            "Ha ocurrido un error al tratar de actualizar los datos!"
          );
        }

        // Volvemos a deshabilita los campos despues de enviar
        formInputs.forEach((input) => {
          if (!input.matches("#cargoUser")) {
            input.disabled = true;
          }
        });
      } else {
        btnEditProfile.textContent = "Guardar Cambios";
        btnEditProfile.classList.add("guardar");
        formInputs[0].focus();
      }
    });
  }

  if (btnDeleteAccount) {
    btnDeleteAccount.addEventListener("click", async (e) => {
      const passwordAccount = await ntfLoginPassword();

      if (!passwordAccount) {
        ntfProcessError("Oops...", "Contraseña Incorrecta!");
        return;
      }

      const iduser = document.getElementById("id_user");

      let formData = new FormData();
      formData.append("action", "verify");
      formData.append("section", "user");
      formData.append("id_user", iduser.value);
      formData.append("password", passwordAccount);

      const response = await verificarContraseniaPerfil(formData);

      if (response["success"]) {
        const confirmation = await ntfConfirm(
          "Advertencia!",
          "¿Estas seguro que deseas realizar esta acción? Esta acción es completamente irreversible!"
        );
        if (confirmation) {
          formData = new FormData();
          formData.append("action", "deleteAccount");
          formData.append("section", "user");
          formData.append("id_user", iduser.value);

          const message = await apiRequest(formData);

          ntfProcessSuccessful(response["message"], "Cuenta Eliminada!");
          setTimeout(() => {
            location.reload();
          }, 1000);
        }
      } else {
        ntfProcessError("Hubo un error!", response["message"]);
        return;
      }
    });
  }
}

export function inicializarMenuPerfil() {
  const profileImgContainer = document.querySelector(".profile__img-container");
  const profileImgClickable = document.querySelector(".profile__img-clickable");
  const profileDropdownMenu = document.querySelector(".profile__dropdown-menu");
  const btnLogout = document.getElementById("btnLogout");

  if (profileImgClickable && profileDropdownMenu) {
    profileImgClickable.addEventListener("click", (event) => {
      event.stopPropagation();
      profileDropdownMenu.classList.toggle("show");
    });

    window.addEventListener("click", (event) => {
      if (
        profileDropdownMenu.classList.contains("show") &&
        !profileImgContainer.contains(event.target)
      ) {
        profileDropdownMenu.classList.remove("show");
      }
    });
  }

  if (btnLogout) {
    btnLogout.addEventListener("click", () => {
      console.log("Cerrando sesión...");
      localStorage.removeItem(LAST_SECTION_KEY);
      window.location.href = "index.php";
    });
  }
}

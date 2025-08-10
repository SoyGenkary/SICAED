import {
  formatInputDNI,
  formatInputPhoneNumber,
} from "./../utils/inputFormatters.js";
import {
  ntfProcessError,
  ntfProcessSuccessful,
} from "./../utils/utils.js";
import { apiRequest } from "./../API/api.js";

// Espera a que el contenido del DOM esté completamente cargado y parseado
document.addEventListener("DOMContentLoaded", () => {
  // Selección de elementos del DOM
  const loginContainer = document.getElementById("login-container");
  const registerContainer = document.getElementById("register-container");
  const showRegisterLink = document.getElementById("show-register");
  const showLoginLink = document.getElementById("show-login");
  const registerForm = document.getElementById("register-form");
  const loginForm = document.getElementById("login-form");

  const cedulaInput = document.getElementById("register-cedula");
  const phoneInput = document.getElementById("register-telefono");
  const emailInput = document.getElementById("register-email");
  const nameInput = document.getElementById("register-name");

  if (cedulaInput && phoneInput) {
    cedulaInput.addEventListener("input", (e) => {
      e.target.value = formatInputDNI(e.target.value);
    });
    phoneInput.addEventListener("input", (e) => {
      e.target.value = formatInputPhoneNumber(e.target.value);
    });
  }

  // Event listener para mostrar el formulario de registro
  if (showRegisterLink) {
    showRegisterLink.addEventListener("click", (e) => {
      e.preventDefault();
      if (loginContainer && registerContainer) {
        loginContainer.classList.remove("active"); // Ocultar formulario de login
        registerContainer.classList.add("active"); // Mostrar formulario de registro
      }
    });
  }

  // Event listener para mostrar el formulario de inicio de sesión
  if (showLoginLink) {
    showLoginLink.addEventListener("click", (e) => {
      e.preventDefault();
      if (loginContainer && registerContainer) {
        registerContainer.classList.remove("active"); // Ocultar formulario de registro
        loginContainer.classList.add("active"); // Mostrar formulario de login
      }
    });
  }

  if (loginForm) {
    loginForm.addEventListener("submit", async (e) => {
      const loginInput = document.getElementById("loginForm");
      const passwordInput = document.getElementById("login-password");

      // Validacion simple para el email del usuario
      if (loginInput && loginInput.value.trim() === "") {
        e.preventDefault();
        ntfProcessError("¡Ey!", "Debes ingresar email.");
        loginInput.focus();
        return;
      }

      // Validación de que las contraseñas coincidan
      if (passwordInput && passwordInput.value.trim() === "") {
        e.preventDefault();
        ntfProcessError("¡Ey!", "Debes Ingresar una contraseña");
        passwordInput.focus();
        return;
      }

      e.preventDefault();

      const formData = new FormData(loginForm);
      formData.append("section", "user");
      formData.append("action", "login");
      const response = await apiRequest(formData);

      if (!response["success"]) {
        ntfProcessError("Oops...", response["message"]);
      } else {
        location.assign("./layout.php");
      }
    });
  }

  // Event listener para el envío del formulario de registro
  if (registerForm) {
    registerForm.addEventListener("submit", async (e) => {
      const passwordInput = document.getElementById("register-password");
      const confirmPasswordInput = document.getElementById(
        "register-confirm-password"
      );
      const masterKeyInput = document.getElementById("register-master-key"); // Nuevo campo

      // Validacion simple para la Cedula del usuario
      if (cedulaInput && cedulaInput.value.trim() === "") {
        e.preventDefault();
        ntfProcessError("¡Ey!", "Debes ingresar tu documento de identidad.");
        cedulaInput.focus();
        return;
      }

      // Validacion simple para el telefono del usuario
      if (phoneInput && phoneInput.value.trim() === "") {
        e.preventDefault();
        ntfProcessError("¡Ey!", "Debes ingresar tu número de telefono.");
        phoneInput.focus();
        return;
      }

      // Validacion simple para el email del usuario
      if (emailInput && emailInput.value.trim() === "") {
        e.preventDefault();
        ntfProcessError("¡Ey!", "Debes ingresar email.");
        emailInput.focus();
        return;
      }

      // Validacion simple para el nombre del usuario
      if (nameInput && nameInput.value.trim() === "") {
        e.preventDefault();
        ntfProcessError("¡Ey!", "Debes ingresar un nombre de usuario.");
        nameInput.focus();
        return;
      }

      // Validación de que las contraseñas coincidan
      if (passwordInput && confirmPasswordInput) {
        const password = passwordInput.value;
        const confirmPassword = confirmPasswordInput.value;
        if (password !== confirmPassword) {
          e.preventDefault();
          ntfProcessError("¡Ey!", "Las contraseñas no coinciden.");
          confirmPasswordInput.focus();
          return;
        }
      }

      // Validación simple para la Clave Maestra
      if (masterKeyInput && masterKeyInput.value.trim() === "") {
        e.preventDefault();
        ntfProcessError("¡Ey!", "Por favor, ingrese la Clave Maestra.");
        masterKeyInput.focus();
        return;
      }

      e.preventDefault();

      const formData = new FormData(registerForm);
      formData.append("section", "user");
      formData.append("action", "add");
      const response = await apiRequest(formData);

      if (response["success"]) {
        registerForm.reset();
        registerContainer.classList.remove("active");
        loginContainer.classList.add("active");
        ntfProcessSuccessful("Proceso Exitoso!", response["message"]);
      } else {
        ntfProcessError("Oops...", response["message"]);
      }
    });
  }
});

import {
  formatInputDNI,
  formatInputPhoneNumber,
} from "./../utils/inputFormatters.js";
import { ntfProcessError, ntfProcessSuccessful } from "./../utils/utils.js";
import { apiRequest } from "./../API/api.js";

function eventRegisterLink(loginContainer, registerContainer) {
  const showRegisterLink = document.getElementById("show-register");

  if (showRegisterLink) {
    showRegisterLink.addEventListener("click", (e) => {
      e.preventDefault();
      if (loginContainer && registerContainer) {
        loginContainer.classList.remove("active"); // Ocultar formulario de login
        registerContainer.classList.add("active"); // Mostrar formulario de registro
      }
    });
  }
}

function validationInput(input, message) {
  if (input && input.value.trim() === "") {
    e.preventDefault();
    ntfProcessError("¡Ey!", message);
    input.focus();
  }
}

function eventFormatInput(cedulaInput, phoneInput) {
  if (cedulaInput && phoneInput) {
    cedulaInput.addEventListener("input", (e) => {
      e.target.value = formatInputDNI(e.target.value);
    });
    phoneInput.addEventListener("input", (e) => {
      e.target.value = formatInputPhoneNumber(e.target.value);
    });
  }
}

function eventSubmitRegisterForm(
  registerForm,
  loginContainer,
  registerContainer,
  cedulaInput,
  phoneInput,
  emailInput,
  nameInput
) {
  if (registerForm) {
    registerForm.addEventListener("submit", async (e) => {
      e.preventDefault();

      const passwordInput = document.getElementById("register-password");
      const confirmPasswordInput = document.getElementById(
        "register-confirm-password"
      );
      const masterKeyInput = document.getElementById("register-master-key");

      // Validacion para la Cedula del usuario
      validationInput(cedulaInput, "Debes ingresar tu documento de identidad.");

      // Validacion para el telefono del usuario
      validationInput(phoneInput, "Debes ingresar tu número de telefono.");

      // Validacion para el email del usuario
      validationInput(emailInput, "Debes ingresar tu email.");

      // Validacion para el nombre del usuario
      validationInput(nameInput, "Debes ingresar tu nombre de usuario.");

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

      // Validación para la Clave Maestra
      validationInput(masterKeyInput, "Debes ingresar la Clave Maestra.");

      // Logica de envio de formulario
      registerRequestAPI(registerForm, loginContainer, registerContainer);
    });
  }
}

async function registerRequestAPI(
  registerForm,
  loginContainer,
  registerContainer
) {
  // Llamada a la api
  const formData = new FormData(registerForm);
  formData.append("section", "user");
  formData.append("action", "add");
  const response = await apiRequest(formData);

  // Muestreo de respuesta
  if (response["success"]) {
    registerForm.reset();
    registerContainer.classList.remove("active");
    loginContainer.classList.add("active");
    ntfProcessSuccessful("Proceso Exitoso!", response["message"]);
  } else {
    ntfProcessError("Oops...", response["message"]);
  }
}

function eventsRegisterForm(registerContainer, loginContainer) {
  const cedulaInput = document.getElementById("register-cedula");
  const phoneInput = document.getElementById("register-telefono");
  const emailInput = document.getElementById("register-email");
  const nameInput = document.getElementById("register-name");
  const registerForm = document.getElementById("register-form");

  // Event listener para mostrar el formulario de registro
  eventRegisterLink(loginContainer, registerContainer);

  // Event listener para formatear automaticamente algunos inputs
  eventFormatInput(cedulaInput, phoneInput);

  // Event listener para el envío del formulario de registro
  eventSubmitRegisterForm(
    registerForm,
    loginContainer,
    registerContainer,
    cedulaInput,
    phoneInput,
    emailInput,
    nameInput
  );
}

function eventShowForm(loginContainer, registerContainer) {
  const showLoginLink = document.getElementById("show-login");

  if (showLoginLink) {
    showLoginLink.addEventListener("click", (e) => {
      e.preventDefault();

      if (loginContainer && registerContainer) {
        registerContainer.classList.remove("active"); // Ocultar formulario de registro
        loginContainer.classList.add("active"); // Mostrar formulario de login
      }
    });
  }
}

async function loginRequestAPI(loginForm) {
  const formData = new FormData(loginForm);
  formData.append("section", "user");
  formData.append("action", "login");
  const response = await apiRequest(formData);

  if (!response["success"]) {
    ntfProcessError("Oops...", response["message"]);
  } else {
    location.assign("./layout.php");
  }
}

function eventSubmitLoginForm(loginForm) {
  if (loginForm) {
    loginForm.addEventListener("submit", async (e) => {
      e.preventDefault();

      const loginInput = document.getElementById("loginForm");
      const passwordInput = document.getElementById("login-password");

      // Validacion para el email del usuario
      validationInput(loginInput, "Debes ingresar email.");

      // Validación de que las contraseñas coincidan
      validationInput(passwordInput, "Debes Ingresar una contraseña");

      // Logica de envio de formulario
      loginRequestAPI(loginForm);
    });
  }
}

function eventsLoginForm(loginContainer, registerContainer, loginForm) {
  // Event listener para mostrar el formulario de inicio de sesión
  eventShowForm(loginContainer, registerContainer);

  // Event listener para el envío del formulario de inicio de sesión
  eventSubmitLoginForm(loginForm);
}

document.addEventListener("DOMContentLoaded", () => {
  // Selección de elementos del DOM
  const loginContainer = document.getElementById("login-container");
  const registerContainer = document.getElementById("register-container");
  const loginForm = document.getElementById("login-form");

  // Eventos relacionados al registro
  eventsRegisterForm(registerContainer, loginContainer);

  // Eventos relacionados con el login
  eventsLoginForm(loginContainer, registerContainer, loginForm);
});

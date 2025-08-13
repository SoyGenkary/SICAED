import {
  formatInputDNI,
  formatInputPhoneNumber,
} from "./../utils/inputFormatters.js";
import { ntfProcessError, ntfProcessSuccessful } from "./../utils/utils.js";
import { apiRequest } from "./../API/api.js";

/**
 * Asigna el evento para mostrar el formulario de registro
 * y ocultar el de login cuando se hace clic en el enlace correspondiente.
 */
function eventRegisterLink(loginContainer, registerContainer) {
  const showRegisterLink = document.getElementById("show-register");

  if (showRegisterLink) {
    showRegisterLink.addEventListener("click", (e) => {
      e.preventDefault();
      if (loginContainer && registerContainer) {
        loginContainer.classList.remove("active"); // Oculta login
        registerContainer.classList.add("active"); // Muestra registro
      }
    });
  }
}

/**
 * Valida que un campo de entrada no esté vacío.
 * Si está vacío, muestra un mensaje de error y enfoca el campo.
 */
function validationInput(input, message) {
  if (input && input.value.trim() === "") {
    e.preventDefault();
    ntfProcessError("¡Ey!", message);
    input.focus();
  }
}

/**
 * Formatea automáticamente los campos de cédula y teléfono
 * mientras el usuario escribe.
 */
function eventFormatInput(cedulaInput, phoneInput) {
  if (cedulaInput && phoneInput) {
    cedulaInput.addEventListener("input", (e) => {
      e.target.value = formatInputDNI(e.target.value); // Formatea cédula
    });
    phoneInput.addEventListener("input", (e) => {
      e.target.value = formatInputPhoneNumber(e.target.value); // Formatea teléfono
    });
  }
}

/**
 * Configura el evento de envío del formulario de registro
 * con validaciones de datos antes de llamar a la API.
 */
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

      // Obtención de campos clave
      const passwordInput = document.getElementById("register-password");
      const confirmPasswordInput = document.getElementById(
        "register-confirm-password"
      );
      const masterKeyInput = document.getElementById("register-master-key");

      // Validaciones de campos obligatorios
      validationInput(cedulaInput, "Debes ingresar tu documento de identidad.");
      validationInput(phoneInput, "Debes ingresar tu número de telefono.");
      validationInput(emailInput, "Debes ingresar tu email.");
      validationInput(nameInput, "Debes ingresar tu nombre de usuario.");

      // Validación de coincidencia de contraseñas
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

      // Validación de Clave Maestra
      validationInput(masterKeyInput, "Debes ingresar la Clave Maestra.");

      // Envío de datos a la API
      registerRequestAPI(registerForm, loginContainer, registerContainer);
    });
  }
}

/**
 * Envía los datos del formulario de registro a la API
 * y gestiona la respuesta del servidor.
 */
async function registerRequestAPI(
  registerForm,
  loginContainer,
  registerContainer
) {
  const formData = new FormData(registerForm);
  formData.append("section", "user");
  formData.append("action", "add");

  const response = await apiRequest(formData);

  if (response["success"]) {
    registerForm.reset(); // Limpia el formulario
    registerContainer.classList.remove("active"); // Oculta registro
    loginContainer.classList.add("active"); // Muestra login
    ntfProcessSuccessful("Proceso Exitoso!", response["message"]);
  } else {
    ntfProcessError("Oops...", response["message"]);
  }
}

/**
 * Inicializa todos los eventos asociados al formulario de registro.
 */
function eventsRegisterForm(registerContainer, loginContainer) {
  const cedulaInput = document.getElementById("register-cedula");
  const phoneInput = document.getElementById("register-telefono");
  const emailInput = document.getElementById("register-email");
  const nameInput = document.getElementById("register-name");
  const registerForm = document.getElementById("register-form");

  eventRegisterLink(loginContainer, registerContainer); // Mostrar registro
  eventFormatInput(cedulaInput, phoneInput); // Formateo en vivo
  eventSubmitRegisterForm(
    registerForm,
    loginContainer,
    registerContainer,
    cedulaInput,
    phoneInput,
    emailInput,
    nameInput
  ); // Envío con validaciones
}

/**
 * Asigna el evento para mostrar el formulario de login
 * y ocultar el de registro.
 */
function eventShowForm(loginContainer, registerContainer) {
  const showLoginLink = document.getElementById("show-login");

  if (showLoginLink) {
    showLoginLink.addEventListener("click", (e) => {
      e.preventDefault();
      if (loginContainer && registerContainer) {
        registerContainer.classList.remove("active"); // Oculta registro
        loginContainer.classList.add("active"); // Muestra login
      }
    });
  }
}

/**
 * Envía los datos del formulario de login a la API
 * y redirige al usuario si las credenciales son correctas.
 */
async function loginRequestAPI(loginForm) {
  const formData = new FormData(loginForm);
  formData.append("section", "user");
  formData.append("action", "login");

  const response = await apiRequest(formData);

  if (!response["success"]) {
    ntfProcessError("Oops...", response["message"]);
  } else {
    location.assign("./layout.php"); // Redirección en caso de éxito
  }
}

/**
 * Configura el evento de envío del formulario de login
 * con validaciones básicas de campos.
 */
function eventSubmitLoginForm(loginForm) {
  if (loginForm) {
    loginForm.addEventListener("submit", async (e) => {
      e.preventDefault();

      const loginInput = document.getElementById("loginForm");
      const passwordInput = document.getElementById("login-password");

      validationInput(loginInput, "Debes ingresar email.");
      validationInput(passwordInput, "Debes Ingresar una contraseña");

      loginRequestAPI(loginForm); // Envío a la API
    });
  }
}

/**
 * Inicializa todos los eventos asociados al formulario de login.
 */
function eventsLoginForm(loginContainer, registerContainer, loginForm) {
  eventShowForm(loginContainer, registerContainer); // Mostrar login
  eventSubmitLoginForm(loginForm); // Envío con validaciones
}

// Ejecución al cargar el DOM
document.addEventListener("DOMContentLoaded", () => {
  const loginContainer = document.getElementById("login-container");
  const registerContainer = document.getElementById("register-container");
  const loginForm = document.getElementById("login-form");

  eventsRegisterForm(registerContainer, loginContainer); // Eventos de registro
  eventsLoginForm(loginContainer, registerContainer, loginForm); // Eventos de login
});

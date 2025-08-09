<?php
// Usamos rutas absolutas para evitar problemas de inclusión
// y asegurarnos de que los archivos se carguen correctamente.
// include_once $_SERVER['DOCUMENT_ROOT'] . '/SICAED/config/database.php';

// if ($_SERVER['REQUEST_METHOD'] === 'POST'){
//   include_once $_SERVER['DOCUMENT_ROOT'] . '/SICAED/app/Models/User.php';
//   if (isset($_POST['submitLoginForm'])) {
//     User::login($conn, $_POST);
//   } elseif (isset($_POST['submitRegisterForm'])) {
//     include_once $_SERVER['DOCUMENT_ROOT'] . '/SICAED/app/Controllers/UserController.php';
//     User::crear($conn, $_POST);
//   }
// }

?>

<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="./assets/css/sections/loginForm.css" />
    <script src="./assets/js/pages/loginForm.js" defer type="module"></script>
    <script src="./assets/framework/sweetalert/sweetalert2.all.min.js" defer></script>
    <title>SICAED - Acceso al Sistema</title>
  </head>

  <body>
    <div class="container">
      <div class="form-container active" id="login-container">
        <div class="header">
          <div class="header__logo">
            <img src="./assets/img/logo/logoBlack.png" alt="Logo del Ministerio" />
          </div>
          <h1>Iniciar Sesión</h1>
          <h2>Sistema de Administración</h2>
        </div>
        <form id="login-form" method="POST">
          <div class="form__group">
            <label for="login-user">Correo Electrónico</label>
            <div class="input__icon-wrapper">
              <input
                type="email"
                name="login-user"
                id="login-user"
                required
                autocomplete="email"
              />
              <div class="icon__input">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  height="24px"
                  viewBox="0 -960 960 960"
                  width="24px"
                >
                  <path
                    d="M480-320q75 0 127.5-52.5T660-500q0-75-52.5-127.5T480-680q-75 0-127.5 52.5T300-500q0 75 52.5 127.5T480-320Zm0-80q-42 0-71-29t-29-71q0-42 29-71t71-29q42 0 71 29t29 71q0 42-29 71t-71 29ZM160-160v-112q0-34 17.5-62.5T224-378q62-31 126-46.5T480-440q66 0 130 15.5T736-378q29 15 46.5 43.5T800-272v112H160Zm80-80h480v-32q0-11-5.5-20T700-306q-54-27-109-40.5T480-360q-56 0-111 13.5T260-306q-9 5-14.5 14t-5.5 20v32Zm240-320q33 0 56.5-23.5T560-640q0-33-23.5-56.5T480-720q-33 0-56.5 23.5T400-640q0 33 23.5 56.5T480-480Zm0-80Z"
                  />
                </svg>
              </div>
            </div>
          </div>
          <div class="form__group">
            <label for="login-password">Contraseña</label>
            <div class="input__icon-wrapper">
              <input
                type="password"
                name="login-password"
                id="login-password"
                required
                autocomplete="current-password"
              />
              <div class="icon__input">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  height="24px"
                  viewBox="0 -960 960 960"
                  width="24px"
                >
                  <path
                    d="M480-80q-139-35-229.5-159.5T160-516v-244l320-120 320 120v244q0 152-90.5 276.5T480-80Zm0-84q104-33 172-132t68-220v-189l-240-90-240 90v189q0 121 68 220t172 132Zm0-316q17 0 28.5-11.5T520-520q0-17-11.5-28.5T480-560q-17 0-28.5 11.5T440-520q0 17 11.5 28.5T480-480Z"
                  />
                </svg>
              </div>
            </div>
          </div>
          <button type="submit" id="submitLoginForm" name='submitLoginForm'>Iniciar Sesión</button>
          <p class="toggle-form-link">
            ¿No tienes una cuenta?
            <a href="#" id="show-register">Regístrate aquí</a>
          </p>
        </form>
      </div>

      <div class="form-container" id="register-container">
        <div class="header">
          <div class="header__logo">
            <img src="./assets/img/logo/logoBlack.png" alt="Logo del Ministerio" />
          </div>
          <h1>Crear Cuenta</h1>
          <h2>Sistema de Administración</h2>
        </div>
        <form id="register-form" method="POST">
          <div class="form__group">
            <label for="register-name">Nombre Completo</label>
            <div class="input__icon-wrapper">
              <input
                type="text"
                name="register-name"
                id="register-name"
                required
                autocomplete="name"
              />
              <div class="icon__input">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  height="24px"
                  viewBox="0 -960 960 960"
                  width="24px"
                >
                  <path
                    d="M480-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM160-160v-112q0-34 17.5-62.5T224-378q62-31 126-46.5T480-440q66 0 130 15.5T736-378q29 15 46.5 43.5T800-272v112H160Z"
                  />
                </svg>
              </div>
            </div>
          </div>
          <div class="form__group">
            <label for="register-cedula">Cédula</label>
            <div class="input__icon-wrapper">
              <input
                type="text"
                name="register-cedula"
                id="register-cedula"
              />
              <div class="icon__input">
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF"><path d="M560-440h200v-80H560v80Zm0-120h200v-80H560v80ZM200-320h320v-22q0-45-44-71.5T360-440q-72 0-116 26.5T200-342v22Zm160-160q33 0 56.5-23.5T440-560q0-33-23.5-56.5T360-640q-33 0-56.5 23.5T280-560q0 33 23.5 56.5T360-480ZM160-160q-33 0-56.5-23.5T80-240v-480q0-33 23.5-56.5T160-800h640q33 0 56.5 23.5T880-720v480q0 33-23.5 56.5T800-160H160Zm0-80h640v-480H160v480Zm0 0v-480 480Z"/></svg>
              </div>
            </div>
          </div>
          <div class="form__group">
            <label for="register-telefono">Telefono</label>
            <div class="input__icon-wrapper">
              <input
                type="text"
                name="register-telefono"
                id="register-telefono"
                required
              />
              <div class="icon__input">
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF"><path d="M280-40q-33 0-56.5-23.5T200-120v-720q0-33 23.5-56.5T280-920h400q33 0 56.5 23.5T760-840v124q18 7 29 22t11 34v80q0 19-11 34t-29 22v404q0 33-23.5 56.5T680-40H280Zm0-80h400v-720H280v720Zm0 0v-720 720Zm200-40q17 0 28.5-11.5T520-200q0-17-11.5-28.5T480-240q-17 0-28.5 11.5T440-200q0 17 11.5 28.5T480-160Z"/></svg>
              </div>
            </div>
          </div>
          <div class="form__group">
            <label for="register-email">Correo Electrónico</label>
            <div class="input__icon-wrapper">
              <input
                type="email"
                name="register-email"
                id="register-email"
                required
                autocomplete="email"
              />
              <div class="icon__input">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  height="24px"
                  viewBox="0 -960 960 960"
                  width="24px"
                >
                  <path
                    d="M160-160q-33 0-56.5-23.5T80-240v-480q0-33 23.5-56.5T160-800h640q33 0 56.5 23.5T880-720v480q0 33-23.5 56.5T800-160H160Zm320-280L160-640v400h640v-400L480-440Zm0-80 320-200H160l320 200ZM160-640v-80 480-400Z"
                  />
                </svg>
              </div>
            </div>
          </div>
          <div class="form__group">
            <label for="register-password">Contraseña</label>
            <div class="input__icon-wrapper">
              <input
                type="password"
                name="register-password"
                id="register-password"
                required
                autocomplete="new-password"
              />
              <div class="icon__input">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  height="24px"
                  viewBox="0 -960 960 960"
                  width="24px"
                >
                  <path
                    d="M480-80q-139-35-229.5-159.5T160-516v-244l320-120 320 120v244q0 152-90.5 276.5T480-80Zm0-84q104-33 172-132t68-220v-189l-240-90-240 90v189q0 121 68 220t172 132Zm0-316q17 0 28.5-11.5T520-520q0-17-11.5-28.5T480-560q-17 0-28.5 11.5T440-520q0 17 11.5 28.5T480-480Z"
                  />
                </svg>
              </div>
            </div>
          </div>
          <div class="form__group">
            <label for="register-confirm-password">Confirmar Contraseña</label>
            <div class="input__icon-wrapper">
              <input
                type="password"
                name="register-confirm-password"
                id="register-confirm-password"
                required
                autocomplete="new-password"
              />
              <div class="icon__input">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  height="24px"
                  viewBox="0 -960 960 960"
                  width="24px"
                >
                  <path
                    d="M480-80q-139-35-229.5-159.5T160-516v-244l320-120 320 120v244q0 152-90.5 276.5T480-80Zm0-84q104-33 172-132t68-220v-189l-240-90-240 90v189q0 121 68 220t172 132Zm0-316q17 0 28.5-11.5T520-520q0-17-11.5-28.5T480-560q-17 0-28.5 11.5T440-520q0 17 11.5 28.5T480-480Z"
                  />
                </svg>
              </div>
            </div>
          </div>
          <div class="form__group">
            <label for="register-master-key">Clave Maestra</label>
            <div class="input__icon-wrapper">
              <input
                type="password"
                name="register-master-key"
                id="register-master-key"
                required
              />
              <div class="icon__input">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  height="24px"
                  viewBox="0 -960 960 960"
                  width="24px"
                >
                  <path
                    d="M320-80q-83 0-141.5-58.5T120-280v-400q0-83 58.5-141.5T320-880h40v-80h80v80h160v-80h80v80h40q83 0 141.5 58.5T840-680v400q0 83-58.5 141.5T640-80H320Zm0-80h320v-400H320v400Zm80-120q33 0 56.5-23.5T480-360q0-33-23.5-56.5T400-440q-33 0-56.5 23.5T320-360q0 33 23.5 56.5T400-280Zm-80 80v-400-80 80 400Z"
                  />
                </svg>
              </div>
            </div>
          </div>
          <button type="submit" id="submitRegisterForm" name="submitRegisterForm">Crear Cuenta</button>
          <p class="toggle-form-link">
            ¿Ya tienes una cuenta?
            <a href="#" id="show-login">Inicia sesión aquí</a>
          </p>
        </form>
      </div>
    </div>
  </body>
</html>

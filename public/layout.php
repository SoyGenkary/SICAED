<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/SICAED/config/database.php';


// Inicialziamos la informacion de sesion
session_start();

// Verificamos que si este logeado
if (!isset($_SESSION['id'])) {
  header("Location: ./index.php");
  exit();
  
} else {
  $sesion = $_SESSION['id'];
  $rol = $_SESSION['rol'];
  $sqluser = "SELECT ruta_img FROM usuarios WHERE id_usuario = '$sesion'";

  $resultados = $conn->query($sqluser);
  $resultados = $resultados->fetch_assoc();
}

?>

<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="./assets/css/fonts.css" />
    <link rel="stylesheet" href="./assets/css/main.css" />
    <link rel="stylesheet" href="./assets/css/sections/principal.css" />
    <link rel="stylesheet" href="./assets/css/sections/browser.css" />
    <link rel="stylesheet" href="./assets/css/sections/administrator.css" />
    <link rel="stylesheet" href="./assets/css/sections/status.css" />
    <link rel="stylesheet" href="./assets/css/sections/profile.css" />
    <link rel="stylesheet" href="./assets/css/sections/detailEntity.css" />
    <link rel="stylesheet" href="./assets/css/sections/management.css">
    <link rel="stylesheet" href="./assets/css/animations.css" />
    <link rel="stylesheet" href="./assets/css/medias.css" />
    <script src="./assets/framework/sweetalert/sweetalert2.all.min.js" defer></script>
    <script src="/SICAED/public/assets/framework/chart/chart.min.js" defer></script>
    <script src="./assets/js/app.js" type="module" defer></script>
    <title>SICAED - Dashboard</title>
  </head>
  <body>
    <nav class="nav">
      <div class="logo__nav">
        <img
          class="logo-img"
          src="./assets/img/logo/logoWhite.png"
          alt="Logo Unexca/Mppe"
          title="UnexMPPE"
          loading="lazy"
        />
      </div>
      <div class="nav__items">
        <?php if($rol === 'Usuario' || $rol === 'Administrador' || $rol === 'Jefe') : ?>
          <div class="main__menu">
            <span class="leyenda">MENÚ PRINCIPAL</span>
            <ul class="list__options">
              <?php if($rol !== 'Usuario') : ?>
                <li class="option active">
                  <a href="#" data-section="principal">
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      height="24px"
                      viewBox="0 -960 960 960"
                      width="24px"
                    >
                      <path
                        d="M160-120v-480l320-240 320 240v480H560v-280H400v280H160Z"
                      />
                    </svg>
                    <span>Principal</span>
                  </a>
                </li>
                <li class="option">
                  <a href="#" data-section="browser">
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      height="27px"
                      viewBox="0 -960 960 960"
                      width="27px"
                    >
                      <path
                        d="M784-120 532-372q-30 24-69 38t-83 14q-109 0-184.5-75.5T120-580q0-109 75.5-184.5T380-840q109 0 184.5 75.5T640-580q0 44-14 83t-38 69l252 252-56 56ZM380-400q75 0 127.5-52.5T560-580q0-75-52.5-127.5T380-760q-75 0-127.5 52.5T200-580q0 75 52.5 127.5T380-400Z"
                      />
                    </svg>
                    <span>Buscador</span>
                  </a>
                </li>
                <li class="option">
                  <a href="#" data-section="administrator">
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      height="24px"
                      viewBox="0 -960 960 960"
                      width="24px"
                    >
                      <path
                        d="M520-600v-240h320v240H520ZM120-440v-400h320v400H120Zm400 320v-400h320v400H520Zm-400 0v-240h320v240H120Z"
                      />
                    </svg>
                    <span>Administrador</span>
                  </a>
                </li>
              <?php endif; ?>
              <li class="option">
                <a href="#" data-section="status">
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    height="24px"
                    viewBox="0 -960 960 960"
                    width="24px"
                    fill="#000000"
                  >
                    <path
                      d="M80-600v-160q0-33 23.5-56.5T160-840h640q33 0 56.5 23.5T880-760v160h-80v-160H160v160H80Zm80 360q-33 0-56.5-23.5T80-320v-200h80v200h640v-200h80v200q0 33-23.5 56.5T800-240H160ZM40-120v-80h880v80H40Zm440-420ZM80-520v-80h240q11 0 21 6t15 16l47 93 123-215q5-9 14-14.5t20-5.5q11 0 21 5.5t15 16.5l49 98h235v80H620q-11 0-21-5.5T584-542l-26-53-123 215q-5 10-15 15t-21 5q-11 0-20.5-6T364-382l-69-138H80Z"
                    />
                  </svg>
                  <span>Estado</span>
                </a>
              </li>
            </ul>
          </div>
          <div class="setting__menu">
            <span class="leyenda">CONFIGURACIÓN</span>
            <ul class="list__options">
              <li class="option">
                <a href="#" data-section="profile">
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    height="24px"
                    viewBox="0 -960 960 960"
                    width="24px"
                  >
                    <path
                      d="M234-276q51-39 114-61.5T480-360q69 0 132 22.5T726-276q35-41 54.5-93T800-480q0-133-93.5-226.5T480-800q-133 0-226.5 93.5T160-480q0 59 19.5 111t54.5 93Zm246-164q-59 0-99.5-40.5T340-580q0-59 40.5-99.5T480-720q59 0 99.5 40.5T620-580q0 59-40.5 99.5T480-440Zm0 360q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Z"
                    />
                  </svg>
                  <span>Perfil</span>
                </a>
              </li>
              <?php if($rol === 'Jefe') : ?>
                <li class="option">
                  <a href="#" data-section="management">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px"><path d="M400-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM80-160v-112q0-33 17-62t47-44q51-26 115-44t141-18h14q6 0 12 2-8 18-13.5 37.5T404-360h-4q-71 0-127.5 18T180-306q-9 5-14.5 14t-5.5 20v32h252q6 21 16 41.5t22 38.5H80Zm560 40-12-60q-12-5-22.5-10.5T584-204l-58 18-40-68 46-40q-2-14-2-26t2-26l-46-40 40-68 58 18q11-8 21.5-13.5T628-460l12-60h80l12 60q12 5 22.5 11t21.5 15l58-20 40 70-46 40q2 12 2 25t-2 25l46 40-40 68-58-18q-11 8-21.5 13.5T732-180l-12 60h-80Zm40-120q33 0 56.5-23.5T760-320q0-33-23.5-56.5T680-400q-33 0-56.5 23.5T600-320q0 33 23.5 56.5T680-240ZM400-560q33 0 56.5-23.5T480-640q0-33-23.5-56.5T400-720q-33 0-56.5 23.5T320-640q0 33 23.5 56.5T400-560Zm0-80Zm12 400Z"/></svg>
                    <span>Gestión</span>
                  </a>
                </li>
              <?php endif; ?>
            </ul>
          </div>
        <?php endif ?>
      </div>
      <div class="closeNavBar">
        <button id="btnCloseNavbar">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            height="24px"
            viewBox="0 -960 960 960"
            width="24px"
            fill="#FFFFFF"
          >
            <path d="m321-80-71-71 329-329-329-329 71-71 400 400L321-80Z" />
          </svg>
        </button>
      </div>
    </nav>
    <main class="main">
      <header class="header">
        <div class="header__onLine">
          <div class="online__Container">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              height="24px"
              viewBox="0 -960 960 960"
              width="24px"
              fill="#000000"
            >
              <path
                d="M40-120v-80h880v80H40Zm120-120q-33 0-56.5-23.5T80-320v-440q0-33 23.5-56.5T160-840h640q33 0 56.5 23.5T880-760v440q0 33-23.5 56.5T800-240H160Zm0-80h640v-440H160v440Zm0 0v-440 440Z"
              />
            </svg>
            <div class="circle-status"></div>
            <p>Estado: <span class="statusOnLine">No Identificado</span></p>
          </div>
        </div>
        <div class="profile__header">
          <div class="profile__img-container">
            <img
              src="<?= !empty($resultados['ruta_img']) ? $resultados['ruta_img'] : './assets/img/icons/avatar.png' ?>"
              alt="foto de perfil"
              class="profile__img-clickable"
            />
            <div class="profile__dropdown-menu">
              <a href="./logout.php" id="btnLogout">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  height="20px"
                  viewBox="0 -960 960 960"
                  width="20px"
                  fill="currentColor"
                >
                  <path
                    d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h280v80H200v560h280v80H200Zm440-160-55-58 102-102H360v-80h327L585-622l55-58 200 200-200 200Z"
                  />
                </svg>
                <span>Cerrar Sesión</span>
              </a>
            </div>
          </div>
        </div>
      </header>
      <div class="container" id="content-container"></div>
      <div class="container" id="detailResult-container"></div>
    </main>
  </body>
</html>

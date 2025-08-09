<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/SICAED/config/database.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/SICAED/app/Services/FileManager.php';

if (session_status() == PHP_SESSION_NONE){
  session_start();
}

$sesion = $_SESSION['id'];

// Extraemos los datos del usuario para mostrarlos
$sqluser = "SELECT * FROM usuarios WHERE id_usuario = '$sesion'";
$resultados = $conn->query($sqluser);

if($resultados){
  $resultados = $resultados->fetch_assoc();
}

// Extraemos los datos del registro de auditorias
$sqlAuditorias = "SELECT * FROM registroauditorias WHERE id_usuario = '$sesion' ORDER BY id_registro DESC LIMIT 10";
$resultadosAuditorias = $conn->query($sqlAuditorias);

?>

<section class="perfil-section">
  <h1 class="principal__title">Ajustes de Perfil y Actividad</h1>
  <div class="settings">
    <div class="setting__wrapper">
      <h2 class="header__title">Tu Foto</h2>
      <form class="setting__photo" method="POST" enctype="multipart/form-data" id="setting__photo">
        <div class="photo__container">
          <img src="<?= !empty($resultados['ruta_img']) ? $resultados['ruta_img'] : './assets/img/icons/avatar.png' ?>" alt="Foto de Perfil" />
        </div>
        <div class="setting__description">
          <strong
            >Se recomienda fotos ligeras por debajo de 10MB y de por lo menos de
            400px por 400px.</strong
          >
          <small
            >*La foto de perfil se verá en todos los apartados relacionados a
            ti. Desde la preview de tu perfil en la esquina superior derecha
            hasta en el registro de auditoria.</small
          >
          <div class="file__input">
            <input
              type="file"
              name="photoPerfil"
              id="photoPerfil"
              accept="image/*"
            />
            <button id="btnUploadPhotoPerfil" name="btnUploadPhotoPerfil" value="subir" type="submit">
              <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF"><path d="M317-114q-29 0-48.5-19.5T249-182q0-28 19.5-48t48.5-20h326q29 0 48.5 20t19.5 48q0 29-19.5 48.5T643-114H317Zm163-216q-28 0-48-19.5T412-398v-231l-30 30q-19 20-47 20t-48-20q-19-19-19-47t19-48l145-144q9-9 22-14.5t26-5.5q13 0 26 5.5t22 14.5l145 144q19 20 19 48t-19 47q-20 20-48 20t-47-20l-30-30v231q0 29-20 48.5T480-330Z"/></svg>
            <button id="btnDeletePhotoPerfil" name="accion" value="eliminar" type="submit">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                height="24px"
                viewBox="0 -960 960 960"
                width="24px"
                fill="#FFFFFF"
              >
                <path
                  d="M267-74q-57 0-96.5-39.5T131-210v-501q-28 0-47.5-19.5t-19.5-48Q64-807 83.5-827t47.5-20h205q0-27 18.8-46.5T402-913h154q28.4 0 47.88 19.36 19.47 19.36 19.47 46.64h205.61q29.04 0 48.54 20.2T897-779q0 29-19.5 48.5T829-711v501q0 57-39.5 96.5T693-74H267Zm426-637H267v501h426v-501ZM391-279q22.6 0 39.3-16.7Q447-312.4 447-335v-251q0-22.6-16.7-39.3Q413.6-642 391-642q-22.6 0-39.8 16.7Q334-608.6 334-586v251q0 22.6 17.2 39.3Q368.4-279 391-279Zm180 0q22.6 0 39.3-16.7Q627-312.4 627-335v-251q0-22.6-16.7-39.3Q593.6-642 571-642q-22.6 0-39.8 16.7Q514-608.6 514-586v251q0 22.6 17.2 39.3Q548.4-279 571-279ZM267-711v501-501Z"
                />
              </svg>
            </button>
          </div>
        </div>
      </form>
      <h2 class="header__title">Datos Personales</h2>
      <div class="setting__perfil-data">
        <div class="setting__description">
          <form id="details__account" method="POST">
          <input type="text" name="id_user" id="id_user" disabled value="<?= $resultados['id_usuario'] ?>" hidden>  
          <div class="group__form">
              <div class="details__form-group">
                <label for="nombreUser"><span>Nombre</span></label>
                <input
                  type="text"
                  class="inputStyle"
                  name="nombreUser"
                  id="nombreUser"
                  placeholder="Nombre del usuario"
                  value="<?= $resultados['nombre'] ?>"
                  required
                  disabled
                />
              </div>
              <div class="details__form-group">
                <label for="emailUser"><span>Email</span></label>
                <input
                  type="email"
                  class="inputStyle"
                  name="emailUser"
                  id="emailUser"
                  placeholder="Email del usuario"
                  value="<?= $resultados['email'] ?>"
                  required
                  disabled
                />
              </div>
            </div>
            <div class="group__form">
              <div class="details__form-group">
                <label for="cedulaUser"><span>Cédula</span></label>
                <input
                  type="text"
                  class="inputStyle"
                  name="cedulaUser"
                  id="cedulaUser"
                  placeholder="Cédula"
                  value="<?= $resultados['cedula'] ?>"
                  minlength="10"
                  maxlength="10"
                  required
                  disabled
                />
              </div>
              <div class="details__form-group">
                <label for="telefonoUser"
                  ><span>Número de Teléfono</span></label
                >
                <input
                  type="text"
                  class="inputStyle"
                  name="telefonoUser"
                  id="telefonoUser"
                  placeholder="Número de teléfono"
                  value="<?= $resultados['telefono'] ?>"
                  minlength="12"
                  maxlength="12"
                  required
                  disabled
                />
              </div>
              <div class="details__form-group">
                <label for="cargoUser"><span>Cargo</span></label>
                <input
                  type="text"
                  class="inputStyle"
                  name="cargoUser"
                  id="cargoUser"
                  placeholder="Cargo"
                  value="<?= $resultados['rol'] ?>"
                  disabled
                />
              </div>
            </div>
            <div class="actions-container">
              <button type="button" id="btnEditProfile" class="btnEditProfile" name="btnEditProfile">
                Modificar Perfil
              </button>
              <button type="button" id="btnDeleteAccount" class="btnDeleteAccount" name="btnDeleteAccount">
                Borrar Cuenta
              </button>
            </div>
          </form>
        </div>
      </div>
      <h2 class="header__title">Registro de Actividad</h2>
      <br>
      <small style="color: red;">*Solo se mostraran las ultimas 10 acciones registradas por temas de rendimiento.</small>
      <div class="audit-log-container">
        <ul class="audit-log-list">
          <?php while($row = $resultadosAuditorias->fetch_assoc()): ?>

            <!-- REGISTROS RELACIONADOS A USUARIO - INICIO -->
            <?php if($row['accion'] === 'USUARIO:LOGIN'):?>
              <li class="audit-log-item audit-log-item--inicio_sesion">
                <div class="audit-log-item__icon">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                    <path
                      d="M480-120v-80h280v-560H480v-80h280q33 0 56.5 23.5T840-760v560q0 33-23.5 56.5T760-120H480Zm-80-160-55-58 102-102H120v-80h327L345-622l55-58 200 200-200 200Z"
                    />
                  </svg>
                </div>
                <div class="audit-log-item__content">
                  <span class="audit-log-item__action"
                    >Inicio de sesión:</span
                  >
                  <span class="audit-log-item__details"
                    ><?= $row['descripcion'] ?></span
                  >
                  <span class="audit-log-item__meta"
                    >Por: <?= $resultados['nombre'] ?> - <?= $resultados['ultimo_login'] ?></span
                  >
                </div>
              </li>
            <?php elseif($row['accion'] === 'USUARIO:LOGOUT'):?>
              <li class="audit-log-item audit-log-item--inicio_sesion">
                <div class="audit-log-item__icon">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                    <path
                      d="M480-120v-80h280v-560H480v-80h280q33 0 56.5 23.5T840-760v560q0 33-23.5 56.5T760-120H480Zm-80-160-55-58 102-102H120v-80h327L345-622l55-58 200 200-200 200Z"
                    />
                  </svg>
                </div>
                <div class="audit-log-item__content">
                  <span class="audit-log-item__action"
                    >Cerrado de sesión:</span
                  >
                  <span class="audit-log-item__details"
                    >Ha cerrado sesión correctamente.</span
                  >
                  <span class="audit-log-item__meta"
                    >Por: <?= $resultados['nombre'] ?> - <?= $row['fecha'] ?></span
                  >
                </div>
              </li>
            <?php elseif($row['accion'] === 'USUARIO:MODIFY'):?>
              <li class="audit-log-item audit-log-item--modificacion">
                <div class="audit-log-item__icon">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                    <path
                      d="M200-200h57l391-391-57-57-391 391v57Zm-80 80v-170l528-527q12-11 26.5-17t30.5-6q16 0 31 6t26 18l55 56q12 11 17.5 26t5.5 30q0 16-5.5 30.5T817-647L290-120H120Zm640-584-56-56 56 56Zm-141 85-28-29 57 57-29-28Z"
                    />
                  </svg>
                </div>
                <div class="audit-log-item__content">
                  <span class="audit-log-item__action"
                    >Datos de la cuenta actualizados:</span
                  >
                  <span class="audit-log-item__details"
                    ><?= $row['descripcion'] ?></span
                  >
                  <span class="audit-log-item__meta"
                    >Por: <?= $resultados['nombre'] ?> - <?= $row['fecha'] ?></span
                  >
                </div>
              </li>
            <?php elseif($row['accion'] === 'USUARIO:ELIMINAR'):?>
              <li class="audit-log-item audit-log-item--eliminacion">
                <div class="audit-log-item__icon">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                    <path
                      d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z"
                    />
                  </svg>
                </div>
                <div class="audit-log-item__content">
                  <span class="audit-log-item__action"
                    >Datos vehiculares eliminados:</span
                  >
                  <span class="audit-log-item__details"
                    ><?= $row['descripcion'] ?></span
                  >
                  <span class="audit-log-item__meta"
                    >Por: <?= $resultados['nombre'] ?> - <?= $row['fecha'] ?></span
                  >
                </div>
              </li>
            <!-- REGISTROS RELACIONADOS A USUARIO - FINAL -->

            <!-- REGISTROS RELACIONADOS A VEHICULOS - INICIO -->
            <?php elseif($row['accion'] === 'VEHICULO:CREAR'):?>
              <li class="audit-log-item audit-log-item--creacion">
                <div class="audit-log-item__icon">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                    <path
                      d="M440-440H200v-80h240v-240h80v240h240v80H520v240h-80v-240Z"
                    />
                  </svg>
                </div>
                <div class="audit-log-item__content">
                  <span class="audit-log-item__action"
                    >Nuevo vehículo agregado:</span
                  >
                  <span class="audit-log-item__details"
                    ><?= $row['descripcion'] ?></span
                  >
                  <span class="audit-log-item__meta"
                    >Por: <?= $resultados['nombre'] ?> - <?= $row['fecha'] ?></span
                  >
                </div>
              </li>
            <?php elseif($row['accion'] === 'VEHICULO:ELIMINAR'):?>
              <li class="audit-log-item audit-log-item--eliminacion">
                <div class="audit-log-item__icon">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                    <path
                      d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z"
                    />
                  </svg>
                </div>
                <div class="audit-log-item__content">
                  <span class="audit-log-item__action"
                    >Datos vehiculares eliminados:</span
                  >
                  <span class="audit-log-item__details"
                    ><?= $row['descripcion'] ?></span
                  >
                  <span class="audit-log-item__meta"
                    >Por: <?= $resultados['nombre'] ?> - <?= $row['fecha'] ?></span
                  >
                </div>
              </li>
            <?php elseif($row['accion'] === 'VEHICULO:MODIFY'):?>
              <li class="audit-log-item audit-log-item--modificacion">
                <div class="audit-log-item__icon">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                    <path
                      d="M200-200h57l391-391-57-57-391 391v57Zm-80 80v-170l528-527q12-11 26.5-17t30.5-6q16 0 31 6t26 18l55 56q12 11 17.5 26t5.5 30q0 16-5.5 30.5T817-647L290-120H120Zm640-584-56-56 56 56Zm-141 85-28-29 57 57-29-28Z"
                    />
                  </svg>
                </div>
                <div class="audit-log-item__content">
                  <span class="audit-log-item__action"
                    >Datos vehiculares actualizados:</span
                  >
                  <span class="audit-log-item__details"
                    ><?= $row['descripcion'] ?></span
                  >
                  <span class="audit-log-item__meta"
                    >Por: <?= $resultados['nombre'] ?> - <?= $row['fecha'] ?></span
                  >
                </div>
              </li>
            <!-- REGISTROS RELACIONADOS A VEHICULOS - FINAL -->

            <!-- REGISTROS RELACIONADOS A PERSONAL - INICIO -->
            <?php elseif($row['accion'] === 'PERSONAL:CREAR'):?>
              <li class="audit-log-item audit-log-item--creacion">
                <div class="audit-log-item__icon">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                    <path
                      d="M440-440H200v-80h240v-240h80v240h240v80H520v240h-80v-240Z"
                    />
                  </svg>
                </div>
                <div class="audit-log-item__content">
                  <span class="audit-log-item__action"
                    >Nuevo personal agregado:</span
                  >
                  <span class="audit-log-item__details"
                    ><?= $row['descripcion'] ?></span
                  >
                  <span class="audit-log-item__meta"
                    >Por: <?= $resultados['nombre'] ?> - <?= $row['fecha'] ?></span
                  >
                </div>
              </li>
            <?php elseif($row['accion'] === 'PERSONAL:ELIMINAR'):?>
              <li class="audit-log-item audit-log-item--eliminacion">
                <div class="audit-log-item__icon">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                    <path
                      d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z"
                    />
                  </svg>
                </div>
                <div class="audit-log-item__content">
                  <span class="audit-log-item__action"
                    >Datos de personal eliminados:</span
                  >
                  <span class="audit-log-item__details"
                    ><?= $row['descripcion'] ?></span
                  >
                  <span class="audit-log-item__meta"
                    >Por: <?= $resultados['nombre'] ?> - <?= $row['fecha'] ?></span
                  >
                </div>
              </li>
            <?php elseif($row['accion'] === 'PERSONAL:ACTUALIZAR'):?>
              <li class="audit-log-item audit-log-item--modificacion">
                <div class="audit-log-item__icon">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                    <path
                      d="M200-200h57l391-391-57-57-391 391v57Zm-80 80v-170l528-527q12-11 26.5-17t30.5-6q16 0 31 6t26 18l55 56q12 11 17.5 26t5.5 30q0 16-5.5 30.5T817-647L290-120H120Zm640-584-56-56 56 56Zm-141 85-28-29 57 57-29-28Z"
                    />
                  </svg>
                </div>
                <div class="audit-log-item__content">
                  <span class="audit-log-item__action"
                    >Datos de personal actualizados:</span
                  >
                  <span class="audit-log-item__details"
                    ><?= $row['descripcion'] ?></span
                  >
                  <span class="audit-log-item__meta"
                    >Por: <?= $resultados['nombre'] ?> - <?= $row['fecha'] ?></span
                  >
                </div>
              </li>
            <!-- REGISTROS RELACIONADOS A PERSONAL - FINAL -->

            <!-- REGISTROS RELACIONADOS A MANTENIMIENTO - INICIO -->
            <?php elseif($row['accion'] === 'MANTENIMIENTO:CREAR'):?>
              <li class="audit-log-item audit-log-item--creacion">
                <div class="audit-log-item__icon">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                    <path
                      d="M440-440H200v-80h240v-240h80v240h240v80H520v240h-80v-240Z"
                    />
                  </svg>
                </div>
                <div class="audit-log-item__content">
                  <span class="audit-log-item__action"
                    >Nuevo registro de mantenimiento agregado:</span
                  >
                  <span class="audit-log-item__details"
                    ><?= $row['descripcion'] ?></span
                  >
                  <span class="audit-log-item__meta"
                    >Por: <?= $resultados['nombre'] ?> - <?= $row['fecha'] ?></span
                  >
                </div>
              </li>
            <?php elseif($row['accion'] === 'MANTENIMIENTO:ELIMINAR'):?>
              <li class="audit-log-item audit-log-item--eliminacion">
                <div class="audit-log-item__icon">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                    <path
                      d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z"
                    />
                  </svg>
                </div>
                <div class="audit-log-item__content">
                  <span class="audit-log-item__action"
                    >Datos de registro de mantenimiento eliminados:</span
                  >
                  <span class="audit-log-item__details"
                    ><?= $row['descripcion'] ?></span
                  >
                  <span class="audit-log-item__meta"
                    >Por: <?= $resultados['nombre'] ?> - <?= $row['fecha'] ?></span
                  >
                </div>
              </li>
            <?php elseif($row['accion'] === 'MANTENIMIENTO:ACTUALIZAR'):?>
              <li class="audit-log-item audit-log-item--modificacion">
                <div class="audit-log-item__icon">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                    <path
                      d="M200-200h57l391-391-57-57-391 391v57Zm-80 80v-170l528-527q12-11 26.5-17t30.5-6q16 0 31 6t26 18l55 56q12 11 17.5 26t5.5 30q0 16-5.5 30.5T817-647L290-120H120Zm640-584-56-56 56 56Zm-141 85-28-29 57 57-29-28Z"
                    />
                  </svg>
                </div>
                <div class="audit-log-item__content">
                  <span class="audit-log-item__action"
                    >Datos de registro de mantenimiento actualizados:</span
                  >
                  <span class="audit-log-item__details"
                    ><?= $row['descripcion'] ?></span
                  >
                  <span class="audit-log-item__meta"
                    >Por: <?= $resultados['nombre'] ?> - <?= $row['fecha'] ?></span
                  >
                </div>
              </li>
            <!-- REGISTROS RELACIONADOS A MANTENIMIENTO - FINAL -->
            <?php endif; ?>
          <?php endwhile; ?>
        </ul>
        <div class="audit-log__pagination">
          <!-- <button class="btnEditProfile btnEditProfile--small" disabled>
            &laquo; Anterior
          </button>
          <span>Página 1 de 5</span>
          <button class="btnEditProfile btnEditProfile--small">
            Siguiente &raquo;
          </button> -->
        </div>
      </div>
    </div>
  </div>
</section>

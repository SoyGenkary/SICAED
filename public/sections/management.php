<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/SICAED/config/database.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/SICAED/app/Services/FileManager.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/SICAED/app/Models/MasterKey.php';

if (session_status() == PHP_SESSION_NONE){
  session_start();
}

$sesion = $_SESSION['id'];

// Extraemos los datos del usuario para mostrarlos
$sqluser = "SELECT * FROM usuarios WHERE id_usuario != 1";
$resultados = $conn->query($sqluser);

// Extraemos todas las claves maestras
$clavesMaestras = MasterKey::obtenerClavesMaestras($conn);
?>

<section class="management-section">
  <h1 class="principal__title">Panel de Gestión del Sistema</h1>
  <div class="management-container">
    <div class="table-container">
      <table class="table">
        <caption>Gestor de Usuarios</caption>
        <thead>
          <tr>
            <th>ID</th>
            <th>Foto</th>
            <th>Nombre</th>
            <th>Telefono</th>
            <th>Cedula</th>
            <th>Correo</th>
            <th>Fecha de Registro</th>
            <th>Ult. Conectado</th>
            <th>Rol</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php if($resultados) : ?>
            <?php while($user = $resultados->fetch_assoc()) : ?>
              <tr>
                <td data-id="<?= $user['id_usuario'] ?>"><?= $user['id_usuario'] ?></td>
                <td>
                  <div class="image">
                    <img src="<?= $user['ruta_img'] ?? './assets/img/icons/avatar.png' ?>" alt="">
                  </div>
                </td>
                <td id="nameProfile"><?= $user['nombre'] ?></td>
                <td><?= $user['telefono'] ?></td>
                <td><?= $user['cedula'] ?></td>
                <td id="emailProfile"><?= $user['email'] ?></td>
                <td><?= $user['fecha_registro'] ?></td>
                <td><?= $user['ultimo_login'] ?></td>
                <td><?= $user['rol'] ?></td>
                <td>
                  <div class="actions">
                    <button id="btnActionView" title="Ver detalles">
                      <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M480.18-311Q559-311 614-366.18q55-55.17 55-134Q669-579 613.82-634q-55.17-55-134-55Q401-689 346-633.82q-55 55.17-55 134Q291-421 346.18-366q55.17 55 134 55Zm-.12-101q-36.64 0-62.35-25.65T392-499.94q0-36.64 25.65-62.35T479.94-588q36.64 0 62.35 25.65T568-500.06q0 36.64-25.65 62.35T480.06-412ZM480-157q-144 0-264.5-76T29-437q-8-15-12-30.96t-4-31.96q0-16.01 4-32.05Q21-548 29-563q66-128 186.5-204T480-843q144 0 264.5 76T931-563q8 15 12 30.96t4 31.96q0 16.01-4 32.05Q939-452 931-437q-66 128-186.5 204T480-157Zm0-343Zm.09 222q112.91 0 207.2-60.54Q781.58-399.08 832-500q-50.42-100.92-144.8-161.46Q592.82-722 479.91-722t-207.2 60.54Q178.42-600.92 128-500q50.42 100.92 144.8 161.46Q367.18-278 480.09-278Z"/></svg>
                    </button>
                    <button id="btnActionModify" title="Modificar Perfil">
                      <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M209-210h58l315-315-56-57-317 318v54ZM142-74q-29 0-48.5-19.5T74-142v-123q0-26.86 10.2-52.01Q94.39-342.16 114-362l497.32-497.32Q624-873 641.47-879.5 658.93-886 677-886q17.74 0 34.87 6.5T744-860l117 115q14 14 20 31.48 6 17.49 6 36.47 0 18.05-6.5 35.55Q874-624 860-611L364-114q-19.84 19.61-44.99 29.8Q293.86-74 267-74H142Zm594-603-58-58 58 58ZM554-554l-28-28 56 57-28-29Z"/></svg>
                    </button>
                    <button id="btnActionDelete" title="Borrar Perfil">
                      <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M267-74q-57 0-96.5-39.5T131-210v-501q-28 0-47.5-19.5t-19.5-48Q64-807 83.5-827t47.5-20h205q0-27 18.8-46.5T402-913h154q28.4 0 47.88 19.36 19.47 19.36 19.47 46.64h205.61q29.04 0 48.54 20.2T897-779q0 29-19.5 48.5T829-711v501q0 57-39.5 96.5T693-74H267Zm426-637H267v501h426v-501Zm-426 0v501-501Zm213 331 63 62q16 17 39.48 16.5Q605.96-302 623-319q16-16 16-39.48 0-23.48-16-40.52l-63-61 63-62q16-17.04 16-40.52Q639-586 623-602q-17.04-17-40.52-17Q559-619 543-602l-63 62-62-62q-16-17-38.98-17-22.98 0-40.02 17-16 16-16 39.48 0 23.48 16 40.52l61 62-62 62q-16 17.04-15.5 40.02Q323-335 339-319q17.04 17 40.52 17Q403-302 419-319l61-61Z"/></svg>
                    </button>
                  </div>
                </td>
              </tr>
            <?php endwhile; ?>
          <?php else :?>
            <tr>
                <td colspan="10">Hubo un error al tratar de cargar los usuarios o no hay usuarios registrados.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
      <table class="table">
        <caption>Gestor de Claves Maestras</caption>
        <thead>
          <tr>
            <th>ID</th>
            <th>Clave Codificada</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php if($clavesMaestras && count($clavesMaestras) > 0) : ?>
            <?php foreach($clavesMaestras as $clave) : ?>
              <tr>
                <td><?= $clave['id_clave'] ?></td>
                <td><?= $clave['clave'] ?></td>
                <td>
                  <div class="actions">
                    <button id="btnActionDeleteClave" data-id="<?= $clave['id_clave'] ?>" title="Eliminar Clave">
                      <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M796-37 513-322q-39 59-103 92t-132 33q-117 0-199.5-83T-4-480q0-66 31.5-129T121-714l-84-83q-15-15-15-37t16-37q15-15 36.5-15t36.5 15l760 760q15 15 15 37t-15 37q-16 15-38 15t-37-15Zm189-443q0 13-4.5 25T966-433L843-310q-8 8-18.5 13.5T804-291h1l1 1-146-146 59-44 70 52 54-52-25-25H591L471-625h368q14 0 26.5 5.5T887-605l79 77q10 10 14.5 22.5T985-480ZM278-318q48 0 87.5-25.5T424-411q-32-31-57.5-57T316-518.5q-25-24.5-50.5-50T208-625q-44 21-67.5 61T117-480q0 67 47 114.5T278-318Zm0-59q-42 0-72-30.5T176-480q0-42 30-72.5t72-30.5q43 0 73 30.5t30 72.5q0 42-30 72.5T278-377Z"/></svg>
                    </button>
                    <button id="btnActionAddClave" data-id="<?= $clave['id_clave'] ?>" title="Eliminar Clave">
                      <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M412-412H222q-29 0-48.5-20.2T154-480q0-29 19.5-48.5T222-548h190v-191q0-27.6 20.2-47.8Q452.4-807 480-807q27.6 0 47.8 20.2Q548-766.6 548-739v191h190q29 0 48.5 19.5t19.5 48q0 28.5-19.5 48.5T738-412H548v190q0 27.6-20.2 47.8Q507.6-154 480-154q-27.6 0-47.8-20.2Q412-194.4 412-222v-190Z"/></svg>
                    </button>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else : ?>
            <tr>
              <td colspan="3">No hay claves maestras registradas.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
  <div class="management-details">
        <h2>Acerca de los Roles:</h2>
        <p>Resumen de los permisos que tiene cada rol:</p>
        <br>
        <ul>
          <li>
            <strong>Jefe (Usted):</strong> Tiene acceso a todas las opciones del sistema. <strong> Puede acceder a: Principal, Buscador, Administrador, Estado, Gestión y Perfil.</strong>
          </li>
          <li>
            <strong>Administrador:</strong> Tiene acceso a casi todas las opciones del sistema. <strong> Puede acceder a: Principal, Buscador, Administrador, Estado y Perfil.</strong>
          </li>
          <li>
            <strong>Usuario:</strong> Tiene acceso unicamente a 2 opciones del sistema. <strong> Puede acceder a: Estado y Perfil.</strong>
          </li>
        </ul>
  </div>
  <div class="modals-actions">
    <div class="modals-container">
      
      <!-- Modal para Ver Detalles del Usuario -->
      <div class="modal view-detail">
        <div class="modal-content">
          <div class="modal-header">
            <h2>Detalles del Usuario</h2>
            <span class="close-btn">&times;</span>
          </div>
          <div class="user-photo">
            <img id="view-photo" src="../uploads/Users/images/687069e8ad7bb7.31613899.jpg" alt="Foto de Perfil">
          </div>
          <div class="modal-body">
            <div class="detail-item"><strong>ID:</strong> <span id="view-id"></span></div>
            <div class="detail-item"><strong>Nombre:</strong> <span id="view-nombre"></span></div>
            <div class="detail-item"><strong>Teléfono:</strong> <span id="view-telefono"></span></div>
            <div class="detail-item"><strong>Cédula:</strong> <span id="view-cedula"></span></div>
            <div class="detail-item"><strong>Email:</strong> <span id="view-email"></span></div>
            <div class="detail-item"><strong>Fecha de Registro:</strong> <span id="view-fecha-registro"></span></div>
            <div class="detail-item"><strong>Último Login:</strong> <span id="view-ultimo-login"></span></div>
            <div class="detail-item"><strong>Rol:</strong> <span id="view-rol"></span></div>
          </div>
          <div class="view-user-history">
            <h2>Historial del Usuario</h2>
          </div>
        </div>
      </div>

      <!-- Modal para Modificar Usuario -->
      <div class="modal modify-detail">
        <div class="modal-content">
          <div class="modal-header">
            <h2>Modificar Perfil de Usuario</h2>
            <span class="close-btn">&times;</span>
          </div>
          <form id="modify-form">
            <small style="margin-top: 1rem; display: block;">Se debe reingresar todos los datos del usuario!</small>
            <div class="modal-body">
              <input type="hidden" id="modify-id" name="id_usuario">
              <div class="form-group">
                <label for="modify-nombre">Nombre:</label>
                <input type="text" id="modify-nombre" name="nombreUser" class="form-control">
              </div>
              <div class="form-group">
                <label for="modify-telefono">Teléfono:</label>
                <input type="text" id="modify-telefono" name="telefonoUser" class="form-control">
              </div>
              <div class="form-group">
                <label for="modify-cedula">Cédula:</label>
                <input type="text" id="modify-cedula" name="cedulaUser" class="form-control">
              </div>
              <div class="form-group">
                <label for="modify-email">Correo:</label>
                <input type="email" id="modify-email" name="emailUser" class="form-control">
              </div>
              <div class="form-group">
                <label for="modify-password">Contraseña:</label>
                <input type="password" id="modify-password" name="passwordUser" class="form-control">
              </div>
              <div class="form-group">
                <label for="modify-rol">Rol:</label>
                <select id="modify-rol" name="rolUser" class="form-control">
                  <option value="Usuario">Usuario</option>
                  <option value="Administrador">Administrador</option>
                  <option value="Jefe">Jefe</option>
                </select>
              </div>
            </div>
            <div class="form-group">
              <input id='btnChangeProfile' name="btnChangeProfile" type="submit" value="Modificar Perfil">
            </div>
          </form>
        </div>
      </div>

      <!-- Modal para Eliminar Usuario -->
      <div class="modal delete-detail">
        <div class="modal-content">
          <div class="modal-header">
            <h2>Eliminar Usuario</h2>
            <span class="close-btn">&times;</span>
          </div>
          <div class="modal-body">
            <p class="warning">¿Está seguro de que desea eliminar a este usuario?</p>
            <p><strong>Nombre:</strong> <span id="delete-nombre"></span></p>
            <p><strong>Correo:</strong> <span id="delete-correo"></span></p>
            <small style="color: var(--color-danger); margin-top: 1rem; display: block;">*Esta acción es irreversible.</small>
            <input id='btnDeleteProfile' name="btnDeleteProfile" type="submit" value="Borrar Perfil">
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

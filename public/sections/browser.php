<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/SICAED/app/Controllers/VehiclesController.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/SICAED/app/Controllers/StaffController.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/SICAED/config/database.php';

// Obtener todos los vehículos y todo el personal al cargar la página.
$listaVehiculos = VehiclesController::getAll($conn);
$listaPersonal = StaffController::getAll($conn);
?>
<section class="browser-section">
  <div class="header__browser">
    <h1 class="principal__title">Buscador</h1>
    <button id="btnShowContainer">
      <svg
        xmlns="http://www.w3.org/2000/svg"
        height="24px"
        viewBox="0 -960 960 960"
        width="24px"
        fill="#000000"
      >
        <path
          d="M444.33-357.7 271-531q-5-4.95-8-11.02-3-6.06-3-12.7 0-13.28 9.55-23.78Q279.1-589 294-589h372q14.9 0 24.45 10.6Q700-567.8 700-554q0 4-11 23L515.67-357.7q-7.67 7.7-16.74 11.2-9.07 3.5-18.75 3.5t-18.93-3.5q-9.25-3.5-16.92-11.2Z"
        />
      </svg>
    </button>
  </div>
  <form class="browser__container" id="browser__container">
    <div class="browser__group">
      <svg
        xmlns="http://www.w3.org/2000/svg"
        height="24px"
        viewBox="0 -960 960 960"
        width="24px"
        fill="#000000"
      >
        <path
          d="M372.63-306.33q-117.45 0-198.21-80.87-80.75-80.87-80.75-196.67 0-115.8 80.87-196.46Q255.41-861 371.2-861q115.8 0 196.47 80.81 80.66 80.81 80.66 196.62 0 46.24-12.5 83.9-12.5 37.67-35.83 70l229.33 228.34q15 15.7 15 37.99 0 22.3-15.33 38.01Q812.84-110 791.04-110q-21.81 0-37.71-15.67L526.47-353.33q-30.14 21.61-69.11 34.3-38.97 12.7-84.73 12.7Zm-1.47-106q72.85 0 122.01-49.2 49.16-49.19 49.16-121.73 0-72.53-49.2-122.13Q443.93-755 371.22-755q-73.28 0-122.41 49.61-49.14 49.6-49.14 122.13 0 72.54 49.04 121.73 49.04 49.2 122.45 49.2Z"
        />
      </svg>
      <input
        type="search"
        name="search__input"
        class="search__input"
        placeholder="Escribe aquí para buscar de forma global..."
      />
      <input id="btnSearch" name="browserSearch" type="submit" value="Buscar" />
    </div>
    <div class="browser__tools">
      <div class="browser-tools__groups">
        <svg
          xmlns="http://www.w3.org/2000/svg"
          height="24px"
          viewBox="0 -960 960 960"
          width="24px"
          fill="#000000"
        >
          <path
            d="M440-240q-17 0-28.5-11.5T400-280q0-17 11.5-28.5T440-320h80q17 0 28.5 11.5T560-280q0 17-11.5 28.5T520-240h-80ZM280-440q-17 0-28.5-11.5T240-480q0-17 11.5-28.5T280-520h400q17 0 28.5 11.5T720-480q0 17-11.5 28.5T680-440H280ZM160-640q-17 0-28.5-11.5T120-680q0-17 11.5-28.5T160-720h640q17 0 28.5 11.5T840-680q0 17-11.5 28.5T800-640H160Z"
          />
        </svg>
        <select name="tipoDeDato" id="tipoDeDato" class="select-default">
          <option value="default" disabled selected>Tipo de Dato</option>
          <option value="global">Busqueda Global (Por Defecto)</option>
          <optgroup label="Filtro - Vehicular">
            <option value="vehiculo:marca">Vehiculo/Marca</option>
            <option value="vehiculo:modelo">Vehiculo/Modelo</option> <option value="vehiculo:matricula">Vehiculo/Matricula</option>
            <option value="vehiculo:vin">Vehiculo/Vin</option> <option value="vehiculo:sede">Vehiculo/Sede</option>
            <option value="vehiculo:estado">Vehiculo/Estado</option>
            <option value="vehiculo:municipio">Vehiculo/Municipio</option>
            <option value="vehiculo:kilometraje">Vehiculo/Kilometraje</option> <option value="vehiculo:mantenimiento">
              Vehiculo/Servicio
            </option>
            <option value="vehiculo:fecha-agregado">
              Vehiculo/Fecha de Agregado
            </option>
          </optgroup>
          <optgroup label="Filtro - Personal">
            <option value="personal:nombre">Personal/Nombre</option>
            <option value="personal:apellido">Personal/Apellido</option>
            <option value="personal:documento">
              Personal/Documento de Identidad
            </option>
            <option value="personal:contacto">
              Personal/Contacto (Email / Celular)
            </option>
            <option value="personal:estado">Personal/Estado</option>
            <option value="personal:municipio">Personal/Municipio</option>
            <option value="personal:fecha-agregado">
              Personal/Fecha de Agregado
            </option>
          </optgroup>

        </select>
      </div>
      <div class="browser-tools__groups">
        <svg
          xmlns="http://www.w3.org/2000/svg"
          height="24px"
          viewBox="0 -960 960 960"
          width="24px"
          fill="#000000"
        >
          <path
            d="m196-376-23 70q-4 11-14 18.5t-22 7.5q-20 0-32.5-16.5T100-333l120-321q5-12 15-19t23-7h30q13 0 23 7t15 19l121 323q7 19-4.5 35T411-280q-12 0-22-7.5T375-306l-25-70H196Zm24-68h104l-48-150h-6l-50 150Zm418 92h166q15 0 25.5 10.5T840-316q0 15-10.5 25.5T804-280H572q-10 0-17-7t-7-17v-38q0-7 2-13.5t7-11.5l193-241H592q-15 0-25.5-10.5T556-644q0-15 10.5-25.5T592-680h222q10 0 17 7t7 17v38q0 7-2 13.5t-7 11.5L638-352ZM384-760q-7 0-9.5-6t2.5-11l89-89q6-6 14-6t14 6l89 89q5 5 2.5 11t-9.5 6H384Zm82 666-89-89q-5-5-2.5-11t9.5-6h192q7 0 9.5 6t-2.5 11l-89 89q-6 6-14 6t-14-6Z"
          />
        </svg>
        <select name="filtroOrden" id="filtroOrden" class="select-default">
          <option value="default" disabled selected>Filtrar Orden</option>
          <option value="ascendente">
            Ascendente [A-Z] [1-9] (Por Defecto)
          </option>
          <option value="descendente">Descendente [Z-A] [9-1]</option>
        </select>
      </div>
    </div>
  </form>
  <div id="selectionMenu" class="selection-menu">
    <span id="selectionCount">0 seleccionados</span>
    <button id="isolateSelectionBtn" data-action="isolate">Aislar Selección</button>
    <button id="deleteSelectionBtn" data-action="eliminar">Borrar Registros Seleccionados</button>
  </div>
  <!-- RESULTADOS BUSCADOR -->
  <div class="results__container">
    <div class="vehiculos">
      <div class="results__header">
        <h3>Vehículos</h3>
      </div>
      <div class="results__content">
        <table class="results_table">
          <thead>
            <tr>
              <th>CK<input type="checkbox" name="selectedAllResults" class="selectedAllResults" style="display: none;"/></th>
              <th>Identificador</th>
              <th>Módelo</th>
              <th>Marca</th>
              <th>Estado</th>
              <th>Municipio</th>
              <th>Sede</th>
              <th>Kilometraje (KM)</th>
              <th>Fecha de Agregado</th>
              <th>Asignación</th>
              <th>Servicios</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($listaVehiculos)): ?>
                <?php foreach ($listaVehiculos as $vehiculo): ?>
                    <tr data-id="<?= htmlspecialchars($vehiculo['id_vehiculo']) ?>">
                        <td class="select"><input type="checkbox" name="selectedResult" class="selectedResult"/></td>
                        <td class="info">
                            <div class="image">
                                <img src="<?= !empty($vehiculo['ruta_img']) ? htmlspecialchars($vehiculo['ruta_img']) : './assets/img/icons/vehicle.png' ?>" alt="<?= htmlspecialchars($vehiculo['modelo']) ?>" loading="lazy"/>
                            </div>
                            <div class="basic__info">
                                <span class="matricula"><?= htmlspecialchars($vehiculo['matricula']) ?></span>
                                <span class="serial-vin"><?= htmlspecialchars($vehiculo['serial_vin']) ?></span>
                            </div>
                        </td>
                        <td class="model"><span class="model-date"><?= htmlspecialchars($vehiculo['modelo']) ?></span></td>
                        <td class="brand"><span class="brand-date"><?= htmlspecialchars($vehiculo['marca']) ?></span></td>
                        <td class="state"><span class="state-date"><?= htmlspecialchars($vehiculo['estado'] ?? 'N/A') ?></span></td>
                        <td class="municipality"><span class="municipality-date"><?= htmlspecialchars($vehiculo['municipio'] ?? 'N/A') ?></span></td>
                        <td class="sede"><span class="sede-date"><?= htmlspecialchars($vehiculo['sede']) ?></span></td>
                        <td class="mileage"><span class="mileage-date"><?= htmlspecialchars(number_format($vehiculo['kilometraje'], 0, ',', '.')) ?> km</span></td>
                        <td class="date"><span class="time-date"><?= htmlspecialchars($vehiculo['fecha_agregado']) ?></span></td>
                        <td class="assignment">
                            <span class="assignment-date <?= $vehiculo['asignado'] ? 'assignment--assignment' : 'assignment--unassigned' ?>">
                                <?= $vehiculo['asignado'] ? 'Asignado' : 'No Asignado' ?>
                            </span>
                        </td>
                        <td class="maintenance"><span class="maintenance-date"><?= $vehiculo['en_mantenimiento'] ? 'Sí' : 'No' ?></span></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="12" style="text-align: center; padding: 20px;">No hay vehículos registrados.</td>
                </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
    <div class="personal">
      <div class="results__header">
        <h3>Personal</h3>
      </div>
      <div class="results__content">
        <table class="results_table">
          <thead>
            <tr>
              <th>CK<input type="checkbox" name="selectedAllResults" class="selectedAllResults" style="display: none;"/></th>
              <th>Nombres</th>
              <th>Apellidos</th>
              <th>Doc. Identidad</th>
              <th>Estado</th>
              <th>Municipio</th>
              <th>Teléfono</th>
              <th>Email</th>
              <th>Rol</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($listaPersonal)): ?>
                <?php foreach ($listaPersonal as $persona): ?>
                    <tr data-id="<?= htmlspecialchars($persona['id_personal']) ?>">
                        <td class="select"><input type="checkbox" name="selectedResult" class="selectedResult"/></td>
                        <td class="names"><span class="names_date"><?= htmlspecialchars($persona['nombres']) ?></span></td>
                        <td class="lastnames"><span class="lastname-date"><?= htmlspecialchars($persona['apellidos']) ?></span></td>
                        <td class="dni"><span class="dni-date"><?= htmlspecialchars($persona['documento_identidad']) ?></span></td>
                        <td class="state"><span class="state-date"><?= htmlspecialchars($persona['estado'] ?? 'N/A') ?></span></td>
                        <td class="municipality"><span class="municipality-date"><?= htmlspecialchars($persona['municipio'] ?? 'N/A') ?></span></td>
                        <td class="phone"><span class="phone-date"><?= htmlspecialchars($persona['telefono'] ?? 'N/A') ?></span></td>
                        <td class="email"><span class="email-date"><?= htmlspecialchars($persona['email'] ?? 'N/A') ?></span></td>
                        <td class="assignment"><span class="assignment-date"><?= htmlspecialchars($persona['cargo']) ?></span></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="10" style="text-align: center; padding: 20px;">No hay personal registrado.</td>
                </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</section>

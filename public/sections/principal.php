<?php 
date_default_timezone_set("America/Caracas");
include_once $_SERVER['DOCUMENT_ROOT'] . '/SICAED/config/database.php';

$sqlvehiculosCount = 'SELECT COUNT(*) FROM vehiculos';
$sqlpersonalCount = 'SELECT COUNT(*) FROM personal WHERE cargo = "Conductor"';
$sqlmantenimientosCount = 'SELECT COUNT(*) FROM mantenimientos';


$cantidadVehiculos = $conn->query($sqlvehiculosCount)->fetch_column();
$cantidadConductores = $conn->query($sqlpersonalCount)->fetch_column();
$cantidadMantenimientos = $conn->query($sqlmantenimientosCount)->fetch_column();

$sqlvehiculoRecientes = "SELECT 
    v.matricula,
    v.modelo,
    v.marca,
    v.serial_vin,
    est.estado,
    mun.municipio
FROM 
    vehiculos AS v
LEFT JOIN 
    municipios AS mun ON v.id_municipio = mun.id_municipio
LEFT JOIN 
    estados AS est ON mun.id_estado = est.id_estado
WHERE 
    DATE(v.fecha_agregado) >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
ORDER BY 
    v.fecha_agregado DESC
LIMIT 5";

$resultados = $conn->query($sqlvehiculoRecientes);
$vehiculos = $resultados->fetch_all(MYSQLI_ASSOC);


?>

<section class="principal-section">
  <h1 class="principal__title">Panel Principal</h1>
  <div class="principal__resumen">
    <div class="resumen resumen-vehiculos">
      <div class="info__resumen">
        <div class="svgContainer">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="bi bi-car-front-fill"
            viewBox="0 0 16 16"
            fill="#8a89ff"
          >
            <path
              d="M2.52 3.515A2.5 2.5 0 0 1 4.82 2h6.362c1 0 1.904.596 2.298 1.515l.792 1.848c.075.175.21.319.38.404.5.25.855.715.965 1.262l.335 1.679q.05.242.049.49v.413c0 .814-.39 1.543-1 1.997V13.5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5v-1.338c-1.292.048-2.745.088-4 .088s-2.708-.04-4-.088V13.5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5v-1.892c-.61-.454-1-1.183-1-1.997v-.413a2.5 2.5 0 0 1 .049-.49l.335-1.68c.11-.546.465-1.012.964-1.261a.8.8 0 0 0 .381-.404l.792-1.848ZM3 10a1 1 0 1 0 0-2 1 1 0 0 0 0 2m10 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2M6 8a1 1 0 0 0 0 2h4a1 1 0 1 0 0-2zM2.906 5.189a.51.51 0 0 0 .497.731c.91-.073 3.35-.17 4.597-.17s3.688.097 4.597.17a.51.51 0 0 0 .497-.731l-.956-1.913A.5.5 0 0 0 11.691 3H4.309a.5.5 0 0 0-.447.276L2.906 5.19Z"
            />
          </svg>
        </div>
        <div class="info">
          <p>Vehículos Totales</p>
          <span class="cantidad cantidad-vehiculos"><?= $cantidadVehiculos; ?></span>
        </div>
      </div>
      <div class="info__ver-todos">
        <a href="#">Ver detalles</a>
      </div>
    </div>
    <div class="resumen resumen-conductores">
      <div class="info__resumen">
        <div class="svgContainer">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            height="24px"
            viewBox="0 -960 960 960"
            width="24px"
            fill="#86efac"
          >
            <path
              d="M480-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM160-160v-112q0-34 17.5-62.5T224-378q62-31 126-46.5T480-440q66 0 130 15.5T736-378q29 15 46.5 43.5T800-272v112H160Z"
            />
          </svg>
        </div>
        <div class="info">
          <p>Conductores</p>
          <span class="cantidad cantidad-conductores"><?= $cantidadConductores; ?></span>
        </div>
      </div>
      <div class="info__ver-todos">
        <a href="#">Ver detalles</a>
      </div>
    </div>
    <div class="resumen resumen-mantenimientos">
      <div class="info__resumen">
        <div class="svgContainer">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            height="24px"
            viewBox="0 -960 960 960"
            width="24px"
            fill="#fde047"
          >
            <path
              d="M756-120 537-339l84-84 219 219-84 84Zm-552 0-84-84 276-276-68-68-28 28-51-51v82l-28 28-121-121 28-28h82l-50-50 142-142q20-20 43-29t47-9q24 0 47 9t43 29l-92 92 50 50-28 28 68 68 90-90q-4-11-6.5-23t-2.5-24q0-59 40.5-99.5T701-841q15 0 28.5 3t27.5 9l-99 99 72 72 99-99q7 14 9.5 27.5T841-701q0 59-40.5 99.5T701-561q-12 0-24-2t-23-7L204-120Z"
            />
          </svg>
        </div>
        <div class="info">
          <p>Mantenimientos</p>
          <span class="cantidad cantidad-mantenimiento"><?= $cantidadMantenimientos; ?></span>
        </div>
      </div>
      <div class="info__ver-todos">
        <a href="#">Ver detalles</a>
      </div>
    </div>
  </div>
  <div class="temporal-resumen">
    <div class="vehiculos-recientes">
      <div class="recientes__title">
        <h2 class="title__reciente">Vehículos agregados recientemente</h2>
      </div>
      <table class="recientes__table">
        <thead>
          <tr>
            <th>Matrícula</th>
            <th>Vin</th>
            <th>Modelo</th>
            <th>Marca</th>
            <th>Estado</th>
            <th>Municipio</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($vehiculos as $data) : ?>
            <tr>
              <td><?= $data['matricula'] ?></td>
              <td><?= $data['serial_vin'] ?></td>
              <td><?= $data['modelo'] ?></td>
              <td><?= $data['marca'] ?></td>
              <td><?= $data['estado'] ?></td>
              <td><?= $data['municipio'] ?></td>
            </tr>
          <?php endforeach ?>
          <tr>
            <td colspan="6"><a href="#">Ver recientes</a></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</section>

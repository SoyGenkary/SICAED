<?php
// sections/detalleEntidad.php
include_once $_SERVER['DOCUMENT_ROOT'] . '/SICAED/config/database.php';

// Validar y sanitizar los parámetros de entrada
$tipo_entidad = $_GET['tipo'] ?? 'desconocido';
$id_entidad = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

function obtenerDatosVehiculo($conn, $id) {
    // Obtener información principal del vehículo
    $sql = "SELECT v.*, mun.municipio, est.estado 
            FROM vehiculos v
            LEFT JOIN municipios mun ON v.id_municipio = mun.id_municipio
            LEFT JOIN estados est ON mun.id_estado = est.id_estado
            WHERE v.id_vehiculo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $vehiculo = $stmt->get_result()->fetch_assoc();

    if (!$vehiculo) return null;

    // Obtener todos los conductores asignados (CORREGIDO PARA MÚLTIPLES)
    $sql_conductores = "SELECT p.id_personal, p.nombres, p.apellidos, p.documento_identidad, p.ruta_img 
                        FROM personal p
                        JOIN asignaciones a ON p.id_personal = a.id_personal
                        WHERE a.id_vehiculo = ?";
    $stmt_conductores = $conn->prepare($sql_conductores);
    $stmt_conductores->bind_param("i", $id);
    $stmt_conductores->execute();
    $vehiculo['conductores'] = $stmt_conductores->get_result()->fetch_all(MYSQLI_ASSOC);

    // Obtener historial de mantenimientos
    $sql_mantenimientos = "SELECT * FROM mantenimientos WHERE id_vehiculo = ? ORDER BY fecha_mantenimiento DESC";
    $stmt_mantenimientos = $conn->prepare($sql_mantenimientos);
    $stmt_mantenimientos->bind_param("s", $vehiculo['id_vehiculo']);
    $stmt_mantenimientos->execute();
    $vehiculo['mantenimientos'] = $stmt_mantenimientos->get_result()->fetch_all(MYSQLI_ASSOC);

    return $vehiculo;
}

function obtenerDatosPersonal($conn, $id) {
    // Obtener información principal del personal
    $sql = "SELECT p.*, mun.municipio, est.estado, t.telefono, e.email
            FROM personal p
            LEFT JOIN municipios mun ON p.id_municipio = mun.id_municipio
            LEFT JOIN estados est ON mun.id_estado = est.id_estado
            LEFT JOIN telefonos t ON p.id_telefono = t.id_telefono
            LEFT JOIN emails e ON p.id_email = e.id_email
            WHERE p.id_personal = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $personal = $stmt->get_result()->fetch_assoc();
    
    if (!$personal) return null;

    // Obtener vehículos asignados si el cargo es 'Conductor'
    if ($personal['cargo'] === 'Conductor') {
        $sql_vehiculos = "SELECT v.id_vehiculo, v.serial_vin, v.matricula, v.modelo, v.marca, v.ruta_img
                          FROM vehiculos v
                          JOIN asignaciones a ON v.id_vehiculo = a.id_vehiculo
                          WHERE a.id_personal = ?";
        $stmt_vehiculos = $conn->prepare($sql_vehiculos);
        $stmt_vehiculos->bind_param("i", $id);
        $stmt_vehiculos->execute();
        $personal['vehiculos_asignados'] = $stmt_vehiculos->get_result()->fetch_all(MYSQLI_ASSOC);
    } else {
        $personal['vehiculos_asignados'] = [];
    }

    return $personal;
}


$datos = null;
if ($id_entidad) {
    if ($tipo_entidad === 'vehiculo') {
        $datos = obtenerDatosVehiculo($conn, $id_entidad);
    } elseif ($tipo_entidad === 'personal') {
        $datos = obtenerDatosPersonal($conn, $id_entidad);
    }
}

if (!$datos) {
    echo "<h1 class='principal__title'>Entidad no encontrada</h1>";
    echo "<p>No se pudo encontrar la información para el tipo '{$tipo_entidad}' con el ID '{$id_entidad}'.</p>";
    exit;
}
?>

<section class="detalle-section">
    <button id="closeModalDetail" class="btnCloseModalDetail">
        <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H8m12 0-4 4m4-4-4-4M9 4H7a3 3 0 0 0-3 3v10a3 3 0 0 0 3 3h2"/>
        </svg>
        <span>Regresar</span>
    </button>
    <?php if ($tipo_entidad === 'vehiculo'): ?>
    <div class="detalle-header">
        <div class="detalle-header__info">
            <div class="detalle-header__imagen">
                <img src="<?= !empty($datos['ruta_img']) ? htmlspecialchars($datos['ruta_img']) : './assets/img/icons/vehicle.png' ?>"
                    alt="Foto del vehículo">
            </div>
            <div class="detalle-header__texto">
                <h1 class="principal__title"><?= htmlspecialchars($datos['marca'] . ' ' . $datos['modelo']) ?></h1>
                <span class="detalle-subtitulo"><?= htmlspecialchars($datos['matricula']) ?></span>
                <div class="detalle-tags">
                    <?php if (!empty($datos['conductores'])): ?>
                    <span class="tag tag-info">Asignado</span>
                    <?php endif; ?>
                    <?php if (!empty($datos['mantenimientos'])): ?>
                    <span class="tag tag-warning">Se le ha realizado servicio</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="detalle-body">
        <div class="detalle-main-content">
            <div class="info-card">
                <h3 class="subsection__title">Información General</h3>
                <div class="info-grid">
                    <div><strong>VIN:</strong> <?= htmlspecialchars($datos['serial_vin']) ?></div>
                    <div><strong>Kilometraje:</strong> <?= number_format($datos['kilometraje'], 0, ',', '.') ?> km</div>
                    <div><strong>Estado:</strong> <?= htmlspecialchars($datos['estado'] ?? 'N/A') ?></div>
                    <div><strong>Municipio:</strong> <?= htmlspecialchars($datos['municipio'] ?? 'N/A') ?></div>
                    <div><strong>Sede:</strong> <?= htmlspecialchars($datos['sede']) ?></div>
                    <div><strong>Agregado:</strong> <?= date('d/m/Y', strtotime($datos['fecha_agregado'])) ?></div>
                    <div class="ubication"><strong>Ubicacion:</strong> <?= htmlspecialchars($datos['ubicacion'])?> </div>
                    <div id="id-vehiculo" hidden><strong>ID:</strong> <span><?= htmlspecialchars($datos['id_vehiculo'])?></span> </div>
                </div>
            </div>

            <div class="info-card">
                <h3 class="subsection__title">Historial de Servicios</h3>
                <div class="table-container">
                    <table class="estado-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Fecha</th>
                                <th>Tipo</th>
                                <th>Costo</th>
                                <th>Taller</th>
                                <th>Descripción</th>
                                <th>Documentos</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($datos['mantenimientos'])): ?>
                            <?php foreach ($datos['mantenimientos'] as $m): ?>
                            <tr>
                                <td id="id-mantenimiento"><?= htmlspecialchars($m['id_mantenimiento']) ?></td>
                                <td><?= date('d/m/Y', strtotime($m['fecha_mantenimiento'])) ?></td>
                                <td><?= htmlspecialchars($m['tipo_mantenimiento']) ?></td>
                                <td>Bs. <?= number_format($m['costo'], 2, ',', '.') ?></td>
                                <td><?= htmlspecialchars($m['taller']) ?></td>
                                <!-- <td class="description">
                                    <?= htmlspecialchars($m['descripcion']) ?>
                                </td> -->
                                <td class="description">
                                    <button class="info-desc">
                                        <svg xmlns="http://www.w3.org/2000/svg" height="19px" viewBox="0 -960 960 960" width="19px" ><path d="M480-320q75 0 127.5-52.5T660-500q0-75-52.5-127.5T480-680q-75 0-127.5 52.5T300-500q0 75 52.5 127.5T480-320Zm0-72q-45 0-76.5-31.5T372-500q0-45 31.5-76.5T480-608q45 0 76.5 31.5T588-500q0 45-31.5 76.5T480-392Zm0 192q-146 0-266-81.5T40-500q54-137 174-218.5T480-800q146 0 266 81.5T920-500q-54 137-174 218.5T480-200Zm0-300Zm0 220q113 0 207.5-59.5T832-500q-50-101-144.5-160.5T480-720q-113 0-207.5 59.5T128-500q50 101 144.5 160.5T480-280Z"/></svg>
                                    </button>
                                </td>
                                <td class="docs-maintenance">
                                    <?php
                                        $docs = json_decode($m['ruta_documentos'], true);
                                        $count = is_array($docs) ? count($docs) : 0;
                                        
                                        echo '<p>' . $count . '</p>';

                                        if (!empty($count)) {
                                            echo '<style>
                                                .docs-maintenance {
                                                    display: flex;
                                                    justify-content: start;
                                                    align-items: center;
                                                    gap: 5px;
                                                    line-height: 16px;
                                                }
                                                .docs-maintenance svg {
                                                    display: block;
                                                }
                                            </style>';

                                            echo '
                                            <button class="info-docs">
                                                <svg  xmlns="http://www.w3.org/2000/svg"  width="18"  height="18"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-script"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 20h-11a3 3 0 0 1 0 -6h11a3 3 0 0 0 0 6h1a3 3 0 0 0 3 -3v-11a2 2 0 0 0 -2 -2h-10a2 2 0 0 0 -2 2v8" /></svg>
                                            </button>
                                            ';
                                        }
                                    ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php else: ?>
                            <tr>
                                <td colspan="6">No hay registros de mantenimiento.</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-docs" id="modal-docs">
                <span class="close closeBtn">&times;</span>
                <div class="docs-mantenimientos">
                    <h3 class="subsection__title">Documentos Adjuntos: Mantenimiento</h3>
                    <ul class="file-list">
                        </ul>
                </div>
            </div>
            <div class="modal-desc" id="modal-desc">
                <span class="close closeBtn">&times;</span>
                <div class="desc-mantenimientos">
                    <h3 class="subsection__title">Mantenimiento: Descripción</h3>
                    <div class="description-content">

                    </div>
                </div>
            </div>
            <div class="modal-galery" id="modal-galery">
                <span class="close closeBtn">&times;</span>
                <img id="modalImg" src="" alt="Imagen grande">
                <button class="btnDeleteImg">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                        <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5M8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5m3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0"/>
                    </svg>
                </button>
            </div>
            <div class='info-card'>
                <h3 class="subsection__title">Galeria</h3>

                <div class="galery-container">
                    <?php $fotosExtras = json_decode($datos['ruta_extras'], true); ?>
                    <?php if (!empty($fotosExtras) && is_array($fotosExtras)): ?>
                        <?php foreach($fotosExtras as $index => $extras): ?>
                        <div class="image-vehicle">
                            <img src="<?= htmlspecialchars($extras['ruta']) ?>" alt="<?= htmlspecialchars($extras['nombreOriginal']) ?>" data-index="<?= htmlspecialchars($index) ?>" data-type="extra" data-category="vehiculo">
                            <svg width="24px" height="24px" viewBox="0 0 24 24" stroke-width="1.5" fill="none" xmlns="http://www.w3.org/2000/svg" color="#"><path d="M3 13C6.6 5 17.4 5 21 13" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M12 17C10.3431 17 9 15.6569 9 14C9 12.3431 10.3431 11 12 11C13.6569 11 15 12.3431 15 14C15 15.6569 13.6569 17 12 17Z" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <style>
                            .galery-container {
                                grid-template-columns: 1fr;
                            }
                        </style>
                        <p>No hay imagenes extras asignados a este vehículo.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="detalle-sidebar">
            <div class="info-card">
                <h3 class="subsection__title">Conductores Asignados</h3>
                <?php if (!empty($datos['conductores'])): ?>
                    <?php foreach ($datos['conductores'] as $c): ?>
                    <div class="asignacion-item clickable-row" data-tipo="personal" data-id="<?= htmlspecialchars($c['id_personal']) ?>">
                        <img src="<?= !empty($c['ruta_img']) ? htmlspecialchars($c['ruta_img']) : './assets/img/icons/avatar.png' ?>"
                            alt="Foto del conductor">
                        <div class="asignacion-info">
                            <strong><?= htmlspecialchars($c['nombres'] . ' ' . $c['apellidos']) ?></strong>
                            <span><?= htmlspecialchars($c['documento_identidad']) ?></span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No hay conductores asignados a este vehículo.</p>
                <?php endif; ?>
            </div>

            <div class="info-card">
                <h3 class="subsection__title">Documentos Adjuntos</h3>
                <ul class="file-list">
                    <?php 
                    $documentos = json_decode($datos['ruta_documentos'], true);
                    if (!empty($documentos) && is_array($documentos)):
                        foreach ($documentos as $index => $doc): ?>
                            <li>
                                <a href="<?= htmlspecialchars($doc['ruta']) ?>" download="<?= htmlspecialchars($doc['nombreOriginal']) ?>" data-index="<?= htmlspecialchars($index) ?>" data-type="documento" data-category="vehiculo">
                                    <span><?= htmlspecialchars($doc['nombreOriginal']) ?></span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-arrow-down-fill" viewBox="0 0 16 16">
                                        <path d="M9.293 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.707A1 1 0 0 0 13.707 4L10 .293A1 1 0 0 0 9.293 0M9.5 3.5v-2l3 3h-2a1 1 0 0 1-1-1m-1 4v3.793l1.146-1.147a.5.5 0 0 1 .708.708l-2 2a.5.5 0 0 1-.708 0l-2-2a.5.5 0 0 1 .708-.708L7.5 11.293V7.5a.5.5 0 0 1 1 0"/>
                                    </svg>
                                </a>
                                <button class="btnDeleteDocs">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                                        <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5M8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5m3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0"/>
                                    </svg>
                                </button>
                            </li>
                        <?php endforeach;
                    else: ?>
                        <li>No hay documentos adjuntos.</li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>

    <?php elseif ($tipo_entidad === 'personal'): ?>
    <div class="detalle-header">
        <div class="detalle-header__info">
            <div class="detalle-header__imagen">
                <img src="<?= !empty($datos['ruta_img']) ? htmlspecialchars($datos['ruta_img']) : './assets/img/icons/avatar.png' ?>"
                    alt="Foto del personal">
            </div>
            <div class="detalle-header__texto">
                <h1 class="principal__title"><?= htmlspecialchars($datos['nombres'] . ' ' . $datos['apellidos']) ?>
                </h1>
                <span class="detalle-subtitulo"><?= htmlspecialchars($datos['cargo']) ?></span>
                <div class="detalle-tags">
                    <?php if (!empty($datos['vehiculos_asignados'])): ?>
                    <span class="tag tag-info">Con Vehículo Asignado</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="detalle-body">
        <div class="detalle-main-content">
            <div class="info-card">
                <h3 class="subsection__title">Información de Contacto y Ubicación</h3>
                <div class="info-grid">
                    <div><strong>Cédula:</strong> <?= htmlspecialchars($datos['documento_identidad']) ?></div>
                    <div><strong>Teléfono:</strong> <?= htmlspecialchars($datos['telefono'] ?? 'N/A') ?></div>
                    <div><strong>Email:</strong> <?= htmlspecialchars($datos['email'] ?? 'N/A') ?></div>
                    <div><strong>Estado:</strong> <?= htmlspecialchars($datos['estado'] ?? 'N/A') ?></div>
                    <div><strong>Municipio:</strong> <?= htmlspecialchars($datos['municipio'] ?? 'N/A') ?></div>
                    <div><strong>Agregado:</strong> <?= date('d/m/Y', strtotime($datos['fecha_agregado'])) ?></div>
                    <div><strong>Ubicación:</strong> <?= htmlspecialchars($datos['ubicacion'] ?? 'N/A')?> </div>
                    <div id="id-personal" hidden><strong>ID:</strong> <span><?= htmlspecialchars($datos['id_personal'])?></span></div>
                </div>
            </div>

            <div class="info-card">
                <h3 class="subsection__title">Vehículos Asignados</h3>
                <div class="table-container">
                    <table class="estado-table">
                        <thead>
                            <tr>
                                <th>Matrícula</th>
                                <th>VIN</th>
                                <th>Marca</th>
                                <th>Modelo</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($datos['vehiculos_asignados'])): ?>
                            <?php foreach ($datos['vehiculos_asignados'] as $v): ?>
                            <tr class="clickable-row" data-tipo="vehiculo"
                                data-id="<?= htmlspecialchars($v['id_vehiculo']) ?>">
                                <td><?= htmlspecialchars($v['matricula']) ?></td>
                                <td><?= htmlspecialchars($v['serial_vin']) ?></td>
                                <td><?= htmlspecialchars($v['marca']) ?></td>
                                <td><?= htmlspecialchars($v['modelo']) ?></td>
                            </tr>
                            <?php endforeach; ?>
                            <?php else: ?>
                            <tr>
                                <td colspan="3">No hay vehículos asignados a esta persona.</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <div class="detalle-sidebar">
            <div class="info-card">
                <h3 class="subsection__title">Documentos Adjuntos</h3>
                <ul class="file-list">
                    <?php 
                    $documentos = json_decode($datos['ruta_documentos'], true);
                    if (!empty($documentos) && is_array($documentos)):
                        foreach ($documentos as $index => $doc): ?>
                            <li>
                                <a href="<?= htmlspecialchars($doc['ruta']) ?>" download="<?= htmlspecialchars($doc['nombreOriginal']) ?>" data-index="<?= htmlspecialchars($index) ?>" data-type="documento" data-category="personal">
                                    <span><?= htmlspecialchars($doc['nombreOriginal']) ?></span>
                                    <svg xmlns="http://www.w3.org/2000/svg"  fill="currentColor" class="bi bi-file-earmark-arrow-down-fill" viewBox="0 0 16 16">
                                        <path d="M9.293 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.707A1 1 0 0 0 13.707 4L10 .293A1 1 0 0 0 9.293 0M9.5 3.5v-2l3 3h-2a1 1 0 0 1-1-1m-1 4v3.793l1.146-1.147a.5.5 0 0 1 .708.708l-2 2a.5.5 0 0 1-.708 0l-2-2a.5.5 0 0 1 .708-.708L7.5 11.293V7.5a.5.5 0 0 1 1 0"/>
                                    </svg>
                                </a>
                                <button class="btnDeleteDocs">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                                        <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5M8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5m3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0"/>
                                    </svg>
                                </button>
                            </li>
                        <?php endforeach;
                    else: ?>
                        <li>No hay documentos adjuntos.</li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
    <?php endif; ?>
</section>

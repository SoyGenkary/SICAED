<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/SICAED/config/database.php';

// Vehículos totales
$total_vehiculos_res = $conn->query("SELECT COUNT(*) as total FROM vehiculos");
$total_vehiculos = $total_vehiculos_res->fetch_assoc()['total'] ?? 0;

// Vehículos en mantenimiento (contando matrículas únicas en mantenimiento)
$en_mantenimiento_res = $conn->query("SELECT COUNT(DISTINCT id_vehiculo) as en_mantenimiento FROM mantenimientos");
$en_mantenimiento = $en_mantenimiento_res->fetch_assoc()['en_mantenimiento'] ?? 0;

// Vehículos activos
$activos = $total_vehiculos - $en_mantenimiento;

// Vehiculos por sede y estado
$sql_estado_municipio = "
    SELECT 
        e.estado, 
        m.municipio, 
        v.sede, 
        COUNT(v.id_vehiculo) as cantidad
    FROM vehiculos v
    LEFT JOIN municipios m ON v.id_municipio = m.id_municipio
    LEFT JOIN estados e ON m.id_estado = e.id_estado
    GROUP BY e.estado, m.municipio, v.sede
    ORDER BY e.estado, m.municipio, v.sede
";
$res_estado_municipio = $conn->query($sql_estado_municipio);
$estado_municipio_data = [];
$estado_totals = [];
$municipio_totals = [];

if ($res_estado_municipio) {
    while ($row = $res_estado_municipio->fetch_assoc()) {
        $estado = $row['estado'] ?? 'No especificado';
        $municipio = $row['municipio'] ?? 'No especificado';
        $sede = $row['sede'] ?? 'No especificada';
        $cantidad = $row['cantidad'];

        if (!isset($estado_municipio_data[$estado])) {
            $estado_municipio_data[$estado] = [];
            $estado_totals[$estado] = 0;
        }

        if (!isset($estado_municipio_data[$estado][$municipio])) {
            $estado_municipio_data[$estado][$municipio] = [
                'total_municipio' => 0,
                'sedes' => []
            ];
        }
        
        $estado_totals[$estado] += $cantidad;
        $estado_municipio_data[$estado][$municipio]['total_municipio'] += $cantidad;

        $estado_municipio_data[$estado][$municipio]['sedes'][] = [
            'sede' => $sede,
            'cantidad' => $cantidad
        ];
    }
}

// DISTRIBUCIÓN DE MARCAS Y MODELOS ---
// Top 5 marcas
$sql_top_marcas = "SELECT marca, COUNT(*) as cantidad 
                    FROM vehiculos 
                    GROUP BY marca 
                    ORDER BY cantidad DESC 
                    LIMIT 5";
$res_top_marcas = $conn->query($sql_top_marcas);
$top_marcas = $res_top_marcas ? $res_top_marcas->fetch_all(MYSQLI_ASSOC) : [];

// Detalle completo de marcas y modelos
$sql_detalle_modelos = "SELECT marca, modelo, COUNT(*) as cantidad 
                         FROM vehiculos 
                         GROUP BY marca, modelo 
                         ORDER BY marca, cantidad DESC";
$res_detalle_modelos = $conn->query($sql_detalle_modelos);
$detalle_modelos = $res_detalle_modelos ? $res_detalle_modelos->fetch_all(MYSQLI_ASSOC) : [];

?>
<section class="estado-section">
    <h1 class="principal__title">Estado Detallado</h1>
    <div class="estado-dashboard">
        <div class="estado-summary">
            <h2 class="subsection__title">Resumen General de Vehículos</h2>
            <div class="chart-container">
                <canvas class="myChart" style="max-width: 280px; max-height: 200px; width: 100%; height: 100%;" hidden></canvas>
            </div>
            <div class="summary-grid">
                <div class="summary-item">
                    <span class="summary-item__value"></span>
                    <span class="summary-item__label">Vehículos Totales</span>
                </div>
                <div class="summary-item summary-item--active">
                    <span class="summary-item__value"></span>
                    <span class="summary-item__label">Vehiculos sin Servicios</span>
                </div>
                <div class="summary-item summary-item--maintenance">
                    <span class="summary-item__value"></span>
                    <span class="summary-item__label">Vehiculos con Servicios</span>
                </div>
            </div>
        </div>
        <div class="estado-municipio-sede">
            <h2 class="subsection__title">Vehículos por Estado, Municipio y Sede</h2>
            <div class="ubicacion-content">
                <div class="ubicacion-list">
                    <?php if (!empty($estado_municipio_data)): ?>
                    <ul class="ubicacion-estado-list" style="list-style: none; padding-left: 0;">
                        <?php foreach ($estado_municipio_data as $estado => $municipios): ?>
                        <li style="margin-bottom: 1.5em;">
                            <div style="font-weight: bold;">
                                <?= htmlspecialchars($estado) ?> <span style="color: #718096;"><?= $estado_totals[$estado] ?></span>
                            </div>
                            <ul class="ubicacion-municipio-list" style="list-style: none; padding-left: 1.5em; margin-top: 0.5em;">
                                <?php foreach ($municipios as $municipio => $data): ?>
                                <li style="margin-bottom: 0.7em;">
                                    <div>
                                        <?= htmlspecialchars($municipio) ?> <span style="color: #718096;"><?= $data['total_municipio'] ?></span>
                                    </div>
                                    <ul class="ubicacion-sede-list" style="list-style: disc; padding-left: 2em; margin-top: 0.3em;">
                                        <?php foreach ($data['sedes'] as $sede): ?>
                                        <li style="margin-bottom: 0.2em;">
                                            <span>
                                                <?= htmlspecialchars($sede['sede']) ?>
                                            </span>
                                            <span style="color: #718096;">(<?= $sede['cantidad'] ?> vehículos)</span>
                                        </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <?php else: ?>
                    <p>No hay datos de estado/municipio/sede para mostrar.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="estado-marcas-modelos">
            <h2 class="subsection__title">Distribución de Marcas</h2>
            <div class="marcas-content">
                <div class="marcas-list">
                    <?php if (!empty($top_marcas)): ?>
                    <ol>
                        <?php foreach ($top_marcas as $marca): ?>
                        <li><?= htmlspecialchars($marca['marca']) ?>: <?= $marca['cantidad'] ?> vehículos</li>
                        <?php endforeach; ?>
                    </ol>
                    <?php else: ?>
                    <p>No hay datos de marcas para mostrar.</p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="table-container">
                <h4>Detalle Completo Marcas/Modelos:</h4>
                <table class="estado-table">
                    <thead>
                        <tr>
                            <th>Marca</th>
                            <th>Modelo</th>
                            <th>Cantidad</th>
                            <th>% del Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($detalle_modelos)): ?>
                            <?php foreach ($detalle_modelos as $item): 
                                $porcentaje = ($total_vehiculos > 0) ? ($item['cantidad'] / $total_vehiculos) * 100 : 0;
                            ?>
                            <tr>
                                <td><?= htmlspecialchars($item['marca']) ?></td>
                                <td><?= htmlspecialchars($item['modelo']) ?></td>
                                <td><?= $item['cantidad'] ?></td>
                                <td><?= number_format($porcentaje, 1, ',', '.') ?>%</td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4">No hay datos para mostrar.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
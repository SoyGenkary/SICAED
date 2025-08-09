<?php 
include_once $_SERVER['DOCUMENT_ROOT'] . '/SICAED/config/database.php';
function obtenerEstadosYMunicipios($conn) {
    // Arrays con los datos
    $estados = [];
    $municipios = [];

    // Consulta para obtener los estados
    $sqlEstados = "SELECT id_estado, estado FROM estados ORDER BY estado";
    $sqlMunicipios = "SELECT id_municipio, municipio, id_estado FROM municipios ORDER BY municipio";

    // Ejecutar consulta de estados
    $resultadoEstados = $conn->query($sqlEstados);
    while($row = $resultadoEstados->fetch_assoc()) {
        $estados[] = [
            'id_estado' => $row['id_estado'],
            'estado' => $row['estado']
        ];
    }

    $resultadoMunicipios = $conn->query($sqlMunicipios);
    while($row = $resultadoMunicipios->fetch_assoc()) {
        $municipios[] = [
            'id_municipio' => $row['id_municipio'],
            'id_estado' => $row['id_estado'],
            'municipio' => $row['municipio'],
        ];
    }

    return [
        'estados' => $estados,
        'municipios' => $municipios
    ];
}

echo json_encode(obtenerEstadosYMunicipios($conn), JSON_UNESCAPED_UNICODE);

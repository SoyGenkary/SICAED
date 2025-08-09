<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/SICAED/app/Services/FileManager.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/SICAED/app/Models/AuditLogger.php';

class Maintenance {

    /**
     * Registra un nuevo mantenimiento para un vehículo, incluyendo documentos adjuntos.
     */
    public static function crear($conn, $data) {
        $vin = '';
        $matricula = '';
        if (!empty($data['prefijoIdentificadorVehiculo'])) {
            if ($data['prefijoIdentificadorVehiculo'] === 'vin') {
                $vin = mysqli_real_escape_string($conn, $data['vehiculoMantenimiento'] ?? '');
            } else if ($data['prefijoIdentificadorVehiculo'] === 'matricula') {
                $matricula = mysqli_real_escape_string($conn, $data['vehiculoMantenimiento'] ?? '');
            } else {
                return ['success' => false, 'message' => 'Prefijo de identificación (matricula/vin) no válido.'];
            }
        }

        $tipo = mysqli_real_escape_string($conn, $data['tipoMantenimiento'] ?? '');
        $fecha = mysqli_real_escape_string($conn, $data['fechaMantenimiento'] ?? '');
        $costo = mysqli_real_escape_string($conn, $data['costoMantenimiento'] ?? '0');
        $taller = mysqli_real_escape_string($conn, $data['tallerMantenimiento'] ?? '');
        $descripcion = mysqli_real_escape_string($conn, $data['descripcionMantenimiento'] ?? '');

        if ((empty($matricula) && empty($vin)) || empty($tipo) || empty($fecha)) {
            return ['success' => false, 'message' => 'Matrícula/VIN, tipo y fecha son obligatorios.'];
        }

        if (!empty($vin)){
            $condicional = "WHERE serial_vin = '$vin'";
            $tipoIdentificador = 'serial_vin';
        } else if (!empty($matricula)) {
            $condicional = "WHERE matricula = '$matricula'";
            $tipoIdentificador = 'matricula';
        }

        // Verificar que el vehículo exista
        $check = $conn->query("SELECT $tipoIdentificador, id_vehiculo FROM vehiculos $condicional");
        if ($check->num_rows === 0) {
            return ['success' => false, 'message' => "El vehículo con el identificador: ". $tipoIdentificador === 'serial_vin' ? $vin : $matricula . " no existe."];
        }

        $idVehiculo = $check->fetch_assoc();
        $idVehiculo = $idVehiculo['id_vehiculo'];

        // Manejar la subida de documentos
        $rutaArchivos = json_encode(manejarMultiplesArchivos('Maintenance/docs/', 'archivosMantenimiento'));

        $costo_val = is_numeric($costo) ? $costo : 0;

        $sql = "INSERT INTO mantenimientos (
                    id_vehiculo, tipo_mantenimiento, fecha_mantenimiento, 
                    costo, taller, descripcion, ruta_documentos
                ) VALUES (
                    '$idVehiculo', '$tipo', '$fecha',
                    $costo_val, '$taller', '$descripcion', '$rutaArchivos'
                )";

        $res = $conn->query($sql);

        if (!$res) {
            // Si la inserción falla, eliminar los archivos que se hayan subido para evitar basura
            if(!empty(json_decode($rutaArchivos, true))) {
                eliminarArchivosSubidos(array_column(json_decode($rutaArchivos, true), 'ruta'));
            }
            return ['success' => false, 'message' => 'Error al registrar el mantenimiento: ' . $conn->error];
        }

        if ($res) {
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            $id_mantenimiento_creado = $conn->insert_id;
            $identificadorVehiculo = !empty($matricula) ? "matrícula $matricula" : "VIN $vin";

            AuditLogger::registrarAccion(
                $conn,
                $_SESSION['id'],
                'MANTENIMIENTO:CREAR',
                'mantenimientos',
                $id_mantenimiento_creado,
                '',
                '',
                "Se creó un registro de mantenimiento para el vehículo con {$identificadorVehiculo}."
            );
        }

        return [
            'success' => true,
            'message' => 'Mantenimiento registrado exitosamente.'
        ];
    }

    /**
     * Obtiene los datos de un registro de mantenimiento por su ID.
     */
    public static function obtener($conn, $data) {
        $id = (int)($data['searchIdInput'] ?? 0);

        if ($id <= 0) {
            return ['success' => false, 'message' => 'ID de mantenimiento no válido.'];
        }

        $sql = "SELECT 
                m.id_mantenimiento,
                m.id_vehiculo,
                m.tipo_mantenimiento AS tipoMantenimiento,
                m.fecha_mantenimiento AS fechaMantenimiento,
                m.costo AS costoMantenimiento,
                m.taller AS tallerMantenimiento,
                m.descripcion AS descripcionMantenimiento,
                m.ruta_documentos,
                v.matricula,
                v.serial_vin
            FROM mantenimientos m
            JOIN vehiculos v ON m.id_vehiculo = v.id_vehiculo
            WHERE m.id_mantenimiento = $id";

        $res = $conn->query($sql);
        if ($res && $res->num_rows > 0) {
            $mantenimiento = $res->fetch_assoc();
            // Selecciona la matrícula si existe, si no, el serial_vin
            $mantenimiento['vehiculoMantenimiento'] = !empty($mantenimiento['matricula']) ? $mantenimiento['matricula'] : $mantenimiento['serial_vin'];
            
            $mantenimiento['prefijoIdentificadorVehiculo'] = !empty($mantenimiento['matricula']) ? 'matricula' : 'vin';

            $mantenimiento['documentos_existentes'] = json_decode($mantenimiento['ruta_documentos'], true) ?: [];
            return ['success' => true, 'data' => $mantenimiento];
        }

        return ['success' => false, 'message' => 'Registro de mantenimiento no encontrado.'];
    }

    /**
     * Actualiza un registro de mantenimiento, incluyendo la gestión de sus documentos.
     */
    public static function actualizar($conn, $data) {
        $id = (int)($data['id_mantenimiento'] ?? 0);

        $vin = '';
        $matricula = '';
        if (!empty($data['prefijoIdentificadorVehiculo'])) {
            if ($data['prefijoIdentificadorVehiculo'] === 'vin') {
                $vin = mysqli_real_escape_string($conn, $data['vehiculoMantenimiento'] ?? '');
            } else if ($data['prefijoIdentificadorVehiculo'] === 'matricula') {
                $matricula = mysqli_real_escape_string($conn, $data['vehiculoMantenimiento'] ?? '');
            } else {
                return ['success' => false, 'message' => 'Prefijo de identificación (matricula/vin) no válido.'];
            }
        }

        $tipo = mysqli_real_escape_string($conn, $data['tipoMantenimiento'] ?? '');
        $fecha = mysqli_real_escape_string($conn, $data['fechaMantenimiento'] ?? '');
        $costo = mysqli_real_escape_string($conn, $data['costoMantenimiento'] ?? '0');
        $taller = mysqli_real_escape_string($conn, $data['tallerMantenimiento'] ?? '');
        $descripcion = mysqli_real_escape_string($conn, $data['descripcionMantenimiento'] ?? '');

        if ($id <= 0 || (empty($matricula) && empty($vin)) || empty($tipo) || empty($fecha)) {
            return ['success' => false, 'message' => 'ID, matrícula, tipo y fecha son obligatorios.'];
        }

        if (!empty($vin)){
            $condicional = "WHERE serial_vin = '$vin'";
            $tipoIdentificador = 'serial_vin';
        } else if (!empty($matricula)) {
            $condicional = "WHERE matricula = '$matricula'";
            $tipoIdentificador = 'matricula';
        }

        // Verificar que el vehículo exista
        $check = $conn->query("SELECT $tipoIdentificador, id_vehiculo FROM vehiculos $condicional");
        if ($check->num_rows === 0) {
            return ['success' => false, 'message' => "El vehículo con el identificador: ". $tipoIdentificador === 'serial_vin' ? $vin : $matricula . " no existe."];
        }

        $idVehiculo = $check->fetch_assoc();
        $idVehiculo = $idVehiculo['id_vehiculo'];

        // Obtener documentos actuales de la base de datos
        $sqlDocs = "SELECT ruta_documentos FROM mantenimientos WHERE id_mantenimiento = $id";
        $resDocs = $conn->query($sqlDocs);
        $docsActualesStr = $resDocs->fetch_assoc()['ruta_documentos'] ?? '[]';
        $documentosActuales = json_decode($docsActualesStr, true) ?: [];
        
        // Manejar nuevos documentos subidos
        $nuevosDocumentos = manejarMultiplesArchivos('Maintenance/docs/', 'archivosMantenimiento');

        // Fusionar documentos existentes con los nuevos
        $documentosFinales = json_encode(array_merge($documentosActuales, $nuevosDocumentos));

        $costo_val = is_numeric($costo) ? $costo : 0;


        $sql = "UPDATE mantenimientos SET 
                    id_vehiculo = '$idVehiculo',
                    tipo_mantenimiento = '$tipo',
                    fecha_mantenimiento = '$fecha',
                    costo = $costo_val,
                    taller = '$taller',
                    descripcion = '$descripcion',
                    ruta_documentos = '$documentosFinales'
                WHERE id_mantenimiento = $id";

        $res = $conn->query($sql);

        if ($res) {
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            $identificadorVehiculo = !empty($matricula) ? "matrícula $matricula" : "VIN $vin";
            AuditLogger::registrarAccion(
                $conn,
                $_SESSION['id'],
                'MANTENIMIENTO:ACTUALIZAR',
                'mantenimientos',
                $id,
                '',
                '',
                "Se actualizó el mantenimiento ID {$id} para el vehículo con {$identificadorVehiculo}."
            );
            return [
                'success' => (bool)$res,
                'message' => $res ? 'Mantenimiento actualizado exitosamente.' : 'Error al actualizar: ' . $conn->error
            ];
        }
    }

    /**
     * Elimina un registro de mantenimiento y todos sus documentos asociados.
     */
    public static function eliminar($conn, $data) {
        $id = (int)($data['id_mantenimiento'] ?? 0);

        if ($id <= 0) {
            return ['success' => false, 'message' => 'ID no válido para eliminar.'];
        }

        // Primero, obtener las rutas de los documentos para borrarlos del servidor
        $sqlDocs = "SELECT ruta_documentos FROM mantenimientos WHERE id_mantenimiento = $id";
        $resDocs = $conn->query($sqlDocs);

        $mantenimiento = $resDocs->fetch_assoc();

        $rutasAEliminar = [];

        if (!empty($mantenimiento['ruta_documentos'])) {
            $documentos = json_decode($mantenimiento['ruta_documentos'], true);
            if (is_array($documentos)) {
                foreach ($documentos as $doc) {
                    if (isset($doc['ruta'])) {
                        $rutasAEliminar[] = $doc['ruta'];
                    }
                }
            }
        }

        // Luego, eliminar el registro de la base de datos
        $sql = "DELETE FROM mantenimientos WHERE id_mantenimiento = $id";
        $res = $conn->query($sql);

        if ($res) {
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            eliminarArchivosSubidos($rutasAEliminar);
            AuditLogger::registrarAccion(
                $conn,
                $_SESSION['id'],
                'MANTENIMIENTO:ELIMINAR',
                'mantenimientos',
                $id,
                '',
                '',
                "Se eliminó el registro de mantenimiento con ID {$id}."
            );

            return [
                'success' => (bool)$res,
                'message' => $res ? 'Registro de mantenimiento eliminado.' : 'Error al eliminar: ' . $conn->error
            ];
        }
    }

    public static function eliminarDocumento($conn, $data){
        $id_mantenimiento = $data['ID'];
        $indexArchivo = $data['indexFile'];

        $sql = "SELECT ruta_documentos FROM mantenimientos WHERE id_mantenimiento = '$id_mantenimiento'";
        $resultado = $conn->query($sql);

        if (!$resultado && !$resultado->num_rows > 0) {
            return ['success' => false, 'message' => 'No se pudo encontrar el documento en la base de datos'];
        }

        $rutaAEliminar = [];

        $resultado = $resultado->fetch_assoc();
        $resultado = json_decode($resultado['ruta_documentos'], true);

        foreach ($resultado as $index => $doc) {
            if ($index == $indexArchivo) {
                $ruta = $doc['ruta'];

                if (empty($ruta)) {
                    return ['success' => false, 'message' => 'No se encontro la ruta del documento!'];
                }

                $rutaAEliminar[] = $ruta;
                
                eliminarArchivosSubidos($rutaAEliminar);

                unset($resultado[$index]);

                $resultado = array_values($resultado);

                $resultado = json_encode($resultado);

                $sql = "UPDATE mantenimientos SET ruta_documentos = '$resultado' WHERE id_mantenimiento = '$id_mantenimiento'";
                
                if ($conn->query($sql)) {
                    if (session_status() == PHP_SESSION_NONE) {
                        session_start();
                    }
                    AuditLogger::registrarAccion(
                        $conn,
                        $_SESSION['id'],
                        'MANTENIMIENTO:ELIMINAR',
                        'mantenimientos',
                        $id_mantenimiento,
                        '',
                        '',
                        "Se eliminó el documento: [".$doc['nombreOriginal']."] del registro de mantenimiento de ID: $id_mantenimiento");
                    return ['success' => true, 'message' => 'Se borro de forma exitosa el documento!'];
                } else {
                    return ['success' => false, 'message' => 'Ocurrio un error al tratar de borrar el documento!'];
                }
            }
        }

        return ['response' => $indexArchivo];
    }

    /**
     * Obtiene todos los registros de mantenimiento.
     */
    public static function obtenerTodos($conn) {
        $mantenimientos = [];
        $sql = "SELECT 
                    m.id_mantenimiento, m.matricula, m.tipo_mantenimiento,
                    DATE_FORMAT(m.fecha_mantenimiento, '%d/%m/%Y') as fecha_mantenimiento,
                    m.costo, m.taller, v.marca, v.modelo
                FROM mantenimientos m
                JOIN vehiculos v ON m.matricula = v.matricula
                ORDER BY m.fecha_mantenimiento DESC";
        
        $resultado = $conn->query($sql);
        if ($resultado) {
            while ($row = $resultado->fetch_assoc()) {
                $mantenimientos[] = $row;
            }
        }
        return $mantenimientos;
    }
}

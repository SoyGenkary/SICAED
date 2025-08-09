<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/SICAED/app/Services/FileManager.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/SICAED/app/Models/AuditLogger.php';

class Vehiculo {

    public static function crear($conn, $data) {
        $id_vehiculo = null;
        $matricula = mysqli_real_escape_string($conn, $data['matriculaVehiculo'] ?? '');
        $vin = mysqli_real_escape_string($conn, $data['vinVehiculo'] ?? '');

        if (empty($matricula) && empty($vin)) {
            return ['success' => false, 'message' => 'La matrícula y el VIN no pueden estar ambos vacíos.'];
        }

        $sql_check = "SELECT id_vehiculo FROM vehiculos WHERE matricula = '$matricula' OR serial_vin = '$vin'";

        if (empty($matricula)) {
            $sql_check = "SELECT id_vehiculo FROM vehiculos WHERE serial_vin = '$vin'";
        } else if (empty($vin)) {
            $sql_check = "SELECT id_vehiculo FROM vehiculos WHERE matricula = '$matricula'";
        }

        if ($conn->query($sql_check)->num_rows > 0) {
            return [
                'success' => false,
                'message' => 'Un vehículo con esta matrícula o VIN ya existe en el sistema.'
            ];
        }


        $marca = mysqli_real_escape_string($conn, $data['marcaVehiculo'] ?? '');
        $modelo = mysqli_real_escape_string($conn, $data['modeloVehiculo'] ?? '');
        $kilometraje = mysqli_real_escape_string($conn, $data['kilometrajeVehiculo'] ?? '0');
        $municipio = mysqli_real_escape_string($conn, $data['municipioVehiculo'] ?? '');
        $sede = mysqli_real_escape_string($conn, $data['sedeVehiculo'] ?? '');
        $ubicacion = mysqli_real_escape_string($conn, $data['descripcionUbicacion'] ?? '');
        $asignarPersonal = mysqli_real_escape_string($conn, $data['asignarPersonalVehiculo'] ?? '');

        $rutaFoto = manejarSubidaArchivo('Vehicles/images/', 'fotoVehiculo');
        $rutaExtras = json_encode(manejarMultiplesArchivos('Vehicles/images/', 'fotosVehiculo'));
        $rutaArchivos = json_encode(manejarMultiplesArchivos('Vehicles/docs/', 'archivosVehiculo'));

        $kilometraje_val = is_numeric($kilometraje) ? $kilometraje : 0;
        $municipio_val = !empty($municipio) ? "'$municipio'" : "NULL";

        if (empty($matricula) && empty($vin)) {
            return ['success' => false, 'message' => 'La matrícula/vin no puede estar vacía.'];
        }

        $sqlInsertar = "INSERT INTO vehiculos (
            matricula, serial_vin, marca, modelo, kilometraje,
            id_municipio, sede, ruta_img, ruta_extras, ruta_documentos, ubicacion
        ) VALUES (
            '$matricula', '$vin', '$marca', '$modelo', $kilometraje_val,
            $municipio_val, '$sede', '$rutaFoto', '$rutaExtras','$rutaArchivos', '$ubicacion'
        )";
        
        // Comprobamos si esta marcado la casilla de asignar personal
        if (!empty($asignarPersonal)) {
            // Construimos la cedula
            $cedulaPrefijo = mysqli_real_escape_string($conn, $data['prefijoCedulaPersonalAsignado'] ?? '');
            $cedula = mysqli_real_escape_string($conn, $data['cedulaPersonalAsignado'] ?? '');
            $cedulaCompleta = $cedulaPrefijo . $cedula;

            // Verificar existencia del personal
            $sql = "SELECT id_personal FROM personal WHERE documento_identidad = '$cedulaCompleta'";
            $resultado_personal = $conn->query($sql);

            if ($resultado_personal && $resultado_personal->num_rows > 0) {
                $id_personal = $resultado_personal->fetch_assoc();
                $id_personal = $id_personal['id_personal'];

                // Hacemos la peticion
                $resultado = $conn->query($sqlInsertar);
                $id_vehiculo = $conn->insert_id;

                $sql = "INSERT INTO asignaciones(id_personal, id_vehiculo) VALUES('$id_personal', '$id_vehiculo')";
                $resultado_asignacion = $conn->query($sql);

                if (!$resultado_asignacion) {
                    return ['success' => false, 'message' => 'Error al asignar el personal.'];
                }
            } else {
                return ['success' => false, 'message' => 'El documento de identidad ingresado no existe.'];
            }
        } else {
            $resultado = $conn->query($sqlInsertar);
            $id_vehiculo = $conn->insert_id;
        }

        if ($resultado) {
            if (session_status() === PHP_SESSION_NONE){
                session_start();
            }
            // Registramos la accion
            AuditLogger::registrarAccion($conn, $_SESSION['id'], "VEHICULO:CREAR", 'vehiculos', $id_vehiculo, '', '', "Ha registrado un vehiculo con " . (empty($matricula) ? "el vin: $vin" : "la matricula: $matricula"));
        }

        return [
            'success' => (bool)$resultado,
            'message' => $resultado ? 'Vehículo creado exitosamente.' : 'Error al crear: ' . $conn->error
        ];
    }

    public static function obtener($conn, $data) {
        $vin = '';
        $matricula = '';
        if (!empty($data['prefijoIdentificadorVehiculoSearch'])) {
            if ($data['prefijoIdentificadorVehiculoSearch'] === 'vin') {
                $vin = mysqli_real_escape_string($conn, $data['searchIdInput'] ?? '');
            } else if ($data['prefijoIdentificadorVehiculoSearch'] === 'matricula') {
                $matricula = mysqli_real_escape_string($conn, $data['searchIdInput'] ?? '');
            } else {
                return ['success' => false, 'message' => 'Prefijo de identificación no válido.'];
            }
        }

        if (empty($data['searchIdInput'])) {
            return ['success' => false, 'message' => 'No se proporcionó matrícula/vim.'];
        }

        if (!empty($vin)){
            $sql = "SELECT
                v.id_vehiculo, m.id_estado AS estadoSedeVehiculo,
                v.id_municipio AS municipioVehiculo, v.matricula AS matriculaVehiculo,
                v.serial_vin AS vinVehiculo, v.marca AS marcaVehiculo,
                v.modelo AS modeloVehiculo, v.kilometraje AS kilometrajeVehiculo,
                v.sede AS sedeVehiculo, v.ubicacion AS descripcionUbicacion
            FROM vehiculos v
            JOIN municipios m ON v.id_municipio = m.id_municipio WHERE v.serial_vin = '$vin'";
        } elseif (!empty($matricula)) {
            $sql = "SELECT
                    v.id_vehiculo, m.id_estado AS estadoSedeVehiculo,
                    v.id_municipio AS municipioVehiculo, v.matricula AS matriculaVehiculo,
                    v.serial_vin AS vinVehiculo, v.marca AS marcaVehiculo,
                    v.modelo AS modeloVehiculo, v.kilometraje AS kilometrajeVehiculo,
                    v.sede AS sedeVehiculo, v.ubicacion AS descripcionUbicacion
                FROM vehiculos v
                JOIN municipios m ON v.id_municipio = m.id_municipio WHERE v.matricula = '$matricula'";
        }

        $resultado = $conn->query($sql);

        if (!$resultado || $resultado->num_rows === 0) {
            return ['success' => false, 'message' => 'Vehículo no encontrado en la base de datos.'];
        }

        if ($resultado && $resultado->num_rows > 0) {
            $resultado = $resultado->fetch_assoc();

            // Realizamos las operaciones necesarias para chequear si existe un asociacion 
            // $sql = "SELECT id_vehiculo FROM vehiculos WHERE matricula = '$matricula'";
            // $resultado_vehiculo = $conn->query($sql);
            // $id_vehiculo = $resultado_vehiculo->fetch_assoc();
            $id_vehiculo = $resultado['id_vehiculo'];

            $sql = "SELECT p.documento_identidad AS cedula
                FROM asignaciones a
                JOIN personal p ON a.id_personal = p.id_personal
                WHERE a.id_vehiculo = '$id_vehiculo'
                ";
            $documento = $conn->query($sql);
            if ($documento->num_rows > 0) {
                $documento = $documento->fetch_assoc()['cedula'];
                $cedulaPrefijo = substr($documento, 0, 2);
                $cedulaSinPrefijo = substr($documento, 2);
    
                $resultado['asignarPersonalVehiculo'] = true;
                $resultado['prefijoCedulaPersonalAsignado'] = $cedulaPrefijo;
                $resultado['cedulaPersonalAsignado'] = $cedulaSinPrefijo;
            }

            return ['success' => true, 'data' => $resultado];
        }
        return ['success' => false, 'message' => 'Vehículo no encontrado.'];
    }

    public static function obtenerCantidad($conn) {
        // Vehículos totales
        $total_vehiculos_res = $conn->query("SELECT COUNT(*) as total FROM vehiculos");
        $total_vehiculos = $total_vehiculos_res->fetch_assoc()['total'] ?? 0;

        // Vehículos en mantenimiento (contando matrículas únicas en mantenimiento)
        $en_mantenimiento_res = $conn->query("SELECT COUNT(DISTINCT id_vehiculo) as en_mantenimiento FROM mantenimientos");
        $en_mantenimiento = $en_mantenimiento_res->fetch_assoc()['en_mantenimiento'] ?? 0;

        // Vehículos activos
        $activos = $total_vehiculos - $en_mantenimiento;

        return [
            'success' => true,
            'data' => [
                [
                    'vehiculos' => $total_vehiculos,
                    'matenimientos' => $en_mantenimiento,
                    'activos' => $activos
                ]
            ]
        ];
    }

    public static function eliminar($conn, $data) {
        $vin = mysqli_real_escape_string($conn, $data['vinVehiculo'] ?? '');
        $matricula = mysqli_real_escape_string($conn, $data['matriculaVehiculo'] ?? '');

        // Obtener ID del vehículo y rutas
        if (!empty($vin)){
            $sql = "SELECT id_vehiculo, ruta_img, ruta_documentos, ruta_extras FROM vehiculos WHERE serial_vin = '$vin'";
        } elseif (!empty($matricula)) {
            $sql = "SELECT id_vehiculo, ruta_img, ruta_documentos, ruta_extras FROM vehiculos WHERE matricula = '{$matricula}'";
        }
        
        $resultado_vehiculo = $conn->query($sql);

        
        if (!$resultado_vehiculo || $resultado_vehiculo->num_rows === 0) {
            return ['success' => false, 'message' => 'Vehículo no encontrado.'];
        }
        
        $vehiculo = $resultado_vehiculo->fetch_assoc();
        $id_vehiculo = $vehiculo['id_vehiculo'];

        $sqlMantenimiento = "SELECT id_mantenimiento, ruta_documentos FROM mantenimientos WHERE id_vehiculo = '{$id_vehiculo}'";
        $resultado_mantenimiento = $conn->query($sqlMantenimiento);
        
        // Preparar rutas a eliminar
        $rutasAEliminar = [];

        if ($resultado_mantenimiento->num_rows > 0){
            $resultado_mantenimiento = $resultado_mantenimiento->fetch_assoc();

            
            if (!empty($resultado_mantenimiento['ruta_documentos'])) {
            $documentosMantenimiento = json_decode($resultado_mantenimiento['ruta_documentos'], true);
            if (is_array($documentosMantenimiento)) {
                foreach ($documentosMantenimiento as $doc) {
                    if (isset($doc['ruta'])) {
                        $rutasAEliminar[] = $doc['ruta'];
                        }
                    }
                }
            }

            // Eliminar mantenimientos asociados
            $sql = "DELETE FROM mantenimientos WHERE id_vehiculo = '$id_vehiculo'";
            $conn->query($sql);
        }

        if (!empty($vehiculo['ruta_img'])) {
            $rutasAEliminar[] = $vehiculo['ruta_img'];
        }

        if (!empty($vehiculo['ruta_extras'])) {
            $extras = json_decode($vehiculo['ruta_extras'], true);
            if (is_array($extras)) {
                foreach ($extras as $ext) {
                    if (isset($ext['ruta'])) {
                        $rutasAEliminar[] = $ext['ruta'];   
                    }
                }
            }
        }

        if (!empty($vehiculo['ruta_documentos'])) {
            $documentos = json_decode($vehiculo['ruta_documentos'], true);
            if (is_array($documentos)) {
                foreach ($documentos as $doc) {
                    if (isset($doc['ruta'])) {
                        $rutasAEliminar[] = $doc['ruta'];
                    }
                }
            }
        }

        // Eliminar archivos físicos
        eliminarArchivosSubidos($rutasAEliminar);

        // Eliminar asignación si existe
        $sql = "SELECT id_asignacion FROM asignaciones WHERE id_vehiculo = '$id_vehiculo'";
        $resultado_asociacion = $conn->query($sql);
        if ($resultado_asociacion->num_rows > 0) {
            $sql = "DELETE FROM asignaciones WHERE id_vehiculo = '$id_vehiculo'";
            $conn->query($sql);
        }

        // Eliminar vehículo
        $sql = "DELETE FROM vehiculos WHERE id_vehiculo = '$id_vehiculo'";
        $resultado = $conn->query($sql);

        if ($resultado){
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            AuditLogger::registrarAccion(
                $conn,
                $_SESSION['id'],
                'VEHICULO:ELIMINAR',
                'vehiculos',
                "$id_vehiculo",
                '',
                '',
                "Se eliminó el vehículo con " . (empty($matricula) ? "el vin: $vin" : "la matricula: $matricula")
            );
            
            return [
                'success' => (bool)$resultado,
                'message' => $resultado ? 'Vehículo eliminado.' : 'Error: ' . $conn->error
            ];
        }
    }

    public static function eliminarPorId($conn, $data) {
        $id_vehiculo = mysqli_real_escape_string($conn, $data['id_vehiculo']);

        // Obtener rutas de archivos del vehículo
        $sql = "SELECT matricula, serial_vin, ruta_img, ruta_documentos, ruta_extras FROM vehiculos WHERE id_vehiculo = '$id_vehiculo'";
        $resultado_vehiculo = $conn->query($sql);

        if (!$resultado_vehiculo || $resultado_vehiculo->num_rows === 0) {
            return ['success' => false, 'message' => 'Vehículo no encontrado.'];
        }

        $vehiculo = $resultado_vehiculo->fetch_assoc();
        $matricula = $vehiculo['matricula'];
        $vin = $vehiculo['serial_vin'];

        // Eliminar archivos de mantenimientos
        $sqlMantenimiento = "SELECT ruta_documentos FROM mantenimientos WHERE id_vehiculo = '$id_vehiculo'";
        $resultado_mantenimiento = $conn->query($sqlMantenimiento);
        $rutasAEliminar = [];

        if ($resultado_mantenimiento && $resultado_mantenimiento->num_rows > 0) {
            while ($row = $resultado_mantenimiento->fetch_assoc()) {
                if (!empty($row['ruta_documentos'])) {
                    $documentosMantenimiento = json_decode($row['ruta_documentos'], true);
                    if (is_array($documentosMantenimiento)) {
                        foreach ($documentosMantenimiento as $doc) {
                            if (isset($doc['ruta'])) {
                                $rutasAEliminar[] = $doc['ruta'];
                            }
                        }
                    }
                }
            }
            // Eliminar mantenimientos asociados
            $conn->query("DELETE FROM mantenimientos WHERE id_vehiculo = '$id_vehiculo'");
        }

        // Eliminar archivos del vehículo
        if (!empty($vehiculo['ruta_img'])) {
            $rutasAEliminar[] = $vehiculo['ruta_img'];
        }
        if (!empty($vehiculo['ruta_extras'])) {
            $extras = json_decode($vehiculo['ruta_extras'], true);
            if (is_array($extras)) {
                foreach ($extras as $ext) {
                    if (isset($ext['ruta'])) {
                        $rutasAEliminar[] = $ext['ruta'];
                    }
                }
            }
        }
        if (!empty($vehiculo['ruta_documentos'])) {
            $documentos = json_decode($vehiculo['ruta_documentos'], true);
            if (is_array($documentos)) {
                foreach ($documentos as $doc) {
                    if (isset($doc['ruta'])) {
                        $rutasAEliminar[] = $doc['ruta'];
                    }
                }
            }
        }

        // Eliminar archivos físicos
        eliminarArchivosSubidos($rutasAEliminar);

        // Eliminar asignaciones
        $conn->query("DELETE FROM asignaciones WHERE id_vehiculo = '$id_vehiculo'");

        // Eliminar vehículo
        $resultado = $conn->query("DELETE FROM vehiculos WHERE id_vehiculo = '$id_vehiculo'");

        if ($resultado) {
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            AuditLogger::registrarAccion(
                $conn,
                $_SESSION['id'],
                'VEHICULO:ELIMINAR',
                'vehiculos',
                "$id_vehiculo",
                '',
                '',
                "Se eliminó el vehículo con " . (empty($matricula) ? "el vin: $vin" : "la matricula: $matricula")
            );
            return ['success' => true, 'message' => 'Vehículo eliminado.'];
        } else {
            return ['success' => false, 'message' => 'Error: ' . $conn->error];
        }
    }

    public static function eliminarDocumento($conn, $data){
        $id_vehiculo = $data['ID'];
        $indexArchivo = $data['indexFile'];

        $sql = "SELECT ruta_documentos FROM vehiculos WHERE id_vehiculo = '$id_vehiculo'";
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
            

                $sql = "UPDATE vehiculos SET ruta_documentos = '$resultado' WHERE id_vehiculo = '$id_vehiculo'";
            

                if ($conn->query($sql)) {
                    
                    if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                    }

                    $sqlIdentificadores = "SELECT matricula, serial_vin FROM vehiculos WHERE id_vehiculo = '$id_vehiculo'";
                    $id = $conn->query($sqlIdentificadores);

                    if ($id) {
                        $id = $id->fetch_assoc();
                        $matricula = $id['matricula'];
                        $vin = $id['serial_vin'];
                    }

                    AuditLogger::registrarAccion(
                        $conn,
                        $_SESSION['id'],
                        'VEHICULO:ELIMINAR',
                        'vehiculos',
                        "$id_vehiculo",
                        '',
                        '',
                        "Se eliminó el documento: [".$doc['nombreOriginal']."] del vehiculo con " . (empty($matricula) ? "el vin: $vin" : "la matricula: $matricula")
                    );
                    return ['success' => true, 'message' => 'Se borro de forma exitosa el documento!'];
                } else {
                    return ['success' => false, 'message' => 'Ocurrio un error al tratar de borrar el documento!'];
                }
            }
        }

        return ['response' => $indexArchivo];
    }

    public static function eliminarExtra($conn, $data){
        $id_vehiculo = $data['ID'];
        $indexArchivo = $data['indexFile'];

        $sql = "SELECT ruta_extras FROM vehiculos WHERE id_vehiculo = '$id_vehiculo'";
        $resultado = $conn->query($sql);

        if (!$resultado && !$resultado->num_rows > 0) {
            return ['success' => false, 'message' => 'No se pudo encontrar la imagen en la base de datos'];
        }

        $rutaAEliminar = [];

        $resultado = $resultado->fetch_assoc();
        $resultado = json_decode($resultado['ruta_extras'], true);
        foreach ($resultado as $index => $doc) {
            if ($index == $indexArchivo) {
                $ruta = $doc['ruta'];
                
                if (empty($ruta)) {
                    return ['success' => false, 'message' => 'No se encontro la ruta de la imagen!'];
                }
                
                $rutaAEliminar[] = $ruta;
                
                eliminarArchivosSubidos($rutaAEliminar);
                
                
                unset($resultado[$index]);
                
                $resultado = array_values($resultado);

                $resultado = json_encode($resultado);
                

                $sql = "UPDATE vehiculos SET ruta_extras = '$resultado' WHERE id_vehiculo = '$id_vehiculo'";
                
                if ($conn->query($sql)) {
                    if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                    }
                    $sqlIdentificadores = "SELECT matricula, serial_vin FROM vehiculos WHERE id_vehiculo = '$id_vehiculo'";
                    $id = $conn->query($sqlIdentificadores);

                    if ($id) {
                        $id = $id->fetch_assoc();
                        $matricula = $id['matricula'];
                        $vin = $id['serial_vin'];
                    }

                    AuditLogger::registrarAccion(
                        $conn,
                        $_SESSION['id'],
                        'VEHICULO:ELIMINAR',
                        'vehiculos',
                        "$id_vehiculo",
                        '',
                        "",
                        "Se eliminó la imagen extra: [".$doc['nombreOriginal']."] del vehiculo con " . (empty($matricula) ? "el vin: $vin" : "la matricula: $matricula")
                    );
                    return ['success' => true, 'message' => 'Se borro de forma exitosa la imagen!'];
                } else {
                    return ['success' => false, 'message' => 'Ocurrio un error al tratar de borrar la imagen!'];
                }
            }
        }

        return ['response' => $indexArchivo];
    }

    public static function actualizar($conn, $data) {
        $id = mysqli_real_escape_string($conn, $data['id_vehiculo'] ?? '');
        $matricula = mysqli_real_escape_string($conn, $data['matriculaVehiculo'] ?? '');
        $vin = mysqli_real_escape_string($conn, $data['vinVehiculo'] ?? '');

        $marca = mysqli_real_escape_string($conn, $data['marcaVehiculo'] ?? '');
        $modelo = mysqli_real_escape_string($conn, $data['modeloVehiculo'] ?? '');
        $kilometraje = mysqli_real_escape_string($conn, $data['kilometrajeVehiculo'] ?? '0');
        $municipio = mysqli_real_escape_string($conn, $data['municipioVehiculo'] ?? '');
        $sede = mysqli_real_escape_string($conn, $data['sedeVehiculo'] ?? '');
        $ubicacion = mysqli_real_escape_string($conn, $data['descripcionUbicacion'] ?? '');
        
        $asignarPersonal = mysqli_real_escape_string($conn, $data['asignarPersonalVehiculo'] ?? '');

        if (empty($id) || (empty($matricula) && empty($vin))) {
            return ['success' => false, 'message' => 'Faltan datos clave.'];
        }

        $kilometraje_val = is_numeric($kilometraje) ? $kilometraje : 0;
        $municipio_val = !empty($municipio) ? "'$municipio'" : "NULL";

        // Obtener la imagen y documentos actuales
        $sql = "SELECT ruta_img, ruta_documentos, ruta_extras FROM vehiculos WHERE id_vehiculo = '$id'";
        $resultadoArchivos = $conn->query($sql);

        if ($resultadoArchivos && $resultadoArchivos->num_rows > 0) {
            $archivosActuales = $resultadoArchivos->fetch_assoc();
            $rutaImagenActual = $archivosActuales['ruta_img'];
            $rutaExtrasActuales = json_decode($archivosActuales['ruta_extras']);
            $documentosActuales = json_decode($archivosActuales['ruta_documentos'], true);
        } else {
            $rutaImagenActual = '';
            $rutaExtrasActuales = [];
            $documentosActuales = [];
        }

        $archivosParaEliminar = [];

        // Nueva imagen
        $nuevaRutaImagen = manejarSubidaArchivo('Vehicles/images/', 'fotoVehiculo');
        $rutaImagenFinal = $rutaImagenActual; // Por defecto, mantenemos la imagen actual.

        if (!empty($nuevaRutaImagen)) {
            // Si se subió una nueva imagen, la usamos como la final.
            $rutaImagenFinal = $nuevaRutaImagen;
            // Y si había una imagen antigua, la añadimos a la lista de eliminación.
            if (!empty($rutaImagenActual)) {
                $archivosParaEliminar[] = $rutaImagenActual;
            }
        }

        // Nuevas imagenes extras
        $nuevosExtras = manejarMultiplesArchivos('Vehicles/images/', 'fotosVehiculo');
        $rutaExtrasFinales = is_array($rutaExtrasActuales) ? $rutaExtrasActuales : [];
        $rutaExtrasFinales = array_merge($rutaExtrasFinales, $nuevosExtras);

        // Nuevos documentos
        $nuevosDocumentos = manejarMultiplesArchivos('Vehicles/docs/', 'archivosVehiculo');
        $documentosFinales = is_array($documentosActuales) ? $documentosActuales : [];
        $documentosFinales = array_merge($documentosFinales, $nuevosDocumentos);

        // Codificar JSON final
        $rutaExtrasFinalesJson = json_encode($rutaExtrasFinales, JSON_UNESCAPED_UNICODE);
        $documentosFinalesJson = json_encode($documentosFinales, JSON_UNESCAPED_UNICODE);

        $sqlActualizar = "UPDATE vehiculos SET
            matricula = '$matricula',
            serial_vin = '$vin',
            marca = '$marca',
            modelo = '$modelo',
            kilometraje = $kilometraje_val,
            id_municipio = $municipio_val,
            sede = '$sede',
            ruta_img = '$rutaImagenFinal',
            ruta_extras = '$rutaExtrasFinalesJson',
            ruta_documentos = '$documentosFinalesJson',
            ubicacion = '$ubicacion'
        WHERE id_vehiculo = '$id'";


        // Comprobamos si esta marcado la casilla de asignar personal
        if (!empty($asignarPersonal)) {
            // Construimos la cedula
            $cedulaPrefijo = mysqli_real_escape_string($conn, $data['prefijoCedulaPersonalAsignado'] ?? '');
            $cedula = mysqli_real_escape_string($conn, $data['cedulaPersonalAsignado'] ?? '');
            $cedulaCompleta = $cedulaPrefijo . $cedula;

            // Verificar existencia del personal
            $sql = "SELECT id_personal FROM personal WHERE documento_identidad = '$cedulaCompleta'";
            $resultado_personal = $conn->query($sql);

            if ($resultado_personal && $resultado_personal->num_rows > 0) {
                $id_personal = $resultado_personal->fetch_assoc();
                $id_personal = $id_personal['id_personal'];

                // Hacemos la peticion
                $resultado = $conn->query($sqlActualizar);
                $id_vehiculo = $id;

                // Obtenemos el ID de la asignacion (si existe)
                $sql = "SELECT id_asignacion FROM asignaciones WHERE id_vehiculo = '$id_vehiculo'";
                $resultadoAsignacion = $conn->query($sql);

                // Comprobamos su existencia
                if ($resultadoAsignacion->num_rows > 0) {
                    $idAsignacion = $resultadoAsignacion->fetch_assoc()['id_asignacion'];
                    $sql = "UPDATE asignaciones SET id_personal = '$id_personal', id_vehiculo = '$id_vehiculo' WHERE id_asignacion = '$idAsignacion'";

                    if (!$conn->query($sql)) {
                        return [
                            'success' => false,
                            'message' => "Error al actualizar la asociación",
                        ];
                    }
                } else {
                    $sql = "INSERT INTO asignaciones(id_personal, id_vehiculo) VALUES('$id_personal', '$id_vehiculo')";
                    $resultado_asignacion = $conn->query($sql);
    
                    if (!$resultado_asignacion) {
                        return ['success' => false, 'message' => 'Error al asignar el personal.'];
                    }
                }

            } else {
                return ['success' => false, 'message' => 'El documento de identidad ingresado no existe.'];
            }
        } else {
            $resultado = $conn->query($sqlActualizar);
            $id_vehiculo = $id;

            // Obtenemos el ID de la asignacion (si existe)
            $sql = "SELECT id_asignacion FROM asignaciones WHERE id_vehiculo = '$id_vehiculo'";
            $resultadoAsignacion = $conn->query($sql);
            
            // Comprobamos su existencia
            if ($resultadoAsignacion->num_rows > 0) {
                $id_asignacion = $resultadoAsignacion->fetch_assoc()['id_asignacion'];
                $sql = "DELETE FROM asignaciones WHERE id_asignacion = '$id_asignacion'";
                $conn->query($sql);
            }

        }

        if (!empty($archivosParaEliminar)) {
            eliminarArchivosSubidos($archivosParaEliminar);
        }

        if ($resultado) {
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            # Registrar acción de auditoría
            AuditLogger::registrarAccion(
                $conn,
                $_SESSION['id'],
                'VEHICULO:MODIFY',
                'vehiculos',
                "$id_vehiculo",
                '',
                '',
                "Se actualizó el vehículo con " . (empty($matricula) ? "el vin: $vin" : "la matricula: $matricula")
            );

            if (!empty($archivosParaEliminar)) {
                eliminarArchivosSubidos($archivosParaEliminar);
            }
        }

        return [
            'success' => (bool)$resultado,
            'message' => $resultado ? 'Vehículo actualizado.' : 'Error: ' . $conn->error
        ];
    }

    public static function obtenerTodos($conn) {
        $vehiculos = [];
        $sql = "SELECT 
                    v.id_vehiculo, v.matricula, v.serial_vin, v.modelo, v.marca,
                    v.kilometraje, v.ruta_img, mun.municipio, est.estado,
                    v.sede, DATE_FORMAT(v.fecha_agregado, '%d/%m/%Y') as fecha_agregado,
                    (SELECT COUNT(*) FROM asignaciones a WHERE a.id_vehiculo = v.id_vehiculo) > 0 as asignado,
                    (SELECT COUNT(*) FROM mantenimientos m WHERE m.id_vehiculo = v.id_vehiculo) > 0 as en_mantenimiento
                FROM vehiculos v
                LEFT JOIN municipios mun ON v.id_municipio = mun.id_municipio
                LEFT JOIN estados est ON mun.id_estado = est.id_estado
                ORDER BY v.fecha_agregado DESC";

        $resultado = $conn->query($sql);
        if ($resultado) {
            while ($row = $resultado->fetch_assoc()) {
                $vehiculos[] = $row;
            }
        }
        return $vehiculos;
    }
}

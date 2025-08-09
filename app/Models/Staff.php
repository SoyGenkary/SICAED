<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/SICAED/app/Services/FileManager.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/SICAED/app/Models/AuditLogger.php';

class Staff {

    /**
     * Crea un nuevo registro de personal o actualiza uno existente para ser conductor.
     * Maneja la subida de archivos y la asignación de vehículos.
     */
    public static function crear($conn, $data) {
        // Opción 1: Usar un registro de personal existente y convertirlo en conductor.
        if (!empty($data['usarPersonalExistenteConductor'])) {
            $cedulaPrefijo = mysqli_real_escape_string($conn, $data['prefijoCedulaPersonalExistenteConductor'] ?? '');
            $cedula = mysqli_real_escape_string($conn, $data['cedulaPersonalExistenteConductor'] ?? '');
            $cedulaCompleta = $cedulaPrefijo . $cedula;

            // Verificar que el personal exista
            $sql = "SELECT id_personal, ruta_documentos FROM personal WHERE documento_identidad = '$cedulaCompleta'";
            $resultado_personal = $conn->query($sql);

            if ($resultado_personal && $resultado_personal->num_rows > 0) {
                $personalActual = $resultado_personal->fetch_assoc();
                $documentosActuales = json_decode($personalActual['ruta_documentos'], true) ?: [];
                
                // Manejar y fusionar nuevos documentos
                $nuevosDocumentos = manejarMultiplesArchivos('Staff/docs/', 'documentos');
                $documentosFinales = json_encode(array_merge($documentosActuales, $nuevosDocumentos));

                $sql = "UPDATE personal SET ruta_documentos = '$documentosFinales', cargo = 'Conductor' WHERE documento_identidad = '$cedulaCompleta'";
                $resultado = $conn->query($sql);

                if ($resultado) {
                    if (session_status() == PHP_SESSION_NONE) {
                        session_start();
                    }
                    AuditLogger::registrarAccion(
                        $conn,
                        $_SESSION['id'],
                        'PERSONAL:ACTUALIZAR',
                        'personal',
                        $personalActual['id_personal'],
                        '',
                        '',
                        "Se actualizó el personal con CI {$cedulaCompleta} para ser Conductor."
                    );
                }

                return [
                    'success' => (bool)$resultado,
                    'message' => $resultado ? 'El personal ha sido asignado como conductor exitosamente.' : 'Ocurrió un error al actualizar el personal.'
                ];
            } else {
                return ['success' => false, 'message' => 'El documento de identidad ingresado no existe.'];
            }
        }

        // Opción 2: Crear un nuevo registro de personal.
        $prefijoCedula = mysqli_real_escape_string($conn, $data['prefijoCedulaPersonal'] ?? $data['prefijoCedulaConductor']);
        $cedula = mysqli_real_escape_string($conn, $data['cedulaPersonal'] ?? $data['cedulaConductor']);
        $cedulaCompleta = $prefijoCedula . $cedula;

        // Verificar si el personal ya existe
        $sql = "SELECT id_personal FROM personal WHERE documento_identidad = '$cedulaCompleta'";
        if ($conn->query($sql)->num_rows > 0) {
            return ['success' => false, 'message' => 'Este número de documento ya está registrado.'];
        }

        $nombre = mysqli_real_escape_string($conn, $data['nombrePersonal'] ?? $data['nombreConductor']);
        $apellido = mysqli_real_escape_string($conn, $data['apellidoPersonal'] ?? $data['apellidoConductor']);
        $telefono = mysqli_real_escape_string($conn, $data['telefonoPersonal'] ?? $data['telefonoConductor']);
        $municipio = mysqli_real_escape_string($conn, $data['municipioPersonal'] ?? $data['municipioConductor']);
        $email = mysqli_real_escape_string($conn, $data['emailPersonal'] ?? $data['emailConductor']);
        $cargo = isset($data['cargoPersonal']) ? mysqli_real_escape_string($conn, $data['cargoPersonal']) : 'Conductor';

        if(isset($data['descripcionUbicacionPersonal'])) {
            $ubicacion = mysqli_real_escape_string($conn, $data['descripcionUbicacionPersonal'] ?? '');
        } else if (isset($data['descripcionUbicacionConductor'])) {
            $ubicacion = mysqli_real_escape_string($conn, $data['descripcionUbicacionConductor'] ?? '');
        }

        
        $rutaFoto = manejarSubidaArchivo('Staff/images/', 'foto');
        $rutaArchivos = json_encode(manejarMultiplesArchivos('Staff/docs/', 'documentos'));
        
        $id_email = self::obtenerOInsertar($conn, 'emails', 'email', $email);
        $id_telefono = self::obtenerOInsertar($conn, 'telefonos', 'telefono', $telefono);

        $sqlInsertarPersonal = "INSERT INTO personal (
            nombres, apellidos, documento_identidad, id_municipio, id_telefono, 
            id_email, cargo, ruta_img, ruta_documentos, ubicacion
        ) VALUES (
            '$nombre', '$apellido', '$cedulaCompleta', '$municipio', '$id_telefono',
            '$id_email', '$cargo', '$rutaFoto', '$rutaArchivos', '$ubicacion'
        )";

        $resultado = $conn->query($sqlInsertarPersonal);
        if (!$resultado) {
            // Si falla la inserción, eliminar los archivos que se hayan subido
            if($rutaFoto) eliminarArchivosSubidos([$rutaFoto]);
            if(!empty(json_decode($rutaArchivos, true))) eliminarArchivosSubidos(array_column(json_decode($rutaArchivos, true), 'ruta'));
            return ['success' => false, 'message' => 'Error al crear el personal: ' . $conn->error];
        }
        
        $id_personal_creado = $conn->insert_id;

        // Si se asigna un vehículo al crearlo (solo para conductores)
        if ($cargo === 'Conductor' && !empty($data['asignarVehiculoConductor'])) {
            $vin = '';
            $matricula = '';
            if (!empty($data['prefijoIdentificadorVehiculo'])) {
                if ($data['prefijoIdentificadorVehiculo'] === 'vin') {
                    $vin = mysqli_real_escape_string($conn, $data['matriculaVehiculoAsignado'] ?? '');
                } else if ($data['prefijoIdentificadorVehiculo'] === 'matricula') {
                    $matricula = mysqli_real_escape_string($conn, $data['matriculaVehiculoAsignado'] ?? '');
                } else {
                    return ['success' => false, 'message' => 'Prefijo de identificación (matricula/vin) no válido.'];
                }
            }

            if (!empty($vin)){
                $condicional = "WHERE serial_vin = '$vin'";
            } else if (!empty($matricula)) {
                $condicional = "WHERE matricula = '$matricula'";
            }

            $sql = "SELECT id_vehiculo FROM vehiculos $condicional";
            $resultado_vehiculo = $conn->query($sql);

            if ($resultado_vehiculo && $resultado_vehiculo->num_rows > 0) {
                $id_vehiculo = $resultado_vehiculo->fetch_assoc()['id_vehiculo'];
                $sqlAsignacion = "INSERT INTO asignaciones(id_personal, id_vehiculo) VALUES('$id_personal_creado', '$id_vehiculo')";
                
                if (!$conn->query($sqlAsignacion)) {
                     // Aunque falle la asignación, el personal ya fue creado. Se informa del error.
                    return ['success' => true, 'message' => 'Personal creado, pero hubo un error al asignar el vehículo: ' . $conn->error];
                }
            } else {
                return ['success' => true, 'message' => 'Personal creado, pero la matrícula del vehículo no fue encontrada.'];
            }
        }

        
        if ($resultado) { // Asegurarse que la creación inicial fue exitosa
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            AuditLogger::registrarAccion(
                $conn,
                $_SESSION['id'],
                'PERSONAL:CREAR',
                'personal',
                $id_personal_creado,
                null,
                '',
                "Se creó el personal {$nombre} {$apellido} con CI {$cedulaCompleta}."
            );
        }

        return [
            'success' => true,
            'message' => 'Personal creado exitosamente.'
        ];
    }

    /**
     * Obtiene los datos de un miembro del personal por su documento de identidad.
     */
    public static function obtener($conn, $data) {
        $prefijo = mysqli_real_escape_string($conn, $data['prefijoCedulaPersonal'] ?? $data['prefijoCedulaConductor'] ?? '');
        $cedula = mysqli_real_escape_string($conn, $data['searchIdInput'] ?? '');
        $cedulaCompleta = $prefijo . $cedula;

        $sql = "SELECT 
                    p.id_personal, m.id_estado, p.id_municipio,
                    p.nombres, p.apellidos, p.documento_identidad,
                    t.telefono, e.email, p.cargo, p.ruta_img, p.ruta_documentos,
                    p.ubicacion
                FROM personal p
                JOIN municipios m ON p.id_municipio = m.id_municipio
                JOIN telefonos t ON p.id_telefono = t.id_telefono
                JOIN emails e ON p.id_email = e.id_email
                WHERE p.documento_identidad = '$cedulaCompleta'";

        $res = $conn->query($sql);
        if ($res && $res->num_rows > 0) {
            $row = $res->fetch_assoc();
            $documento = $row['documento_identidad'];
            $prefijoDoc = substr($documento, 0, 2);
            $cedulaSinPrefijo = substr($documento, 2);
            
            $esPersonalGeneral = $data['section'] === 'personal';
            $tipo = $esPersonalGeneral ? 'Personal' : 'Conductor';

            $respuesta = [
                'id_personal' => $row['id_personal'],
                'estadoSede' . $tipo => $row['id_estado'],
                'municipio' . $tipo => $row['id_municipio'],
                'nombre' . $tipo => $row['nombres'],
                'apellido' . $tipo => $row['apellidos'],
                'cedula' . $tipo => $cedulaSinPrefijo,
                'telefono' . $tipo => $row['telefono'],
                'email' . $tipo => $row['email'],
                'prefijoCedula' . $tipo => $prefijoDoc,
                'foto_existente' => $row['ruta_img'],
                'documentos_existentes' => json_decode($row['ruta_documentos'], true) ?: [],
            ];

            if ($esPersonalGeneral) {
                $respuesta['descripcionUbicacionPersonal'] = $row['ubicacion']; 
                $respuesta['cargoPersonal'] = $row['cargo'];
            } else {
                $respuesta['descripcionUbicacionConductor'] = $row['ubicacion']; 
            }

            // --- Lógica para verificar si tiene un vehículo asignado ---
            $id_personal_actual = mysqli_real_escape_string($conn, $row['id_personal']); // Escapar por seguridad
            $sqlAsignacion = "SELECT 
                                v.matricula, 
                                v.serial_vin 
                            FROM asignaciones a 
                            JOIN vehiculos v ON a.id_vehiculo = v.id_vehiculo 
                            WHERE a.id_personal = '$id_personal_actual'";
            
            $resAsignacion = $conn->query($sqlAsignacion);

            if ($resAsignacion && $resAsignacion->num_rows > 0) {
                $asignacion = $resAsignacion->fetch_assoc();
                
                // Establece la bandera de que hay un vehículo asignado
                $respuesta['asignarVehiculoConductor'] = true; 
                
                // Determina qué identificador usar (matrícula o VIN) y su prefijo
                if (!empty($asignacion['matricula'])) {
                    $respuesta['matriculaVehiculoAsignado'] = $asignacion['matricula'];
                    $respuesta['prefijoIdentificadorVehiculo'] = 'matricula';
                } else {
                    $respuesta['matriculaVehiculoAsignado'] = $asignacion['serial_vin'];
                    $respuesta['prefijoIdentificadorVehiculo'] = 'vin';
                }
            } else {
                // Si no tiene asignación, estas claves deben existir pero vacías o falsas
                $respuesta['asignarVehiculoConductor'] = false;
                $respuesta['matriculaVehiculoAsignado'] = '';
                $respuesta['prefijoIdentificadorVehiculo'] = '';
            }

            return ['success' => true, 'data' => $respuesta];
        }

        return ['success' => false, 'message' => 'Personal no encontrado.'];
    }

    /**
     * Elimina un miembro del personal, sus archivos asociados y cualquier asignación de vehículo.
     */
    public static function eliminar($conn, $data) {
        $prefijo = mysqli_real_escape_string($conn, $data['prefijoCedulaPersonal'] ?? $data['prefijoCedulaConductor'] ?? '');
        $cedula = mysqli_real_escape_string($conn, $data['cedulaPersonal'] ?? $data['cedulaConductor'] ?? '');
        $cedulaCompleta = $prefijo . $cedula;

        if (empty($cedulaCompleta)) {
            return ['success' => false, 'message' => 'Documento de identidad no proporcionado.'];
        }

        $sql = "SELECT id_personal, ruta_img, ruta_documentos, cargo FROM personal WHERE documento_identidad = '$cedulaCompleta'";
        $res = $conn->query($sql);

        if (!$res || $res->num_rows === 0) {
            return ['success' => false, 'message' => 'Personal no encontrado.'];
        }

        $personal = $res->fetch_assoc();
        $id_personal = $personal['id_personal'];

        // Recolectar archivos para eliminar
        $rutasAEliminar = [];
        if (!empty($personal['ruta_img'])) $rutasAEliminar[] = $personal['ruta_img'];
        if (!empty($personal['ruta_documentos'])) {
            $documentos = json_decode($personal['ruta_documentos'], true);
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
        $conn->query("DELETE FROM asignaciones WHERE id_personal = '$id_personal'");

        // Eliminar registro del personal
        $sqlDelete = "DELETE FROM personal WHERE id_personal = '$id_personal'";
        $resultadoFinal = $conn->query($sqlDelete);

        if ($resultadoFinal) {
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            AuditLogger::registrarAccion(
                $conn,
                $_SESSION['id'],
                'PERSONAL:ELIMINAR',
                'personal',
                $id_personal,
                '',
                '',
                "Se eliminó el personal con CI {$cedulaCompleta}."
            );
        }

        return [
            'success' => (bool)$resultadoFinal,
            'message' => $resultadoFinal ? 'Personal eliminado exitosamente.' : 'Error al eliminar: ' . $conn->error,
            'data' => ['cedula' => $cedulaCompleta, 'cargo' => $personal['cargo']] // Para actualizar UI
        ];
    }

    /**
     * Elimina un miembro del personal por su ID, junto con sus archivos y asignaciones.
     */
    public static function eliminarPorId($conn, $data) {
        $id_personal = mysqli_real_escape_string($conn, $data['id_personal']);

        // Obtener datos y rutas de archivos
        $sql = "SELECT documento_identidad, ruta_img, ruta_documentos, cargo FROM personal WHERE id_personal = '$id_personal'";
        $res = $conn->query($sql);

        if (!$res || $res->num_rows === 0) {
            return ['success' => false, 'message' => 'Personal no encontrado.'];
        }

        $personal = $res->fetch_assoc();
        $cedulaCompleta = $personal['documento_identidad'];

        // Recolectar archivos para eliminar
        $rutasAEliminar = [];
        if (!empty($personal['ruta_img'])) $rutasAEliminar[] = $personal['ruta_img'];
        if (!empty($personal['ruta_documentos'])) {
            $documentos = json_decode($personal['ruta_documentos'], true);
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
        $conn->query("DELETE FROM asignaciones WHERE id_personal = '$id_personal'");

        // Eliminar registro del personal
        $sqlDelete = "DELETE FROM personal WHERE id_personal = '$id_personal'";
        $resultadoFinal = $conn->query($sqlDelete);

        if ($resultadoFinal) {
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            AuditLogger::registrarAccion(
                $conn,
                $_SESSION['id'],
                'PERSONAL:ELIMINAR',
                'personal',
                $id_personal,
                '',
                '',
                "Se eliminó el personal con CI {$cedulaCompleta}."
            );
        }

        return [
            'success' => (bool)$resultadoFinal,
            'message' => $resultadoFinal ? 'Personal eliminado exitosamente.' : 'Error al eliminar: ' . $conn->error,
            'data' => ['cedula' => $cedulaCompleta, 'cargo' => $personal['cargo']]
        ];
    }

    /**
     * Actualiza los datos de un miembro del personal, incluyendo sus archivos.
     */
    public static function actualizar($conn, $data) {
        $id_personal = mysqli_real_escape_string($conn, $data['id_personal'] ?? '');
        if (empty($id_personal)) {
            return ['success' => false, 'message' => 'ID de personal no proporcionado.'];
        }
        
        // --- Recopilación de datos del formulario ---
        $nombre = mysqli_real_escape_string($conn, $data['nombrePersonal'] ?? $data['nombreConductor']);
        $apellido = mysqli_real_escape_string($conn, $data['apellidoPersonal'] ?? $data['apellidoConductor']);
        $prefijo = mysqli_real_escape_string($conn, $data['prefijoCedulaPersonal'] ?? $data['prefijoCedulaConductor']);
        $cedula = mysqli_real_escape_string($conn, $data['cedulaPersonal'] ?? $data['cedulaConductor']);
        $cedulaCompleta = $prefijo . $cedula;
        $telefono = mysqli_real_escape_string($conn, $data['telefonoPersonal'] ?? $data['telefonoConductor']);
        $municipio = mysqli_real_escape_string($conn, $data['municipioPersonal'] ?? $data['municipioConductor']);
        $email = mysqli_real_escape_string($conn, $data['emailPersonal'] ?? $data['emailConductor']);
        $cargo = isset($data['cargoPersonal']) ? mysqli_real_escape_string($conn, $data['cargoPersonal']) : 'Conductor';

        // --- Lógica de manejo de archivos ---
        
        // 1. Obtener rutas actuales de la base de datos
        $sqlArchivos = "SELECT ruta_img, ruta_documentos FROM personal WHERE id_personal = '$id_personal'";
        $resArchivos = $conn->query($sqlArchivos);
        $archivosActuales = $resArchivos->fetch_assoc();
        $rutaImagenActual = $archivosActuales['ruta_img'] ?? '';

        // 2. Manejar la nueva foto
        $rutaFotoNueva = manejarSubidaArchivo('Staff/images/', 'foto');
        $rutaFotoFinal = $rutaImagenActual; // Por defecto, mantener la imagen actual

        if (!empty($rutaFotoNueva)) {
            $rutaFotoFinal = $rutaFotoNueva; // Si se subió una nueva, usar la nueva ruta
            if (!empty($rutaImagenActual)) {
                // Eliminar la imagen anterior para no dejar basura en el servidor
                eliminarArchivosSubidos([$rutaImagenActual]);
            }
        }

        // 3. Manejar los nuevos documentos
        $documentosActuales = json_decode($archivosActuales['ruta_documentos'], true) ?: [];
        $nuevosDocumentos = manejarMultiplesArchivos('Staff/docs/', 'documentos');
        // Fusionar documentos existentes con los nuevos
        $documentosFinales = json_encode(array_merge($documentosActuales, $nuevosDocumentos));

        // --- Actualización de la base de datos ---
        $id_email = self::obtenerOInsertar($conn, 'emails', 'email', $email);
        $id_telefono = self::obtenerOInsertar($conn, 'telefonos', 'telefono', $telefono);

        $sqlActualizar = "UPDATE personal SET
            nombres = '$nombre', apellidos = '$apellido', documento_identidad = '$cedulaCompleta',
            id_municipio = '$municipio', id_telefono = '$id_telefono', id_email = '$id_email',
            cargo = '$cargo', ruta_img = '$rutaFotoFinal', ruta_documentos = '$documentosFinales'
        WHERE id_personal = '$id_personal'";
        
        $resultado = $conn->query($sqlActualizar);
        if (!$resultado) {
             return ['success' => false, 'message' => 'Error al actualizar los datos del personal: ' . $conn->error];
        }

        // --- Lógica de asignación de vehículo ---
        if ($cargo === 'Conductor') {
            if (!empty($data['asignarVehiculoConductor'])) {
                $vin = '';
                $matricula = '';
                if (!empty($data['prefijoIdentificadorVehiculo'])) {
                    if ($data['prefijoIdentificadorVehiculo'] === 'vin') {
                        $vin = mysqli_real_escape_string($conn, $data['matriculaVehiculoAsignado'] ?? '');
                    } else if ($data['prefijoIdentificadorVehiculo'] === 'matricula') {
                        $matricula = mysqli_real_escape_string($conn, $data['matriculaVehiculoAsignado'] ?? '');
                    } else {
                        return ['success' => false, 'message' => 'Prefijo de identificación (matricula/vin) no válido.'];
                    }
                }

                $condicional = '';

                if (!empty($vin)){
                    $condicional = "WHERE serial_vin = '$vin'";
                } else if (!empty($matricula)) {
                    $condicional = "WHERE matricula = '$matricula'";
                }

                $sqlVehiculo = "SELECT id_vehiculo FROM vehiculos $condicional";
                $resVehiculo = $conn->query($sqlVehiculo);

                if ($resVehiculo && $resVehiculo->num_rows > 0) {
                    $id_vehiculo = $resVehiculo->fetch_assoc();
                    $id_vehiculo = $id_vehiculo['id_vehiculo'];
                    $sqlAsignacion = "SELECT id_asignacion FROM asignaciones WHERE id_personal = '$id_personal'";
                    $resAsignacion = $conn->query($sqlAsignacion);

                    if ($resAsignacion && $resAsignacion->num_rows > 0) { // Actualizar asignación
                        $id_asignacion = $resAsignacion->fetch_assoc()['id_asignacion'];
                        $conn->query("UPDATE asignaciones SET id_vehiculo = '$id_vehiculo' WHERE id_asignacion = '$id_asignacion'");
                    } else { // Crear nueva asignación
                        $conn->query("INSERT INTO asignaciones(id_personal, id_vehiculo) VALUES('$id_personal', '$id_vehiculo')");
                    }
                } else {
                    return ['success' => false, 'message' => "No se encontro el vehiculo con ese VIN/Matricula!"];
                }
            } else { // Si se desmarca la casilla, eliminar asignación
                $conn->query("DELETE FROM asignaciones WHERE id_personal = '$id_personal'");
            }
        } else { // Si el cargo cambia y ya no es Conductor, eliminar asignación
            $conn->query("DELETE FROM asignaciones WHERE id_personal = '$id_personal'");
        }
        
        if ($resultado) {
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            AuditLogger::registrarAccion(
                $conn,
                $_SESSION['id'],
                'PERSONAL:ACTUALIZAR',
                'personal',
                $id_personal,
                '',
                '',
                "Se actualizaron los datos del personal con CI {$cedulaCompleta}."
            );
        }

        return ['success' => true, 'message' => 'Personal actualizado exitosamente.'];
    }

    public static function eliminarDocumento($conn, $data){
        $id_personal = $data['ID'];
        $indexArchivo = $data['indexFile'];

        $sql = "SELECT ruta_documentos FROM personal WHERE id_personal = '$id_personal'";
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

                $sql = "UPDATE personal SET ruta_documentos = '$resultado' WHERE id_personal = '$id_personal'";
                
                if ($conn->query($sql)) {
                    if (session_status() == PHP_SESSION_NONE) {
                        session_start();
                    }

                    $sqlIdentificadores = "SELECT documento_identidad FROM personal WHERE id_personal = '$id_personal'";
                    $id = $conn->query($sqlIdentificadores);

                    if ($id) {
                        $id = $id->fetch_assoc();
                        $cedula = $id['documento_identidad'];
                    }

                    AuditLogger::registrarAccion(
                        $conn,
                        $_SESSION['id'],
                        'VEHICULO:ELIMINAR',
                        'vehiculos',
                        "$id_personal",
                        '',
                        '',
                        "Se eliminó el documento: [".$doc['nombreOriginal']."] del personal: $cedula");
                    return ['success' => true, 'message' => 'Se borro de forma exitosa el documento!'];
                } else {
                    return ['success' => false, 'message' => 'Ocurrio un error al tratar de borrar el documento!'];
                }
            }
        }

        return ['response' => $indexArchivo];
    }

    /**
     * Obtiene una lista de todo el personal.
     */
    public static function obtenerTodos($conn) {
        $sql = "SELECT 
                    p.id_personal, p.nombres, p.apellidos, p.documento_identidad,
                    p.ruta_img, p.cargo,
                    t.telefono, e.email, mun.municipio, est.estado,
                    (SELECT COUNT(*) FROM asignaciones a WHERE a.id_personal = p.id_personal) > 0 as asignado
                FROM personal p
                LEFT JOIN telefonos t ON p.id_telefono = t.id_telefono
                LEFT JOIN emails e ON p.id_email = e.id_email
                LEFT JOIN municipios mun ON p.id_municipio = mun.id_municipio
                LEFT JOIN estados est ON mun.id_estado = est.id_estado
                ORDER BY p.nombres, p.apellidos";

        $res = $conn->query($sql);
        $personal = [];
        if ($res) {
            while ($row = $res->fetch_assoc()) {
                $personal[] = $row;
            }
        }
        return $personal;
    }

    /**
     * Función auxiliar para obtener el ID de un registro (email/teléfono) o crearlo si no existe.
     */
    private static function obtenerOInsertar($conn, $tabla, $campo, $valor) {
        if(empty($valor)) return 'NULL';
        
        $valorEscapado = mysqli_real_escape_string($conn, $valor);
        $id_campo = "id_" . rtrim($tabla, 's'); // ej: id_email, id_telefono

        $sql = "SELECT $id_campo FROM $tabla WHERE $campo = '$valorEscapado'";
        $res = $conn->query($sql);

        if ($res && $res->num_rows > 0) {
            return $res->fetch_assoc()[$id_campo];
        } else {
            $conn->query("INSERT INTO $tabla($campo) VALUES('$valorEscapado')");
            return $conn->insert_id;
        }
    }
}

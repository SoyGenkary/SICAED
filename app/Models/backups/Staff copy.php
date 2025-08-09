<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/SICAED/app/Services/FileManager.php';

class Staff {

    public static function crear($conn, $data) {
        if (!empty($data['usarPersonalExistenteConductor'])){
            $rutaArchivos = json_encode(manejarMultiplesArchivos('Staff/docs/', 'documentos'));

            // Construimos la cedula
            $cedulaPrefijo = mysqli_real_escape_string($conn, $data['prefijoCedulaPersonalExistenteConductor'] ?? '');
            $cedula = mysqli_real_escape_string($conn, $data['cedulaPersonalExistenteConductor'] ?? '');
            $cedulaCompleta = $cedulaPrefijo . $cedula;

            // Verificar existencia del personal
            $sql = "SELECT id_personal FROM personal WHERE documento_identidad = '$cedulaCompleta'";
            $resultado_personal = $conn->query($sql);

            if ($resultado_personal && $resultado_personal->num_rows > 0) {
                $sql = "UPDATE personal SET ruta_documentos = '$rutaArchivos', cargo = 'Conductor' WHERE documento_identidad = '$cedulaCompleta'";

                if ($conn->query($sql)) {
                    return [
                        'success' => true,
                        'message' => 'Se ha agregado el conductor de forma exitosa!'
                    ];
                } else {
                    return [
                        'success' => false,
                        'message' => 'Ocurrio un error a la hora de registrar al conductor!'
                    ];
                }
            } else {
                return [
                    'success' => false,
                    'message' => 'El documento de identidad ingresado no existe.'
                ];
            }
        }

        $nombre = mysqli_real_escape_string($conn, $data['nombrePersonal'] ?? $data['nombreConductor']);
        $apellido = mysqli_real_escape_string($conn, $data['apellidoPersonal'] ?? $data['apellidoConductor']);
        $prefijoCedula = mysqli_real_escape_string($conn, $data['prefijoCedulaPersonal'] ?? $data['prefijoCedulaConductor']);
        $cedula = mysqli_real_escape_string($conn, $data['cedulaPersonal'] ?? $data['cedulaConductor']);
        $cedulaCompleta = $prefijoCedula . $cedula;
        $telefono = mysqli_real_escape_string($conn, $data['telefonoPersonal'] ?? $data['telefonoConductor']);
        $municipio = mysqli_real_escape_string($conn, $data['municipioPersonal'] ?? $data['municipioConductor']);
        $email = mysqli_real_escape_string($conn, $data['emailPersonal'] ?? $data['emailConductor']);
        $cargo = isset($data['cargoPersonal']) ? mysqli_real_escape_string($conn, $data['cargoPersonal']) : 'Conductor';

        $rutaFoto = manejarSubidaArchivo('Staff/images/', 'foto');
        $rutaArchivos = json_encode(manejarMultiplesArchivos('Staff/docs/', 'documentos'));

        $id_email = self::obtenerOInsertar($conn, 'emails', 'email', $email);
        $id_telefono = self::obtenerOInsertar($conn, 'telefonos', 'telefono', $telefono);

        if (!empty($data['asignarVehiculoConductor'])) {
            // Construimos la cedula
            $matricula = mysqli_real_escape_string($conn, $data['matriculaVehiculoAsignado'] ?? '');

            // Verificar existencia del vehiculo
            $sql = "SELECT id_vehiculo FROM vehiculos WHERE matricula = '$matricula'";
            $resultado_vehiculo = $conn->query($sql);

            if ($resultado_vehiculo && $resultado_vehiculo->num_rows > 0) {
                $id_vehiculo = $resultado_vehiculo->fetch_assoc();
                $id_vehiculo = $id_vehiculo['id_vehiculo'];

                // Insertamos el documento
                $sql = "INSERT INTO personal (
                    nombres, apellidos, documento_identidad,
                    id_municipio, id_telefono, id_email, cargo, ruta_img,
                    ruta_documentos
                ) VALUES (
                    '$nombre', '$apellido', '$cedulaCompleta',
                    '$municipio', '$id_telefono', '$id_email', '$cargo', '$rutaFoto',
                    '$rutaArchivos'
                )";

                $res = $conn->query($sql);

                if (!$res) {
                    return [
                        'success' => false,
                        'message' => "Hubo un error a la hora de registrar el personal!",
                    ];
                }

                // Verificar existencia del personal
                $sql = "SELECT id_personal FROM personal WHERE documento_identidad = '$cedulaCompleta'";
                $resultado_personal = $conn->query($sql);

                // Hacemos la peticion
                $resultado_personal = $resultado_personal->fetch_assoc();
                $id_personal = $resultado_personal['id_personal'];

                // Obtenemos el ID de la asignacion (si existe)
                $sql = "SELECT id_asignacion FROM asignaciones WHERE id_personal = '$id_personal' AND id_vehiculo = '$id_vehiculo'";
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
                        return ['success' => false, 'message' => 'Error al asignar el vehiculo.'];
                    }
                }

            } else {
                return ['success' => false, 'message' => 'La matricula ingresada no existe.'];
            }
        } else {
            $sql = "INSERT INTO personal (
                        nombres, apellidos, documento_identidad,
                        id_municipio, id_telefono, id_email, cargo, ruta_img,
                        ruta_documentos
                    ) VALUES (
                        '$nombre', '$apellido', '$cedulaCompleta',
                        '$municipio', '$id_telefono', '$id_email', '$cargo', '$rutaFoto',
                        '$rutaArchivos'
                    )";
    
            $res = $conn->query($sql);
            if (!$res) {
                    return [
                        'success' => false,
                        'message' => "Hubo un error a la hora de registrar el personal!",
                    ];
                }
        }


        return [
            'success' => $res,
            'message' => $res ? 'Personal creado exitosamente.' : 'Error al crear: ' . $conn->error,
            'data' => $data
        ];
    }

    public static function obtener($conn, $data) {
        $prefijo = mysqli_real_escape_string($conn, $data['prefijoCedulaPersonal'] ?? $data['prefijoCedulaConductor'] ?? '');
        $cedula = mysqli_real_escape_string($conn, $data['searchIdInput'] ?? '');
        $cedulaCompleta = $prefijo . $cedula;

        $sql = "SELECT 
                    p.id_personal, m.id_estado, p.id_municipio,
                    p.nombres, p.apellidos, p.documento_identidad,
                    t.telefono, e.email, p.cargo
                FROM personal p
                JOIN municipios m ON p.id_municipio = m.id_municipio
                JOIN telefonos t ON p.id_telefono = t.id_telefono
                JOIN emails e ON p.id_email = e.id_email
                WHERE p.documento_identidad = '$cedulaCompleta'";

        $res = $conn->query($sql);
        if ($res && $res->num_rows > 0) {
            $row = $res->fetch_assoc();
            $documento = $row['documento_identidad'];
            $prefijo = substr($documento, 0, 2);
            $cedulaSinPrefijo = substr($documento, 2);
            $desdePersonal = $data['section'] === 'personal';
            $tipo = $desdePersonal ? 'Personal' : 'Conductor';

            $respuesta = [
                'id_personal' => $row['id_personal'],
                'estadoSede' . $tipo => $row['id_estado'],
                'municipio' . $tipo => $row['id_municipio'],
                'nombre' . $tipo => $row['nombres'],
                'apellido' . $tipo => $row['apellidos'],
                'cedula' . $tipo => $cedulaSinPrefijo,
                'telefono' . $tipo => $row['telefono'],
                'email' . $tipo => $row['email'],
                'prefijoCedula' . $tipo => $prefijo
            ];

            if ($desdePersonal) {
                $respuesta['cargoPersonal'] = $row['cargo'];
            }

            return ['success' => true, 'data' => $respuesta];
        }

        return ['success' => false, 'message' => 'Personal no encontrado.'];
    }

    public static function eliminar($conn, $data) {
        $prefijo = mysqli_real_escape_string($conn, $data['prefijoCedulaPersonal'] ?? $data['prefijoCedulaConductor']);
        $cedula = mysqli_real_escape_string($conn, $data['cedulaPersonal'] ?? $data['cedulaConductor']);
        $cedulaCompleta = $prefijo . $cedula;

        $cargo = null;
        $sqlCargo = "SELECT cargo FROM personal WHERE documento_identidad = '$cedulaCompleta'";
        $resCargo = $conn->query($sqlCargo);
        if ($resCargo && $resCargo->num_rows > 0) {
            $cargo = $resCargo->fetch_assoc()['cargo'];
        }

        $sql = "DELETE FROM personal WHERE documento_identidad = '$cedulaCompleta'";
        $res = $conn->query($sql);

        return [
            'success' => $res,
            'message' => $res ? 'Personal eliminado.' : 'Error al eliminar: ' . $conn->error,
            'data' => ['cedula' => $cedulaCompleta, 'cargo' => $cargo]
        ];
    }

    public static function actualizar($conn, $data) {
        $id = mysqli_real_escape_string($conn, $data['id_personal']);
        $nombre = mysqli_real_escape_string($conn, $data['nombrePersonal'] ?? $data['nombreConductor']);
        $apellido = mysqli_real_escape_string($conn, $data['apellidoPersonal'] ?? $data['apellidoConductor']);
        $prefijo = mysqli_real_escape_string($conn, $data['prefijoCedulaPersonal'] ?? $data['prefijoCedulaConductor']);
        $cedula = mysqli_real_escape_string($conn, $data['cedulaPersonal'] ?? $data['cedulaConductor']);
        $cedulaCompleta = $prefijo . $cedula;
        $telefono = mysqli_real_escape_string($conn, $data['telefonoPersonal'] ?? $data['telefonoConductor']);
        $municipio = mysqli_real_escape_string($conn, $data['municipioPersonal'] ?? $data['municipioConductor']);
        $email = mysqli_real_escape_string($conn, $data['emailPersonal'] ?? $data['emailConductor']);
        $cargo = isset($data['cargoPersonal']) ? mysqli_real_escape_string($conn, $data['cargoPersonal']) : 'Conductor';

        $id_email = self::obtenerOInsertar($conn, 'emails', 'email', $email);
        $id_telefono = self::obtenerOInsertar($conn, 'telefonos', 'telefono', $telefono);

        $sql = "UPDATE personal SET
                    nombres = '$nombre',
                    apellidos = '$apellido',
                    documento_identidad = '$cedulaCompleta',
                    id_municipio = '$municipio',
                    id_telefono = '$id_telefono',
                    id_email = '$id_email',
                    cargo = '$cargo'
                WHERE id_personal = '$id'";

        $res = $conn->query($sql);

        return [
            'success' => $res,
            'message' => $res ? 'Personal actualizado.' : 'Error al actualizar: ' . $conn->error,
            'data' => $data
        ];
    }

    public static function obtenerTodos($conn) {
        $sql = "SELECT 
                    p.id_personal, p.nombres, p.apellidos, p.documento_identidad,
                    t.telefono, e.email, p.cargo,
                    mun.municipio, est.estado
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

    private static function obtenerOInsertar($conn, $tabla, $campo, $valor) {
        $valor = mysqli_real_escape_string($conn, $valor);
        $sql = "SELECT id_$campo FROM $tabla WHERE $campo = '$valor'";
        $res = $conn->query($sql);

        if ($res && $res->num_rows > 0) {
            return $res->fetch_assoc()["id_$campo"];
        } else {
            $conn->query("INSERT INTO $tabla($campo) VALUES('$valor')");
            return $conn->insert_id;
        }
    }
}

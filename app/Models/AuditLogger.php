<?php

class AuditLogger {
    /**
     * Registra una acción de auditoría en la base de datos usando MySQLi.
     *
     * @param mysqli $conn La conexión MySQLi a la base de datos.
     * @param int $id_usuario El ID del usuario que realizó la acción.
     * @param string $accion El tipo de acción realizada (ej. 'INSERT', 'UPDATE', 'DELETE', 'LOGIN').
     * @param string $tabla_afectada (Opcional) El nombre de la tabla afectada.
     * @param string $campo_afectado (Opcional) El campo del registro afectado.
     * @param string|null $valor_antiguo (Opcional) El valor antiguo antes de la modificación (serializado si es necesario).
     * @param string|null $valor_nuevo (Opcional) El nuevo valor después de la modificación (serializado si es necesario).
     * @param string $descripcion Una descripción detallada de la acción.
     */
    public static function registrarAccion(
        $conn,
        $id_usuario,
        $accion,
        $tabla_afectada = '',
        $campo_afectado = '',
        $valor_antiguo = null,
        $valor_nuevo = null,
        $descripcion,
    ) {
        // Asegurarse de que los valores null se manejen correctamente para MySQLi
        $valor_antiguo = $valor_antiguo ?? ''; // MySQLi bind_param no acepta null directamente para 's'
        $valor_nuevo = $valor_nuevo ?? '';   // MySQLi bind_param no acepta null directamente para 's'

        $sql = "INSERT INTO
            registroauditorias (
            id_usuario,
            accion,
            tabla_afectada,
            campo_afectado,
            valor_antiguo,
            valor_nuevo,
            descripcion
            ) VALUES (
            $id_usuario,
            '$accion',
            '$tabla_afectada',
            '$campo_afectado',
            '$valor_antiguo',
            '$valor_nuevo',
            '$descripcion'
            )";

        // Preparar la sentencia
        $stmt = $conn->query($sql);

        if (!$stmt) {
            // Manejo de error si la preparación falla
            return ['success' => false, 'message' => 'Error al tratar de registrar la accion!.'];
        }

        return ['success' => true, 'message' => 'Registro exitoso!'];
    }

    public static function mostrarRegistro($conn, $data, $sesion = null) {
        $registrosAMostrar = mysqli_real_escape_string($conn, $data['numRegistros'] ?? 10);
        $pagActual = $data['pagActual'] ? (int)$data['pagAActual'] : 1;

        if($pagActual < 1) {
            $pagActual = 1;
        }

        $offset = ($pagActual - 1) * $registrosAMostrar;

        if (!empty($sesion)){
            $sql = "SELECT COUNT(*) AS Total FROM registroauditorias WHERE id_usuario = '$sesion'";
        } else {
            $sql = "SELECT COUNT(*) AS Total FROM registroauditorias";
        }

        $resultado = $conn->query($sql);

        if ($resultado) {
            $resultado = $resultado->fetch_assoc();
            $numTotales = $resultado['Total'];

            $totalPaginas = ceil($numTotales / $registrosAMostrar);

            if (!empty($sesion)){
                $sql = "SELECT * FROM registroauditorias LIMIT $registrosAMostrar OFFSET $offset WHERE id_usuario = '$sesion'";
            } else {
                $sql = "SELECT * FROM registroauditorias LIMIT $registrosAMostrar OFFSET $offset";
            }

            $resultado = $conn->query($sql);

            if (!$resultado) {
                return ['success' => false, 'message' => 'Error al tratar de traer los registros!.'];
            }

            $registros = [];
            if ($resultado->num_rows > 0) {
                while($row = $resultado->fetch_assoc()){
                    $registros[] = $row;
                }
            }

            return [
                'registros' => $registros,
                'pagTotales' => $totalPaginas,
            ];
        } else {
            return ['success' => false, 'message' => 'Error al tratar de traer los registros!.'];
        }
    }
}
<?php

class Maintenance {

    public static function crear($conn, $data) {
        $matricula = mysqli_real_escape_string($conn, $data['vehiculoMantenimiento'] ?? '');
        $tipo = mysqli_real_escape_string($conn, $data['tipoMantenimiento'] ?? '');
        $fecha = mysqli_real_escape_string($conn, $data['fechaMantenimiento'] ?? '');
        $costo = mysqli_real_escape_string($conn, $data['costoMantenimiento'] ?? '0');
        $taller = mysqli_real_escape_string($conn, $data['tallerMantenimiento'] ?? '');
        $descripcion = mysqli_real_escape_string($conn, $data['descripcionMantenimiento'] ?? '');

        if (empty($matricula) || empty($tipo) || empty($fecha)) {
            return ['success' => false, 'message' => 'Matrícula, tipo y fecha son obligatorios.'];
        }

        $check = $conn->query("SELECT matricula FROM vehiculos WHERE matricula = '$matricula'");
        if ($check->num_rows === 0) {
            return ['success' => false, 'message' => "Vehículo con matrícula '$matricula' no existe."];
        }

        $costo_val = is_numeric($costo) ? $costo : 0;
        $taller_val = !empty($taller) ? "'$taller'" : "NULL";
        $descripcion_val = !empty($descripcion) ? "'$descripcion'" : "NULL";

        $sql = "INSERT INTO mantenimientos (
                    matricula, tipo_mantenimiento, fecha_mantenimiento, 
                    costo, taller, descripcion
                ) VALUES (
                    '$matricula', '$tipo', '$fecha',
                    $costo_val, $taller_val, $descripcion_val
                )";

        $res = $conn->query($sql);

        return [
            'success' => (bool)$res,
            'message' => $res ? 'Mantenimiento registrado.' : 'Error al registrar: ' . $conn->error
        ];
    }

    public static function obtener($conn, $data) {
        $id = (int)($data['searchIdInput'] ?? 0);

        if ($id <= 0) {
            return ['success' => false, 'message' => 'ID no válido.'];
        }

        $sql = "SELECT 
                    id_mantenimiento,
                    matricula AS vehiculoMantenimiento,
                    tipo_mantenimiento AS tipoMantenimiento,
                    fecha_mantenimiento AS fechaMantenimiento,
                    costo AS costoMantenimiento,
                    taller AS tallerMantenimiento,
                    descripcion AS descripcionMantenimiento
                FROM mantenimientos
                WHERE id_mantenimiento = $id";

        $res = $conn->query($sql);
        if ($res && $res->num_rows > 0) {
            return ['success' => true, 'data' => $res->fetch_assoc()];
        }

        return ['success' => false, 'message' => 'Registro no encontrado.'];
    }

    public static function actualizar($conn, $data) {
        $id = (int)($data['id_mantenimiento'] ?? 0);
        $matricula = mysqli_real_escape_string($conn, $data['vehiculoMantenimiento'] ?? '');
        $tipo = mysqli_real_escape_string($conn, $data['tipoMantenimiento'] ?? '');
        $fecha = mysqli_real_escape_string($conn, $data['fechaMantenimiento'] ?? '');
        $costo = mysqli_real_escape_string($conn, $data['costoMantenimiento'] ?? '0');
        $taller = mysqli_real_escape_string($conn, $data['tallerMantenimiento'] ?? '');
        $descripcion = mysqli_real_escape_string($conn, $data['descripcionMantenimiento'] ?? '');

        if ($id <= 0 || empty($matricula) || empty($tipo) || empty($fecha)) {
            return ['success' => false, 'message' => 'ID, matrícula, tipo y fecha son obligatorios.'];
        }

        $costo_val = is_numeric($costo) ? $costo : 0;
        $taller_val = !empty($taller) ? "'$taller'" : "NULL";
        $descripcion_val = !empty($descripcion) ? "'$descripcion'" : "NULL";

        $sql = "UPDATE mantenimientos SET 
                    matricula = '$matricula',
                    tipo_mantenimiento = '$tipo',
                    fecha_mantenimiento = '$fecha',
                    costo = $costo_val,
                    taller = $taller_val,
                    descripcion = $descripcion_val
                WHERE id_mantenimiento = $id";

        $res = $conn->query($sql);

        return [
            'success' => (bool)$res,
            'message' => $res ? 'Mantenimiento actualizado.' : 'Error al actualizar: ' . $conn->error
        ];
    }

    public static function eliminar($conn, $data) {
        $id = (int)($data['id_mantenimiento'] ?? 0);

        if ($id <= 0) {
            return ['success' => false, 'message' => 'ID no válido para eliminar.'];
        }

        $sql = "DELETE FROM mantenimientos WHERE id_mantenimiento = $id";
        $res = $conn->query($sql);

        return [
            'success' => (bool)$res,
            'message' => $res ? 'Mantenimiento eliminado.' : 'Error al eliminar: ' . $conn->error
        ];
    }
}

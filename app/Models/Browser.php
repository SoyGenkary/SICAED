<?php

class Search {

    public static function ejecutarBusqueda($conn, $data) {
        $tipo = $data['tipoDeDato'] ?? 'global';
        $orden = ($data['filtroOrden'] ?? 'ascendente') === 'descendente' ? 'DESC' : 'ASC';
        $orderBy = self::construirOrderBy($tipo, $orden);

        if (str_starts_with($tipo, 'vehiculo') || $tipo === 'global') {
            return ['success' => true, 'vehiculos' => self::buscarVehiculos($conn, $data, $tipo, $orderBy)];
        } else {
            return ['success' => true, 'personal' => self::buscarPersonal($conn, $data, $tipo, $orderBy)];
        }
    }

    private static function construirOrderBy($tipo, $orden) {
        if (str_contains($tipo, 'kilometraje')) return "ORDER BY v.kilometraje $orden";
        if (str_contains($tipo, 'nombre')) return "ORDER BY p.nombres $orden";
        if (str_contains($tipo, 'marca') || str_contains($tipo, 'modelo')) return "ORDER BY v.marca, v.modelo $orden";
        return "ORDER BY fecha_agregado $orden";
    }

    private static function buscarVehiculos($conn, $data, $tipo, $orderBy) { 
        $sql = "SELECT v.*, est.estado, mun.municipio,
                       DATE_FORMAT(v.fecha_agregado, '%d/%m/%Y') as fecha_agregado,
                       (SELECT COUNT(*) FROM asignaciones a WHERE a.id_vehiculo = v.id_vehiculo) > 0 as asignado,
                       (SELECT COUNT(*) FROM mantenimientos m WHERE m.id_vehiculo = v.id_vehiculo) > 0 AS en_mantenimiento
                FROM vehiculos v
                LEFT JOIN municipios mun ON v.id_municipio = mun.id_municipio
                LEFT JOIN estados est ON mun.id_estado = est.id_estado";

        $where = '';
        $param = null;

        switch ($tipo) {
            case 'vehiculo:marca':
                $where = "WHERE v.marca LIKE ?"; $param = '%' . $data['marcaVehiculo'] . '%'; break;
            case 'vehiculo:modelo':
                $where = "WHERE v.modelo LIKE ?"; $param = '%' . $data['modeloVehiculo'] . '%'; break;
            case 'vehiculo:vin':
                $where = "WHERE v.serial_vin LIKE ?"; $param = '%' . $data['vinVehiculo'] . '%'; break;
            case 'vehiculo:matricula':
                $where = "WHERE v.matricula = ?"; $param = $data['matriculaVehiculo']; break;
            case 'vehiculo:estado':
                $where = "WHERE est.estado LIKE ?"; $param = '%' . $data['estadoVehiculo'] . '%'; break;
            case 'vehiculo:municipio':
                $where = "WHERE mun.municipio LIKE ?"; $param = '%' . $data['municipioVehiculo'] . '%'; break;
            case 'vehiculo:mantenimiento':
                $op = ($data['estadoMantenimiento'] === 'si') ? '>' : '=';
                $where = "WHERE (SELECT COUNT(*) FROM mantenimientos m WHERE m.id_vehiculo = v.id_vehiculo) $op 0"; break;
            case 'vehiculo:fecha-agregado':
                $where = "WHERE DATE(v.fecha_agregado) = ?"; $param = $data['dateAddVehiculo']; break;
            case 'vehiculo:kilometraje':
                $where = "WHERE v.kilometraje = ?"; $param = $data['kilometrajeVehiculo']; break;
            case 'global':
                $term = '%' . $conn->real_escape_string($data['search__input']) . '%';
                $where = "WHERE (v.matricula LIKE ? OR v.serial_vin LIKE ? OR v.marca LIKE ? OR v.modelo LIKE ? OR v.sede LIKE ? OR est.estado LIKE ? OR mun.municipio LIKE ?)";
                return self::ejecutarConsulta($conn, "$sql $where $orderBy", str_repeat('s', 7), array_fill(0, 7, $term));
        }

        return self::ejecutarConsulta($conn, "$sql $where $orderBy", $param !== null ? 's' : '', $param !== null ? [$param] : []);
    }

    private static function buscarPersonal($conn, $data, $tipo, $orderBy) {
        $sql = "SELECT p.*, t.telefono, e.email, est.estado, mun.municipio,
                       DATE_FORMAT(p.fecha_agregado, '%d/%m/%Y %H:%i') as fecha_agregado
                FROM personal p
                LEFT JOIN telefonos t ON p.id_telefono = t.id_telefono
                LEFT JOIN emails e ON p.id_email = e.id_email
                LEFT JOIN municipios mun ON p.id_municipio = mun.id_municipio
                LEFT JOIN estados est ON mun.id_estado = est.id_estado";

        $where = '';
        $param = null;

        switch ($tipo) {
            case 'personal:nombre':
                $where = "WHERE p.nombres LIKE ?"; $param = '%' . $data['primaryname'] . '%'; break;
            case 'personal:apellido':
                $where = "WHERE p.apellidos LIKE ?"; $param = '%' . $data['primarylastname'] . '%'; break;
            case 'personal:documento':
                $where = "WHERE p.documento_identidad = ?"; $param = ($data['tipoDNI'] ?? 'V-') . $data['DNI']; break;
            case 'personal:contacto':
                $campo = $data['tipoContacto'] === 'email' ? 'e.email' : 't.telefono';
                $where = "WHERE $campo LIKE ?"; $param = '%' . $data['contactValue'] . '%'; break;
            case 'personal:fecha-agregado':
                $where = "WHERE DATE(p.fecha_agregado) = ?"; $param = $data['dateAdd']; break;
            case 'personal:estado':
                $where = "WHERE est.estado LIKE ?"; $param = '%' . $data['estadoPersonal'] . '%'; break;
            case 'global':
                $term = '%' . $conn->real_escape_string($data['search__input']) . '%';
                $where = "WHERE (p.nombres LIKE ? OR p.apellidos LIKE ? OR p.documento_identidad LIKE ? OR p.cargo LIKE ? OR t.telefono LIKE ? OR e.email LIKE ?)";
                return self::ejecutarConsulta($conn, "$sql $where $orderBy", str_repeat('s', 6), array_fill(0, 6, $term));
        }

        return self::ejecutarConsulta($conn, "$sql $where $orderBy", $param !== null ? 's' : '', $param !== null ? [$param] : []);
    }

    private static function ejecutarConsulta($conn, $sql, $types, $params) {
        $results = [];
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            if ($types && $params) {
                $stmt->bind_param($types, ...$params);
            }
            $stmt->execute();
            $res = $stmt->get_result();
            if ($res) {
                while ($row = $res->fetch_assoc()) {
                    $results[] = $row;
                }
            }
            $stmt->close();
        }
        return $results;
    }
}

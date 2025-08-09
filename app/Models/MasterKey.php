<?php

class MasterKey {
    // Crear una nueva clave maestra
    public static function crearClaveMaestra($conn, $data) {
        $clave = mysqli_real_escape_string($conn, $data['clave']);
        $clave_hash = password_hash($clave, PASSWORD_DEFAULT);

        $sql = "INSERT INTO clavesmaestras (clave) VALUES ('$clave_hash')";
        if ($conn->query($sql)) {
            return [
                'success' => true,
                'message' => 'Clave maestra creada correctamente.'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'No se pudo crear la clave maestra.'
            ];
        }
    }

    // Obtener todas las claves maestras
    public static function obtenerClavesMaestras($conn) {
        $sql = "SELECT id_clave, clave FROM clavesmaestras";
        $result = $conn->query($sql);
        $claves = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $claves[] = $row;
            }
        }
        return $claves;
    }

    // Eliminar una clave maestra por ID
    public static function eliminarClaveMaestra($conn, $data) {
        $id_clave = mysqli_real_escape_string($conn, $data['id_clave']);
        $sql = "DELETE FROM clavesmaestras WHERE id_clave = '$id_clave'";
        if ($conn->query($sql)) {
            return [
                'success' => true,
                'message' => 'Clave maestra eliminada correctamente.'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'No se pudo eliminar la clave maestra.'
            ];
        }
    }

    // Verificar si una clave maestra es válida
    public static function verificarClaveMaestra($conn, $data) {
        $clave = mysqli_real_escape_string($conn, $data['clave']);
        $sql = "SELECT clave FROM clavesmaestras";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                if (password_verify($clave, $row['clave'])) {
                    return [
                        'success' => true,
                        'message' => 'Clave maestra válida.'
                    ];
                }
            }
        }
        return [
            'success' => false,
            'message' => 'Clave maestra incorrecta.'
        ];
    }
}
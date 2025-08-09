<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/SICAED/app/Models/Staff.php';

class StaffController {
    public static function handle($conn, $action, $data) {
        switch ($action) {
            case 'add':
                return Staff::crear($conn, $data);
            case 'get':
                return Staff::obtener($conn, $data);
            case 'delete':
                return Staff::eliminar($conn, $data);
            case 'deleteByID':
                return Staff::eliminarPorId($conn, $data);
            case 'deleteDoc':
                return Staff::eliminarDocumento($conn, $data);
            case 'modify':
                return Staff::actualizar($conn, $data);
            case 'getAll':
                return ['success' => true, 'data' => Staff::obtenerTodos($conn)];
            default:
                return ['success' => false, 'message' => 'Acción no válida para personal.'];
        }
    }

    public static function getAll($conn) {
        return Staff::obtenerTodos($conn);
    }
}

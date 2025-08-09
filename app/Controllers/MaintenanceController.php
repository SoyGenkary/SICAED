<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/SICAED/app/Models/Maintenance.php';

class MaintenanceController {
    public static function handle($conn, $action, $data) {
        switch ($action) {
            case 'add':
                return Maintenance::crear($conn, $data);
            case 'get':
                return Maintenance::obtener($conn, $data);
            case 'modify':
                return Maintenance::actualizar($conn, $data);
            case 'delete':
                return Maintenance::eliminar($conn, $data);
            case 'deleteDoc';
                return Maintenance::eliminarDocumento($conn, $data);
            default:
            return ['success' => false, 'message' => 'Acción no válida para mantenimiento.'];
        }
    }
}

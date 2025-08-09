<?php

class Router {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function handle($section, $action, $data) {
        switch ($section) {
            case 'user':
                require_once $_SERVER['DOCUMENT_ROOT'] . '/SICAED/app/Controllers/UserController.php';
                return UserController::handle($this->conn, $action, $data);
            case 'vehiculos':
                require_once $_SERVER['DOCUMENT_ROOT'] . '/SICAED/app/Controllers/VehiclesController.php';
                return VehiclesController::handle($this->conn, $action, $data);
            case 'personal':
            case 'conductores':
                require_once $_SERVER['DOCUMENT_ROOT'] . '/SICAED/app/Controllers/StaffController.php';
                return StaffController::handle($this->conn, $action, $data);
            case 'mantenimientos':
                require_once $_SERVER['DOCUMENT_ROOT'] . '/SICAED/app/Controllers/MaintenanceController.php';
                return MaintenanceController::handle($this->conn, $action, $data);
            case 'browser':
                require_once $_SERVER['DOCUMENT_ROOT'] . '/SICAED/app/Controllers/BrowserController.php';
                return SearchController::search($this->conn, $data);
            case 'auditlogger':
                require_once $_SERVER['DOCUMENT_ROOT'] . '/SICAED/app/Controllers/AuditLoggerContoller.php';
                return AuditLoggerContoller::handle($this->conn, $data, $action);
            case 'masterkey':
                require_once $_SERVER['DOCUMENT_ROOT'] . '/SICAED/app/Controllers/MasterKeyController.php';
                return MasterKeyController::handle($this->conn, $action, $data);
            case 'all':
                require_once $_SERVER['DOCUMENT_ROOT'] . '/SICAED/app/Controllers/VehiclesController.php';
                require_once $_SERVER['DOCUMENT_ROOT'] . '/SICAED/app/Controllers/StaffController.php';
                return [
                    'success' => true,
                    'vehiculos' => VehiclesController::getAll($this->conn),
                    'personal' => StaffController::getAll($this->conn)
                ];
            default:
                return ['success' => false, 'message' => 'Sección no válida', 'xd' => $section];
        }
    }
}

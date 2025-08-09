<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/SICAED/app/Models/Vehicles.php';

class VehiclesController {
    public static function handle($conn, $action, $data) {
        switch ($action) {
            case 'add':
                return Vehiculo::crear($conn, $data);
            case 'get':
                return Vehiculo::obtener($conn, $data);
            case 'getCount':
                return Vehiculo::obtenerCantidad($conn);
            case 'delete':
                return Vehiculo::eliminar($conn, $data);
            case 'deleteByID':
                return Vehiculo::eliminarPorId($conn, $data);
            case 'deleteDoc':
                return Vehiculo::eliminarDocumento($conn, $data);
            case 'deleteExtra':
                return Vehiculo::eliminarExtra($conn, $data);
            case 'modify':
                return Vehiculo::actualizar($conn, $data);
            case 'getAll':
                return ['success' => true, 'data' => Vehiculo::obtenerTodos($conn)];
            default:
                return ['success' => false, 'message' => 'Acción no válida.'];
        }
    }

    public static function getAll($conn) {
        return Vehiculo::obtenerTodos($conn);
    }
}

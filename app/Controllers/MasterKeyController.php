<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/SICAED/app/Models/MasterKey.php';

class MasterKeyController {
    public static function handle($conn, $action, $data) {
        switch ($action) {
            case 'add':
                return MasterKey::crearClaveMaestra($conn, $data);
            case 'delete':
                return MasterKey::eliminarClaveMaestra($conn, $data);
            case 'verify':
                return MasterKey::verificarClaveMaestra($conn, $data);
            default:
                return ['success' => false, 'message' => 'Acción no válida para claves maestras'];
        }
    }
}
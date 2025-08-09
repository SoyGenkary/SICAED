<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/SICAED/app/Models/AuditLogger.php';

class AuditLoggerContoller {
    public static function handle($conn, $data, $action) {
        switch($action) {
            case 'mostrarIDRegistro':
                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }
                return AuditLogger::mostrarRegistro($conn, $data, $_SESSION['id']);
            case 'mostrarTodosRegistro':
                return AuditLogger::mostrarRegistro($conn, $data);
        }
    }
}
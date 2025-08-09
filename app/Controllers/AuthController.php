<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/SICAED/app/Models/User.php';

class AuthController {
    public static function handle($conn, $action, $data) {
        switch ($action) {
            case 'login':
                return User::login($conn, $data);
            default:
                return ['success' => false, 'message' => 'Acción no válida para autenticación'];
        }
    }
}

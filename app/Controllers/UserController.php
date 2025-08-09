<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/SICAED/app/Models/User.php';

class UserController {
    public static function handle($conn, $action, $data) {
        switch ($action) {
            case 'add':
                return User::crear($conn, $data);
            case 'login':
                return User::login($conn, $data);
            case 'verify':
                return User::comprobarContrasenia($conn, $data);
            case 'modify':
                return User::modificar($conn, $data);
            case 'getByID':
                return User::obtenerPerfilPorId($conn, $data);
            case 'photo/add':
                return User::cambiarfoto($conn, $data);
            case 'photo/delete':
                return User::borrarfoto($conn, $data);
            case 'deleteAccount':
                return User::eliminarCuenta($conn, $data);
            default:
                return ['success' => false, 'message' => 'Acción no válida para usuarios'];
        }
    }
}

<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/SICAED/config/database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/SICAED/app/Models/AuditLogger.php';

// Componente de cerrar sesion
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$id_usuario = $_SESSION['id'];

$_SESSION = array();

// Destruimos la sesion
session_destroy();

// Registramos la accion
AuditLogger::registrarAccion($conn, $id_usuario, "USUARIO:LOGOUT", 'usuarios', '*', '', '', "Cerró sesión.");

//Redireccionamos
header('Location: ./index.php');
exit();

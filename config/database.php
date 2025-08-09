<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/SICAED/config/config.php';

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if (mysqli_connect_errno()) {
    echo "Error de conexión: " . mysqli_connect_error();
    exit();
}

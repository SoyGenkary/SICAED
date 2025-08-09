<?php
header('Content-Type: application/json; charset=UTF-8');
require_once $_SERVER['DOCUMENT_ROOT'] . '/SICAED/config/database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/SICAED/routes/router.php';

$section = $_POST['section'] ?? $_GET['section'] ?? '';
$action = $_POST['action'] ?? $_GET['action'] ?? '';
$data = $_POST;

$router = new Router($conn);
$response = $router->handle($section, $action, $data);

echo json_encode($response, JSON_UNESCAPED_UNICODE);

<?php
require_once __DIR__ . '/sesion.php';
if (empty($_SESSION['panel_usuario']) || time() - ($_SESSION['panel_actividad'] ?? 0) > 1800) {
    unset($_SESSION['panel_usuario']);
    $ruta = $_SERVER['SCRIPT_NAME'] ?? '';
    if (strpos($ruta, '/api/') !== false) {
        http_response_code(401);
        header('Content-Type: application/json; charset=utf-8');
        exit(json_encode(['error' => 'Inicie sesión nuevamente.']));
    }
    header('Location: ' . (strpos($ruta, '/models/') !== false ? '../' : '') . 'login.php');
    exit;
}
$_SESSION['panel_actividad'] = time();

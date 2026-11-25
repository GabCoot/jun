<?php
require_once __DIR__ . '/config/sesion.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !hash_equals($_SESSION['panel_csrf'], (string) ($_POST['token'] ?? ''))) {
    http_response_code(403);
    exit('Solicitud inválida.');
}
$_SESSION = [];
$p = session_get_cookie_params();
setcookie(session_name(), '', time() - 3600, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
session_destroy();
header('Location: login.php');
exit;

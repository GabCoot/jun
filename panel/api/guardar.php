<?php
require_once __DIR__ . '/../config/acceso.php';
require_once __DIR__ . '/../models/Expediente.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !hash_equals($_SESSION['panel_csrf'], (string) ($_SERVER['HTTP_X_CSRF_TOKEN'] ?? ''))) {
    jsonResponse(['error' => 'Solicitud inválida'], 403);
}

$input = json_decode(file_get_contents('php://input'), true);

if (!is_array($input) || empty($input['id_expediente'])) {
    jsonResponse(['error' => 'Datos inválidos'], 400);
}

try {
    Expediente::actualizar(
        (int)$input['id_expediente'],
        $input,
        $_SESSION['panel_usuario']
    );
    jsonResponse(['success' => true, 'mensaje' => 'Expediente actualizado']);
} catch (InvalidArgumentException $e) {
    jsonResponse(['error' => $e->getMessage()], 400);
} catch (PDOException $e) {
    jsonResponse(['error' => 'Error al guardar'], 500);
}
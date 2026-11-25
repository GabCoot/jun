<?php
require_once __DIR__ . '/../config/acceso.php';

require_once __DIR__ . '/../models/Expediente.php';

header('Content-Type: application/json; charset=utf-8');

function responderJSON(array $datos, int $codigo = 200): void {
    http_response_code($codigo);

    echo json_encode(
        $datos,
        JSON_UNESCAPED_UNICODE
    );

    exit;
}

try {

    $termino = $_GET['q'] ?? '';

    if (!is_string($termino) || trim($termino) === '') {

        responderJSON([
            'success' => false,
            'error' => 'Ingrese CURP o teléfono'
        ], 400);

    }

    $expediente = Expediente::buscar(trim($termino));

    if (!$expediente) {

        responderJSON([
            'success' => false,
            'error' => 'No se encontró ningún expediente'
        ], 404);

    }

    responderJSON([
        'success' => true,
        'data' => $expediente
    ]);

} catch (InvalidArgumentException $e) {
    responderJSON(['error'=>$e->getMessage()], 400);
} catch (Throwable $e) {

    error_log($e->getMessage());

    responderJSON([
        'success' => false,
        'error' => 'Error al consultar el expediente'
    ], 500);

}
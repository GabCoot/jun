
<?php
session_start();
require_once __DIR__ . "/../config/db.php";

header("Content-Type: application/json; charset=utf-8");

if (!isset($_SESSION["persona_id"])) {
    http_response_code(401);
    echo json_encode(["ok" => false, "mensaje" => "Sesión no válida"]);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode(["ok" => false, "mensaje" => "Método no permitido"]);
    exit;
}

$id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);
$detalle = trim($_POST["detalle"] ?? "");
$paso = $_POST["paso"] ?? "informacion";

$pasosPermitidos = ["informacion", "confirmacion"];

if (!$id || !in_array($paso, $pasosPermitidos, true)) {
    http_response_code(400);
    echo json_encode(["ok" => false, "mensaje" => "Datos no válidos"]);
    exit;
}

$datos = json_encode(
    ["detalle" => $detalle],
    JSON_UNESCAPED_UNICODE
);

$actualizar = $conexion->prepare(
    "UPDATE procesos_pendientes
     SET datos_guardados = ?, paso_actual = ?
     WHERE id = ?
     AND persona_id = ?
     AND estado = 'pendiente'"
);

$actualizar->execute([
    $datos,
    $paso,
    $id,
    $_SESSION["persona_id"]
]);

if ($actualizar->rowCount() === 0) {
    // Comprobar si el proceso existe y pertenece al usuario.
    $verificar = $conexion->prepare(
        "SELECT id FROM procesos_pendientes
         WHERE id = ? AND persona_id = ?
         AND estado = 'pendiente'"
    );

    $verificar->execute([$id, $_SESSION["persona_id"]]);

    if (!$verificar->fetch()) {
        http_response_code(404);
        echo json_encode(["ok" => false, "mensaje" => "Proceso no encontrado"]);
        exit;
    }
}

echo json_encode([
    "ok" => true,
    "mensaje" => "Avance guardado correctamente"
]);
?>

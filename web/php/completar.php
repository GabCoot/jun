
<?php
session_start();
require_once __DIR__ . "/../config/db.php";

if (!isset($_SESSION["persona_id"])) {
    header("Location: login.php");
    exit;
}

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (!$id) {
    die("Proceso no válido.");
}

$actualizar = $conexion->prepare(
    "UPDATE procesos_pendientes
     SET estado = 'completado'
     WHERE id = ?
     AND persona_id = ?
     AND estado = 'pendiente'"
);

$actualizar->execute([
    $id,
    $_SESSION["persona_id"]
]);

if ($actualizar->rowCount() === 0) {
    die("No se pudo completar el proceso. Verifica su estado.");
}

require __DIR__ . "/../vistas/completar.php";


<?php
session_start();
require_once __DIR__ . "/../config/db.php";

if (!isset($_SESSION["persona_id"])) {
    header("Location: login.php");
    exit;
}

// TOKEN DE SEGURIDAD
if (empty($_SESSION["csrf_token"])) {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
}

// ELIMINAR PROCESO PENDIENTE
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["eliminar_proceso"])) {

    if (
        !isset($_POST["csrf_token"]) ||
        !hash_equals($_SESSION["csrf_token"], $_POST["csrf_token"])
    ) {
        die("Solicitud no válida.");
    }

    $id = filter_input(INPUT_POST, "proceso_id", FILTER_VALIDATE_INT);

    if ($id) {
        $eliminar = $conexion->prepare(
            "DELETE FROM procesos_pendientes
             WHERE id = ?
             AND persona_id = ?
             AND estado = 'pendiente'"
        );

        $eliminar->execute([
            $id,
            $_SESSION["persona_id"]
        ]);
    }

    header("Location: pendientes.php");
    exit;
}

// CONSULTAR PROCESOS PENDIENTES
$consulta = $conexion->prepare(
    "SELECT pp.id, pp.paso_actual, pp.fecha_actualizacion,
            p.nombre AS producto
     FROM procesos_pendientes pp
     INNER JOIN productos p ON p.id = pp.producto_id
     WHERE pp.persona_id = ?
     AND pp.estado = 'pendiente'
     ORDER BY pp.fecha_actualizacion DESC"
);

$consulta->execute([$_SESSION["persona_id"]]);
$pendientes = $consulta->fetchAll();

function e($valor) {
    return htmlspecialchars((string)($valor ?? ""), ENT_QUOTES, "UTF-8");
}

require __DIR__ . "/../vistas/pendientes.php";

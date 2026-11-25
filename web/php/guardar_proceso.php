
<?php
session_start();
require_once __DIR__ . "/../config/db.php";

if (!isset($_SESSION["persona_id"])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: catalogo.php");
    exit;
}

$persona_id = (int) $_SESSION["persona_id"];
$producto_id = filter_input(INPUT_POST, "producto_id", FILTER_VALIDATE_INT);

if (!$producto_id || $producto_id <= 0) {
    die("Servicio no válido.");
}

try {
    $conexion->beginTransaction();

    // Comprobar que el servicio exista y esté activo.
    $consulta = $conexion->prepare(
        "SELECT id FROM productos
         WHERE id = ? AND activo = 1
         LIMIT 1"
    );
    $consulta->execute([$producto_id]);

    if (!$consulta->fetch(PDO::FETCH_ASSOC)) {
        throw new Exception("El servicio seleccionado no está disponible.");
    }

    // Comprobar si la persona ya tiene expediente.
    $consulta = $conexion->prepare(
        "SELECT id_expediente
         FROM expedientes
         WHERE persona_id = ?
         LIMIT 1"
    );
    $consulta->execute([$persona_id]);
    $expediente = $consulta->fetch(PDO::FETCH_ASSOC);

    // Si todavía no tiene expediente, crear uno vacío.
    if (!$expediente) {
        $crear = $conexion->prepare(
            "INSERT INTO expedientes
            (persona_id, nombres, apellidos, origen_registro,
             estatus_expediente)
            VALUES (?, '', '', 'web', 'incompleto')"
        );
        $crear->execute([$persona_id]);
    }

    // Crear el proceso pendiente.
    $insertar = $conexion->prepare(
        "INSERT INTO procesos_pendientes
        (persona_id, producto_id, estado, paso_actual, datos_guardados)
        VALUES (?, ?, 'pendiente', 'informacion', '{}')"
    );

    $insertar->execute([$persona_id, $producto_id]);

    $id = $conexion->lastInsertId();

    $conexion->commit();

    header("Location: continuar.php?id=" . $id);
    exit;

} catch (Exception $e) {
    if ($conexion->inTransaction()) {
        $conexion->rollBack();
    }

    die("No se pudo iniciar el proceso: " .
        htmlspecialchars($e->getMessage(), ENT_QUOTES, "UTF-8"));
}

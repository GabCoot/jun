
<?php
session_start();
require_once __DIR__ . "/../config/db.php";

if (!isset($_SESSION["persona_id"])) {
    header("Location: login.php");
    exit;
}

$persona_id = (int) $_SESSION["persona_id"];
$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (!$id || $id <= 0) {
    die("Proceso no válido.");
}

// Buscar el proceso y el servicio seleccionado.
$consulta = $conexion->prepare(
    "SELECT pp.*, p.nombre AS producto
     FROM procesos_pendientes pp
     INNER JOIN productos p ON p.id = pp.producto_id
     WHERE pp.id = ?
       AND pp.persona_id = ?
       AND pp.estado = 'pendiente'
     LIMIT 1"
);

$consulta->execute([$id, $persona_id]);
$proceso = $consulta->fetch(PDO::FETCH_ASSOC);

if (!$proceso) {
    die("No se encontró el proceso o ya fue completado.");
}

// Buscar el expediente de la persona.
$consulta = $conexion->prepare(
    "SELECT *
     FROM expedientes
     WHERE persona_id = ?
     LIMIT 1"
);
$consulta->execute([$persona_id]);
$expediente = $consulta->fetch(PDO::FETCH_ASSOC);

if (!$expediente) {
    die("No se encontró el expediente. Regresa al catálogo e intenta nuevamente.");
}

$nombre = $_SESSION["nombre"] ?? "Usuario";
$paso = $proceso["paso_actual"] ?? "informacion";

// Guardar automáticamente mediante AJAX.
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    header("Content-Type: application/json; charset=utf-8");

    $accion = $_POST["accion"] ?? "";

    $campos = [
        "curp" => strtoupper(trim($_POST["curp"] ?? "")),
        "nombres" => trim($_POST["nombres"] ?? ""),
        "apellidos" => trim($_POST["apellidos"] ?? ""),
        "fecha_nacimiento" => trim($_POST["fecha_nacimiento"] ?? ""),
        "sexo" => trim($_POST["sexo"] ?? ""),
        "estado_civil" => trim($_POST["estado_civil"] ?? ""),
        "telefono_celular" => trim($_POST["telefono_celular"] ?? ""),
        "correo_electronico" => trim($_POST["correo_electronico"] ?? ""),
        "codigo_postal" => trim($_POST["codigo_postal"] ?? ""),
        "estado" => trim($_POST["estado"] ?? ""),
        "municipio" => trim($_POST["municipio"] ?? ""),
        "colonia" => trim($_POST["colonia"] ?? ""),
        "calle" => trim($_POST["calle"] ?? "")
    ];

    $pasoNuevo = $_POST["paso"] ?? "informacion";

    if (!in_array($pasoNuevo, ["informacion", "confirmacion"], true)) {
        echo json_encode([
            "ok" => false,
            "mensaje" => "El paso seleccionado no es válido."
        ]);
        exit;
    }

    if ($campos["curp"] !== "" &&
        !preg_match('/^[A-Z0-9]{18}$/', $campos["curp"])) {
        echo json_encode([
            "ok" => false,
            "mensaje" => "La CURP debe contener 18 caracteres."
        ]);
        exit;
    }

    if (!in_array($campos["sexo"], ["", "H", "M", "X"], true)) {
        echo json_encode([
            "ok" => false,
            "mensaje" => "El sexo seleccionado no es válido."
        ]);
        exit;
    }

    if ($campos["correo_electronico"] !== "" &&
        !filter_var($campos["correo_electronico"], FILTER_VALIDATE_EMAIL)) {
        echo json_encode([
            "ok" => false,
            "mensaje" => "El correo electrónico no tiene un formato válido."
        ]);
        exit;
    }

    if ($campos["fecha_nacimiento"] !== "") {
        $fecha = DateTime::createFromFormat("Y-m-d", $campos["fecha_nacimiento"]);

        if (!$fecha || $fecha->format("Y-m-d") !== $campos["fecha_nacimiento"]) {
            echo json_encode([
                "ok" => false,
                "mensaje" => "La fecha de nacimiento no es válida."
            ]);
            exit;
        }
    }

    if ($accion === "completar") {
        if (
            $campos["nombres"] === "" ||
            $campos["apellidos"] === "" ||
            $campos["telefono_celular"] === "" ||
            $campos["correo_electronico"] === ""
        ) {
            echo json_encode([
                "ok" => false,
                "mensaje" => "Completa nombres, apellidos, teléfono y correo antes de finalizar."
            ]);
            exit;
        }
    } elseif ($accion !== "guardar") {
        echo json_encode([
            "ok" => false,
            "mensaje" => "Acción no válida."
        ]);
        exit;
    }

    try {
        $conexion->beginTransaction();

        // Actualizar el expediente del paciente.
        $actualizar = $conexion->prepare(
            "UPDATE expedientes SET
                curp = ?,
                nombres = ?,
                apellidos = ?,
                fecha_nacimiento = ?,
                sexo = ?,
                estado_civil = ?,
                telefono_celular = ?,
                correo_electronico = ?,
                codigo_postal = ?,
                estado = ?,
                municipio = ?,
                colonia = ?,
                calle = ?,
                estatus_expediente = ?
             WHERE id_expediente = ?
               AND persona_id = ?"
        );

        $estatus = $accion === "completar"
            ? "en_revision"
            : "incompleto";

        $actualizar->execute([
            $campos["curp"] !== "" ? $campos["curp"] : null,
            $campos["nombres"],
            $campos["apellidos"],
            $campos["fecha_nacimiento"] !== "" ? $campos["fecha_nacimiento"] : null,
            $campos["sexo"] !== "" ? $campos["sexo"] : null,
            $campos["estado_civil"] !== "" ? $campos["estado_civil"] : null,
            $campos["telefono_celular"] !== "" ? $campos["telefono_celular"] : null,
            $campos["correo_electronico"] !== "" ? $campos["correo_electronico"] : null,
            $campos["codigo_postal"] !== "" ? $campos["codigo_postal"] : null,
            $campos["estado"] !== "" ? $campos["estado"] : null,
            $campos["municipio"] !== "" ? $campos["municipio"] : null,
            $campos["colonia"] !== "" ? $campos["colonia"] : null,
            $campos["calle"] !== "" ? $campos["calle"] : null,
            $estatus,
            $expediente["id_expediente"],
            $persona_id
        ]);

        // Guardar el paso actual del proceso.
        $actualizarProceso = $conexion->prepare(
            "UPDATE procesos_pendientes
             SET paso_actual = ?
             WHERE id = ?
               AND persona_id = ?
               AND estado = 'pendiente'"
        );

        $actualizarProceso->execute([
            $pasoNuevo,
            $id,
            $persona_id
        ]);

        // Si se finaliza, marcar el proceso como completado.
        if ($accion === "completar") {
            $finalizar = $conexion->prepare(
                "UPDATE procesos_pendientes
                 SET estado = 'completado',
                     paso_actual = 'confirmacion'
                 WHERE id = ?
                   AND persona_id = ?
                   AND estado = 'pendiente'"
            );

            $finalizar->execute([$id, $persona_id]);

            if ($finalizar->rowCount() !== 1) {
                throw new Exception("El proceso ya no está pendiente.");
            }
        }

        $conexion->commit();

        echo json_encode([
            "ok" => true,
            "mensaje" => $accion === "completar"
                ? "Expediente enviado a revisión."
                : "Cambios guardados correctamente."
        ]);
        exit;

    } catch (PDOException $e) {
        if ($conexion->inTransaction()) {
            $conexion->rollBack();
        }

        $mensaje = "No se pudieron guardar los datos. Verifica que la CURP no esté registrada en otro expediente.";

        echo json_encode([
            "ok" => false,
            "mensaje" => $mensaje
        ]);
        exit;

    } catch (Exception $e) {
        if ($conexion->inTransaction()) {
            $conexion->rollBack();
        }

        echo json_encode([
            "ok" => false,
            "mensaje" => $e->getMessage()
        ]);
        exit;
    }
}

function e($valor) {
    return htmlspecialchars((string)($valor ?? ""), ENT_QUOTES, "UTF-8");
}

require __DIR__ . "/../vistas/continuar.php";

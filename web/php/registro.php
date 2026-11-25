
<?php
require_once __DIR__ . "/../config/db.php";

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nombre = trim($_POST["nombre"] ?? "");
    $usuario = trim($_POST["usuario"] ?? "");
    $telefono = trim($_POST["telefono"] ?? "");
    $ciudad = trim($_POST["ciudad"] ?? "");
    $password = $_POST["password"] ?? "";
    $confirmar = $_POST["confirmar"] ?? "";

    if (
        $nombre === "" || $usuario === "" ||
        $telefono === "" || $password === ""
    ) {
        $mensaje = "Completa todos los campos obligatorios.";

    } elseif (!preg_match('/^[0-9+\-\s]{10,20}$/', $telefono)) {
        $mensaje = "Ingresa un teléfono válido.";

    } elseif (strlen($password) < 8) {
        $mensaje = "La contraseña debe tener al menos 8 caracteres.";

    } elseif ($password !== $confirmar) {
        $mensaje = "Las contraseñas no coinciden.";

    } else {

        $consulta = $conexion->prepare(
            "SELECT id FROM personas
             WHERE usuario = ? OR telefono = ?"
        );

        $consulta->execute([$usuario, $telefono]);

        if ($consulta->fetch()) {
            $mensaje = "El usuario o teléfono ya está registrado.";

        } else {

            $hash = password_hash($password, PASSWORD_DEFAULT);

            $insertar = $conexion->prepare(
                "INSERT INTO personas
                (nombre, usuario, telefono, password_hash, ciudad)
                VALUES (?, ?, ?, ?, ?)"
            );

            $insertar->execute([
                $nombre,
                $usuario,
                $telefono,
                $hash,
                $ciudad
            ]);

            header("Location: login.php?registro=exitoso");
            exit;
        }
    }
}

require __DIR__ . "/../vistas/registro.php";


<?php
session_start();
require_once __DIR__ . "/../config/db.php";

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $identificador = trim($_POST["identificador"] ?? "");
    $password = $_POST["password"] ?? "";

    $consulta = $conexion->prepare(
        "SELECT * FROM personas
         WHERE usuario = ? OR telefono = ?
         LIMIT 1"
    );

    $consulta->execute([$identificador, $identificador]);

    $persona = $consulta->fetch();

    if ($persona && password_verify($password, $persona["password_hash"])) {

        session_regenerate_id(true);

        $_SESSION["persona_id"] = $persona["id"];
        $_SESSION["nombre"] = $persona["nombre"];

        header("Location: pendientes.php");
        exit;

    } else {
        $mensaje = "Usuario, teléfono o contraseña incorrectos.";
    }
}

require __DIR__ . "/../vistas/login.php";

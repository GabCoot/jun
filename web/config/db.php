<?php

$host = "sql212.infinityfree.com";
$dbname = "if0_43528599_jun";
$username = "if0_43528599";
$password = "YrQ64BpC2Kjatf0";

try {

    $conexion = new PDO(
        "mysql:host=$host;port=3306;dbname=$dbname;charset=utf8mb4",
        $username,
        $password
    );

    $conexion->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );

    $conexion->setAttribute(
        PDO::ATTR_DEFAULT_FETCH_MODE,
        PDO::FETCH_ASSOC
    );

    $conexion->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

} catch (PDOException $e) {

    error_log($e->getMessage());
    die("Error de conexión con la base de datos.");

}

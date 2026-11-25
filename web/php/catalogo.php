
<?php
session_start();
require_once __DIR__ . "/../config/db.php";

if (!isset($_SESSION["persona_id"])) {
    header("Location: login.php");
    exit;
}

$consulta = $conexion->query(
    "SELECT * FROM productos
     WHERE activo = 1
     ORDER BY categoria, nombre"
);

$productos = $consulta->fetchAll();

$categorias = array_unique(
    array_filter(array_column($productos, "categoria"))
);

function e($valor) {
    return htmlspecialchars((string)($valor ?? ""), ENT_QUOTES, "UTF-8");
}

// ICONOS MEDICOS EN SVG
function iconoMedico($tipo) {

    $iconos = [

        "sangre" => '
        <path d="M32 5 C32 5 15 26 15 36 A17 17 0 0 0 49 36 C49 26 32 5 32 5Z"/>
        <path d="M24 37 C24 43 28 46 33 46"/>
        <path d="M27 27 L37 27"/>
        ',

        "glucosa" => '
        <path d="M32 5 C32 5 14 27 14 38 A18 18 0 0 0 50 38 C50 27 32 5 32 5Z"/>
        <path d="M25 39 C25 44 29 47 34 47"/>
        <path d="M43 17 L48 12"/>
        ',

        "orina" => '
        <path d="M22 10 H42"/>
        <path d="M25 10 V17 L20 24 V49 Q20 54 25 54 H39 Q44 54 44 49 V24 L39 17 V10"/>
        <path d="M21 35 H43"/>
        <path d="M25 42 H39"/>
        ',

        "embarazo" => '
        <rect x="13" y="20" width="38" height="24" rx="6"/>
        <path d="M20 27 H30"/>
        <path d="M20 36 H30"/>
        <path d="M38 27 V36"/>
        <path d="M34 36 H42"/>
        <path d="M20 14 H44"/>
        ',

        "corazon" => '
        <path d="M32 51 C25 44 9 32 9 21 A12 12 0 0 1 32 16 A12 12 0 0 1 55 21 C55 32 39 44 32 51Z"/>
        <path d="M12 31 H23 L28 23 L35 39 L40 29 H52"/>
        ',

        "ultrasonido" => '
        <rect x="9" y="10" width="46" height="34" rx="5"/>
        <path d="M15 17 H49 V37 H15Z"/>
        <path d="M20 32 Q25 18 31 28 T44 23"/>
        <path d="M23 44 V51 H41 V44"/>
        <path d="M32 51 V55"/>
        ',

        "radiografia" => '
        <rect x="10" y="7" width="44" height="50" rx="5"/>
        <circle cx="25" cy="22" r="5"/>
        <circle cx="39" cy="22" r="5"/>
        <path d="M32 28 V48"/>
        <path d="M25 34 L32 39 L39 34"/>
        <path d="M25 48 L32 43 L39 48"/>
        ',

        "cerebro" => '
        <path d="M31 14 C24 5 12 12 16 22 C7 27 12 39 19 40 C17 50 29 54 32 46"/>
        <path d="M33 14 C40 5 52 12 48 22 C57 27 52 39 45 40 C47 50 35 54 32 46"/>
        <path d="M32 14 V48"/>
        <path d="M18 27 Q25 25 25 33"/>
        <path d="M46 27 Q39 25 39 33"/>
        ',

        "diente" => '
        <path d="M32 8 C24 3 12 10 15 24 C18 35 22 54 29 54 C33 54 31 39 36 39 C41 39 39 54 45 54 C51 54 54 35 57 24 C60 10 40 3 32 8Z"/>
        ',

        "ojo" => '
        <path d="M5 32 Q32 5 59 32 Q32 59 5 32Z"/>
        <circle cx="32" cy="32" r="10"/>
        <circle cx="32" cy="32" r="4"/>
        ',

        "tubo" => '
        <path d="M21 8 H43"/>
        <path d="M25 8 V42 Q25 53 32 53 Q39 53 39 42 V8"/>
        <path d="M25 33 H39"/>
        <path d="M28 42 H36"/>
        ',

        "microscopio" => '
        <path d="M24 8 L39 23"/>
        <path d="M20 12 L29 3 L44 18 L35 27Z"/>
        <path d="M39 23 Q50 33 41 43"/>
        <path d="M16 44 H48"/>
        <path d="M27 44 Q27 33 39 33"/>
        <path d="M20 52 H49"/>
        ',

        "medico" => '
        <path d="M22 9 V24 Q22 34 32 34 Q42 34 42 24 V9"/>
        <path d="M15 9 H22 M42 9 H49"/>
        <path d="M32 34 V42"/>
        <path d="M25 42 H39"/>
        <path d="M32 42 V54"/>
        <path d="M26 48 H38"/>
        ',

        "laboratorio" => '
        <path d="M24 7 H40"/>
        <path d="M27 7 V24 L15 46 Q12 54 21 54 H43 Q52 54 49 46 L37 24 V7"/>
        <path d="M20 42 H44"/>
        <circle cx="27" cy="47" r="2"/>
        <circle cx="37" cy="49" r="2"/>
        ',

        "default" => '
        <path d="M32 5 L32 59"/>
        <path d="M5 32 H59"/>
        <path d="M18 18 L46 46"/>
        <path d="M46 18 L18 46"/>
        '
    ];

    $contenido = $iconos[$tipo] ?? $iconos["default"];

    return '
    <svg viewBox="0 0 64 64"
         xmlns="http://www.w3.org/2000/svg"
         fill="none"
         stroke="currentColor"
         stroke-width="2.7"
         stroke-linecap="round"
         stroke-linejoin="round"
         aria-hidden="true">
         '.$contenido.'
    </svg>';
}

// IDENTIFICAR EL ICONO SEGUN EL NOMBRE DEL PRODUCTO
function iconoProducto($nombre, $categoria = "") {

    $texto = strtolower((string)$nombre);

    $texto = strtr($texto, [
        "á"=>"a", "é"=>"e", "í"=>"i",
        "ó"=>"o", "ú"=>"u", "ñ"=>"n"
    ]);

    if (
        strpos($texto, "glucosa") !== false ||
        strpos($texto, "diabetes") !== false
    ) return "glucosa";

    if (
        strpos($texto, "orina") !== false ||
        strpos($texto, "uroanalisis") !== false
    ) return "orina";

    if (
        strpos($texto, "embarazo") !== false ||
        strpos($texto, "hcg") !== false
    ) return "embarazo";

    if (
        strpos($texto, "electrocardiograma") !== false ||
        strpos($texto, "electrocardio") !== false ||
        strpos($texto, "ecg") !== false
    ) return "corazon";

    if (
        strpos($texto, "ultrasonido") !== false ||
        strpos($texto, "ultra") !== false
    ) return "ultrasonido";

    if (
        strpos($texto, "radiografia") !== false ||
        strpos($texto, "rayos x") !== false ||
        strpos($texto, "mastografia") !== false
    ) return "radiografia";

    if (
        strpos($texto, "tomografia") !== false ||
        strpos($texto, "resonancia") !== false ||
        strpos($texto, "cerebral") !== false
    ) return "cerebro";

    if (
        strpos($texto, "dental") !== false ||
        strpos($texto, "odontolog") !== false
    ) return "diente";

    if (
        strpos($texto, "vista") !== false ||
        strpos($texto, "oftalm") !== false
    ) return "ojo";

    if (
        strpos($texto, "sangre") !== false ||
        strpos($texto, "biometria") !== false ||
        strpos($texto, "hematic") !== false ||
        strpos($texto, "hemograma") !== false
    ) return "sangre";

    if (
        strpos($texto, "colesterol") !== false ||
        strpos($texto, "trigliceridos") !== false
    ) return "corazon";

    if (
        strpos($texto, "tiroides") !== false ||
        strpos($texto, "hormona") !== false
    ) return "tubo";

    if (
        strpos($texto, "consulta") !== false ||
        strpos($texto, "medica") !== false
    ) return "medico";

    if (
        strpos($texto, "laboratorio") !== false ||
        strpos($texto, "perfil") !== false
    ) return "laboratorio";

    return "microscopio";
}

// ICONOS PARA CATEGORIAS
function iconoCategoria($categoria) {

    $categoria = strtolower((string)$categoria);

    $categoria = strtr($categoria, [
        "á"=>"a", "é"=>"e", "í"=>"i",
        "ó"=>"o", "ú"=>"u", "ñ"=>"n"
    ]);

    if (strpos($categoria, "laboratorio") !== false) return "laboratorio";
    if (strpos($categoria, "imagen") !== false) return "radiografia";
    if (strpos($categoria, "cardio") !== false) return "corazon";
    if (strpos($categoria, "ultra") !== false) return "ultrasonido";
    if (strpos($categoria, "dental") !== false) return "diente";
    if (strpos($categoria, "vista") !== false) return "ojo";
    if (strpos($categoria, "consulta") !== false) return "medico";

    return "default";
}

require __DIR__ . "/../vistas/catalogo.php";

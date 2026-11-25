<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <meta name="panel-csrf" content="<?= htmlspecialchars($_SESSION['panel_csrf'], ENT_QUOTES, 'UTF-8') ?>">
    <title>JUN | Salud Digna</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
          rel="stylesheet">

    <!-- Estilos personalizados -->
    <link rel="stylesheet" href="assets/css/styles.css">

</head>

<body>

    <div class="layout">

        <?php
        require_once __DIR__ . '/sidebar.php';
        ?>
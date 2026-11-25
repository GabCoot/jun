<?php
require_once __DIR__ . '/config/acceso.php';

date_default_timezone_set('America/Merida');
$pagina = $_GET['pagina'] ?? 'dashboard';

$paginas = [
    'dashboard'   => __DIR__ . '/models/dashboard.php',
    'expedientes' => __DIR__ . '/models/expedientes.php',
    'pacientes'   => __DIR__ . '/models/pacientes.php',
    'citas' => __DIR__ . '/models/citas.php',
    'callcenter'  => __DIR__ . '/models/callcenter.php'
];

if ($pagina === 'citas') {
    $_SESSION['agenda_token'] ??= bin2hex(random_bytes(32));
    require_once __DIR__.'/models/Agenda.php';
    agendaPost();
}
require_once __DIR__ . '/includes/header.php';

?>

<main class="content" id="areaTrabajo">

    <?php

    if (
        is_string($pagina) &&
        isset($paginas[$pagina]) &&
        is_file($paginas[$pagina])
    ) {

        require $paginas[$pagina];

    } else {

        echo '<div class="alert alert-warning">
            Módulo no disponible.
        </div>';

    }

    ?>

</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script src="assets/js/app.js"></script>

<?php
require_once __DIR__ . '/includes/footer.php';
?>
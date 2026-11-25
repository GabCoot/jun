<?php

require_once __DIR__ . '/../config/acceso.php';
require_once __DIR__ . '/Expediente.php';

try {

    $pdo = getDB();

    // INDICADORES GENERALES
    $datos = $pdo->query("
        SELECT
            COUNT(*) AS total,
            COALESCE(SUM(estatus_expediente = 'incompleto'), 0) AS pendientes,
            COALESCE(SUM(estatus_expediente = 'completo'), 0) AS completos
        FROM expedientes
    ")->fetch(PDO::FETCH_ASSOC);

    $total = (int) $datos['total'];
    $pendientes = (int) $datos['pendientes'];
    $completos = (int) $datos['completos'];
    $otros = max(0, $total - $pendientes - $completos);

    $porcentaje = $total > 0
        ? round(($completos / $total) * 100, 1)
        : 0;

} catch (Throwable $e) {

    error_log($e->getMessage());

    echo '<div class="alert alert-danger">
        No se pudo consultar la base de datos.
    </div>';

    return;
}

?>

<!-- ESTILOS EXCLUSIVOS DEL DASHBOARD -->

<style>

.jun-dashboard {
    color: #183a50;
}

.jun-dashboard .dashboard-header {
    margin-bottom: 28px;
}

.jun-dashboard .dashboard-header h2 {
    font-weight: 750;
    letter-spacing: -0.8px;
}

.jun-dashboard .dashboard-subtitle {
    color: #81919b;
    font-size: 14px;
}

.jun-dashboard .kpi-card {
    background: #ffffff;
    border: 1px solid #e8eef1;
    border-radius: 18px;
    padding: 24px;
    height: 100%;
    box-shadow: 0 5px 20px rgba(0,142,135,.05);
    transition: transform .2s, box-shadow .2s;
}

.jun-dashboard .kpi-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 25px rgba(0,142,135,.10);
}

.jun-dashboard .kpi-icon {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 14px;
    font-size: 23px;
}

.jun-dashboard .icon-total {
    background: #e8eef1;
    color: #008e87;
}

.jun-dashboard .icon-pendientes {
    background: #fff3e7;
    color: #b88932;
}

.jun-dashboard .icon-completos {
    background: #e4f6f3;
    color: #006e6a;
}

.jun-dashboard .kpi-label {
    color: #81919b;
    font-size: 14px;
    margin-top: 18px;
    margin-bottom: 4px;
}

.jun-dashboard .kpi-value {
    font-size: 36px;
    font-weight: 750;
    margin: 0;
}

.jun-dashboard .chart-card {
    background: #ffffff;
    border: 1px solid #e8eef1;
    border-radius: 18px;
    padding: 24px;
    height: 100%;
    box-shadow: 0 5px 20px rgba(0,142,135,.05);
}

.jun-dashboard .chart-title {
    font-size: 17px;
    font-weight: 700;
    margin-bottom: 5px;
}

.jun-dashboard .chart-description {
    font-size: 13px;
    color: #81919b;
    margin-bottom: 20px;
}

.jun-dashboard .chart-container {
    position: relative;
    height: 280px;
}

.jun-dashboard .progress {
    height: 13px;
    background: #e8eef1;
    border-radius: 20px;
}

.jun-dashboard .progress-bar {
    background: linear-gradient(90deg, #25b7a5, #008e87);
    border-radius: 20px;
}

.jun-dashboard .system-status {
    background: #e8eef1;
    color: #008e87;
    padding: 9px 15px;
    border-radius: 30px;
    font-size: 12px;
    font-weight: 600;
}

.jun-dashboard .status-dot {
    display: inline-block;
    width: 8px;
    height: 8px;
    background: #006e6a;
    border-radius: 50%;
    margin-right: 5px;
}

</style>

<div class="jun-dashboard">

    <!-- ENCABEZADO -->

    <div class="dashboard-header d-flex flex-wrap
                justify-content-between align-items-center gap-3">

        <div>
            <h2 class="mb-1">Panel de control</h2>

            <p class="dashboard-subtitle mb-0">
                Monitoreo y gestión omnicanal de expedientes
            </p>
        </div>

        <div class="d-flex align-items-center gap-3">

            <span class="system-status">
                <span class="status-dot"></span>
                Base de datos conectada
            </span>

            <a href="index.php?pagina=dashboard"
               class="btn btn-success">

                <i class="bi bi-arrow-clockwise"></i>
                Actualizar

            </a>

        </div>

    </div>

    <!-- INDICADORES KPI -->

    <div class="row g-3 mb-4">

        <div class="col-md-4">

            <div class="kpi-card">

                <div class="kpi-icon icon-total">
                    <i class="bi bi-clipboard-data"></i>
                </div>

                <p class="kpi-label">Total de expedientes</p>

                <h2 class="kpi-value">
                    <?= number_format($total) ?>
                </h2>

                <small class="text-muted">
                    Registros en la plataforma
                </small>

            </div>

        </div>

        <div class="col-md-4">

            <div class="kpi-card">

                <div class="kpi-icon icon-pendientes">
                    <i class="bi bi-clock-history"></i>
                </div>

                <p class="kpi-label">Expedientes pendientes</p>

                <h2 class="kpi-value" style="color:#b88932">
                    <?= number_format($pendientes) ?>
                </h2>

                <small class="text-muted">
                    Requieren seguimiento
                </small>

            </div>

        </div>

        <div class="col-md-4">

            <div class="kpi-card">

                <div class="kpi-icon icon-completos">
                    <i class="bi bi-check-circle"></i>
                </div>

                <p class="kpi-label">Expedientes completos</p>

                <h2 class="kpi-value" style="color:#006e6a">
                    <?= number_format($completos) ?>
                </h2>

                <small class="text-muted">
                    Registrados como completos
                </small>

            </div>

        </div>

    </div>

<div class="card p-4 mb-4">

    <h4>Seguimiento omnicanal</h4>
    <p class="text-muted">
        Comparación de trámites por canal de atención
    </p>

    <?php
    // Datos de demostración
    $canalesDemo = ['Web', 'WhatsApp', 'Call Center'];
    $inicioDemo = [70, 70, 0];
    $finalDemo = [20, 100, 20];
    ?>

    <div class="row g-3 mb-4">

        <div class="col-md-6">
            <h6>Trámites iniciados</h6>

            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Canal</th>
                        <th>Cantidad</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($canalesDemo as $i => $canal): ?>
                    <tr>
                        <td><?= $canal ?></td>
                        <td><?= $inicioDemo[$i] ?></td>
                    </tr>
                    <?php endforeach; ?>

                    <tr>
                        <th>Total</th>
                        <th><?= array_sum($inicioDemo) ?></th>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="col-md-6">
            <h6>Trámites finalizados</h6>

            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Canal</th>
                        <th>Cantidad</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($canalesDemo as $i => $canal): ?>
                    <tr>
                        <td><?= $canal ?></td>
                        <td><?= $finalDemo[$i] ?></td>
                    </tr>
                    <?php endforeach; ?>

                    <tr>
                        <th>Total</th>
                        <th><?= array_sum($finalDemo) ?></th>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>

    <div style="height:280px;">
        <canvas id="graficaOmnicanal"></canvas>
    </div>

    <small class="text-muted mt-3">
        Datos simulados de demostración; independientes de los registros de MySQL. 70 de 140 trámites cambiaron de canal (50%).
    </small>

</div>


    <div id="errorGraficas"
         class="alert alert-warning d-none">
        No se pudieron cargar las gráficas.
    </div>

</div>

<!-- LIBRERÍA CHART.JS -->

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>

<script>

(function () {

    if (typeof Chart === 'undefined') {

        document.getElementById('errorGraficas')
            .classList.remove('d-none');

        return;
    }

    Chart.defaults.font.family =
        "'Segoe UI', Arial, sans-serif";

    Chart.defaults.color = '#81919b';

    const colores = [
        '#008e87',
        '#006e6a',
        '#25b7a5',
        '#a6d9d2',
        '#183a50',
        '#e4f6f3'
    ];

new Chart(document.getElementById('graficaOmnicanal'), {
    type: 'bar',
    data: {
        labels: <?= json_encode($canalesDemo) ?>,
        datasets: [
            {
                label: 'Iniciados',
                data: <?= json_encode($inicioDemo) ?>,
                backgroundColor: '#183a50',
                borderRadius: 5
            },
            {
                label: 'Finalizados',
                data: <?= json_encode($finalDemo) ?>,
                backgroundColor: '#008e87',
                borderRadius: 5
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

})();
</script>


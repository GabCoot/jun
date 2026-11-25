<?php
$actual = $_GET['pagina'] ?? 'dashboard';
?>

<nav class="sidebar d-flex flex-column p-3" id="sidebar">
    <h5 class="mb-4 text-center">
    <div class="d-flex align-items-center justify-content-center gap-2">
        <i class="bi bi-heart-pulse-fill">
    </i> JUN
</div>
<span class="d-block mt-1">SALUD DIGNA</span>
</h5>
<ul class="nav flex-column gap-1">
    <li>
        <a href="index.php?pagina=dashboard"
               class="nav-link <?= $actual === 'dashboard' ? 'active' : '' ?>">
        <i class="bi bi-speedometer2">
    </i>
                Dashboard

</a>
</li>
<li>
    <a href="index.php?pagina=expedientes"
               class="nav-link <?= $actual === 'expedientes' ? 'active' : '' ?>">
    <i class="bi bi-clipboard2-pulse">
</i>
                Expedientes

</a>
</li>
<li>
    <a href="index.php?pagina=pacientes"
               class="nav-link <?= $actual === 'pacientes' ? 'active' : '' ?>">
    <i class="bi bi-people">
</i>
                Pacientes

</a>
</li>
<li>
    <a href="index.php?pagina=callcenter"
               class="nav-link <?= $actual === 'callcenter' ? 'active' : '' ?>">
    <i class="bi bi-headset">
</i>
                Call Center

</a>
</li>
<li>
    <a href="index.php?pagina=citas" class="nav-link <?= $actual === 'citas' ? 'active' : '' ?>">
    <i class="bi bi-calendar-event">
</i> Agenda de citas</a>
</li>
</ul>
<form method="post" action="logout.php" class="mt-auto pt-4">
    <input type="hidden" name="token" value="<?= htmlspecialchars($_SESSION['panel_csrf'], ENT_QUOTES, 'UTF-8') ?>">
    <button class="btn btn-outline-light w-100" type="submit">Cerrar sesión</button>
</form>
</nav>
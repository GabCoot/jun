
<!DOCTYPE html>
<html lang="es">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Inicio | JUN - Salud Digna</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Estilos originales -->
    <link rel="stylesheet" href="../css/pendientes.css">

    <style>

        /* LOGO INSTITUCIONAL JUN */

        .logo-jun {
            text-align: center;
            color: #008D68;
            padding: 20px 0 25px;
            margin-bottom: 15px;
        }

        .logo-jun .logo-principal {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            font-size: 38px;
            font-weight: 800;
            letter-spacing: 2px;
            line-height: 1.2;
        }

        .logo-jun .logo-principal i {
            font-size: 36px;
        }

        .logo-jun span {
            display: block;
            margin-top: 6px;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 4px;
        }

    </style>

</head>

<body>

<div class="layout">

    <!-- MENU LATERAL -->

    <aside class="sidebar">

        <!-- LOGO ORIGINAL JUN -->

        <div class="logo-jun">

            <div class="logo-principal">
                <i class="bi bi-heart-pulse-fill"></i>
                JUN
            </div>

            <span>SALUD DIGNA</span>

        </div>

        <div class="menu-label">MENÚ PRINCIPAL</div>

        <nav class="menu">

            <a href="pendientes.php" class="active">
                <span class="menu-icon">⌂</span>
                Inicio
            </a>

            <a href="catalogo.php">
                <span class="menu-icon">▦</span>
                Catálogo de servicios
            </a>

        </nav>

    </aside>

    <!-- CONTENIDO PRINCIPAL -->

    <main class="main">

        <!-- ENCABEZADO -->

        <header class="topbar">

            <div class="breadcrumb">
                Portal del paciente / <strong>Inicio</strong>
            </div>

            <div class="user-info">

                <div class="avatar">
                    <?= strtoupper(substr(e($_SESSION["nombre"]), 0, 1)) ?>
                </div>

                <div>

                    <div class="user-name">
                        <?= e($_SESSION["nombre"]) ?>
                    </div>

                    <div class="user-role">
                        Paciente
                    </div>

                </div>

            </div>

        </header>

        <!-- BANNER -->

        <section class="hero">

            <div class="hero-content">

                <div class="hero-label">
                    ✦ SALUD DIGNA CONNECT
                </div>

                <h1>
                    ¡Hola de nuevo,

                    <span class="jun-acento">
                        <?= e($_SESSION["nombre"]) ?>!
                    </span>

                </h1>

                <p>
                    Nos alegra tenerte de vuelta. Aquí puedes consultar
                    tus procesos pendientes y continuar tu atención
                    justo donde la dejaste.
                </p>

            </div>

            <div class="hero-art">⚕</div>

        </section>

        <!-- INDICADORES -->

        <section class="stats">

            <div class="stat-card">

                <div class="stat-icon green">📋</div>

                <div>

                    <div class="stat-number">
                        <?= count($pendientes) ?>
                    </div>

                    <div class="stat-text">
                        Procesos pendientes
                    </div>

                </div>

            </div>

            <div class="stat-card">

                <div class="stat-icon blue">🕒</div>

                <div>

                    <div class="stat-number">24/7</div>

                    <div class="stat-text">
                        Acceso a tu portal
                    </div>

                </div>

            </div>

            <div class="stat-card">

                <div class="stat-icon orange">✚</div>

                <div>

                    <div class="stat-number">100%</div>

                    <div class="stat-text">
                        Atención conectada
                    </div>

                </div>

            </div>

        </section>

        <!-- PROCESOS PENDIENTES -->

        <section>

            <div class="section-heading">

                <div>

                    <h2>Mis procesos pendientes</h2>

                    <p>
                        Continúa tus servicios sin perder tu avance.
                    </p>

                </div>

            </div>

            <div class="process-grid">

                <?php if (count($pendientes) > 0): ?>

                    <?php foreach ($pendientes as $proceso): ?>

                        <article class="process-card">

                            <div class="process-top">

                                <div class="process-icon">
                                    🧪
                                </div>

                                <div class="process-tag">
                                    ● Pendiente
                                </div>

                            </div>

                            <h3>
                                <?= e($proceso["producto"]) ?>
                            </h3>

                            <div class="process-info">

                                <p>Último avance</p>

                                <strong>
                                    <?= e($proceso["paso_actual"]) ?>
                                </strong>

                            </div>

                            <div class="process-date">

                                🕒 Última actualización:

                                <?= e($proceso["fecha_actualizacion"]) ?>

                            </div>

                            <!-- BOTONES -->

                            <div class="process-actions">

                                <a class="process-button"
                                   href="continuar.php?id=<?= (int)$proceso["id"] ?>">

                                    Continuar →

                                </a>

                                <form method="POST"
                                      class="delete-form"
                                      onsubmit="return confirm('¿Estás seguro de que deseas eliminar este proceso pendiente? Esta acción no se puede deshacer.');">

                                    <input type="hidden"
                                           name="csrf_token"
                                           value="<?= e($_SESSION["csrf_token"]) ?>">

                                    <input type="hidden"
                                           name="proceso_id"
                                           value="<?= (int)$proceso["id"] ?>">

                                    <button type="submit"
                                            name="eliminar_proceso"
                                            value="1"
                                            class="delete-button">

                                        🗑 Eliminar

                                    </button>

                                </form>

                            </div>

                        </article>

                    <?php endforeach; ?>

                <?php else: ?>

                    <div class="empty">

                        <div class="empty-icon">
                            📋
                        </div>

                        <h3>
                            ¡Todo está al día!
                        </h3>

                        <p>
                            Actualmente no tienes procesos pendientes.
                            Explora nuestro catálogo y encuentra el servicio
                            que necesitas.
                        </p>

                        <a href="catalogo.php" class="catalog-button">
                            Explorar servicios →
                        </a>

                    </div>

                <?php endif; ?>

            </div>

        </section>

        <!-- PIE DE PÁGINA -->

        <footer class="footer">

            <span>
                Salud Digna Connect · Prototipo HackaTec ERROR404
            </span>

            <span>
                Tu atención, siempre conectada.
            </span>

        </footer>

    </main>

</div>

</body>
</html>

</html>

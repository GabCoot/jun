<!DOCTYPE html>
<html lang="es">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Catálogo de servicios | Salud Digna Connect</title>

<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<link rel="stylesheet" href="../css/catalogo.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
.jun-brand{text-align:center;color:#008D68;padding:20px 0 25px;margin-bottom:15px;font-family:Manrope,sans-serif}
.jun-brand div{display:flex;align-items:center;justify-content:center;gap:10px;font-size:38px;font-weight:800;line-height:1.2;letter-spacing:2px}
.jun-brand small{display:block;margin-top:6px;font-size:11px;letter-spacing:4px;font-weight:700}
#jun-chat,#jun-launch{font-family:'DM Sans',sans-serif;box-sizing:border-box;z-index:9999}
#jun-chat *{box-sizing:border-box}
#jun-chat[hidden]{display:none!important}
#jun-launch{position:fixed;bottom:24px;right:24px;display:flex;align-items:center;gap:10px;padding:15px 21px;border:0;border-radius:40px;background:#006b50;color:white;font-size:14px;font-weight:700;cursor:pointer;box-shadow:0 8px 30px #006b5040;width:auto;line-height:1.4}
#jun-launch i{font-size:23px}#jun-launch:hover{background:#00543f}
#jun-chat{position:fixed;right:24px;bottom:96px;width:min(390px,calc(100vw - 48px));height:min(620px,calc(100vh - 120px));height:min(620px,calc(100dvh - 120px));background:white;border:1px solid #dceae4;border-radius:24px;box-shadow:0 20px 70px #173c3338;display:flex;flex-direction:column;overflow:hidden;color:#183c33;animation:jc-in .22s ease-out;text-align:left}
@keyframes jc-in{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:translateY(0)}}
#jun-chat .jc-header{background:linear-gradient(125deg,#005b45,#008d68);color:white;padding:19px;flex-shrink:0}
#jun-chat .jc-headrow{display:flex;align-items:center;gap:12px}
#jun-chat .jc-logo{background:white;color:#008D68;border-radius:14px;width:48px;height:48px;display:grid;place-items:center;font-size:26px;flex-shrink:0}
#jun-chat .jc-header strong{display:block;font-size:19px;line-height:1.3;color:white}#jun-chat .jc-header small{font-size:12px;color:#d8f4e8}#jun-chat .jc-header p{margin:13px 0 0;color:#e3f5ed;font-size:13px}
#jun-chat button{cursor:pointer;font-family:inherit;line-height:1.4;margin:0;text-transform:none}#jun-chat .jc-close{margin-left:auto;border:1px solid #ffffff45;background:#ffffff14;color:white;font-size:17px;padding:9px;border-radius:10px;width:auto;flex-shrink:0}
#jun-chat .jc-messages{flex:1;min-height:0;overflow:auto;overscroll-behavior:contain;padding:16px;background:#f6f9f7;display:flex;flex-direction:column;gap:12px;scrollbar-width:thin}
#jun-chat .jc-msg{align-self:flex-start;max-width:91%;padding:13px 15px;border-radius:4px 16px 16px 16px;background:white;border:1px solid #e1ebe6;font-size:14px;line-height:1.55;overflow-wrap:anywhere;white-space:pre-wrap;flex-shrink:0;color:#183c33}
#jun-chat .jc-user{align-self:flex-end;background:#00795b;color:white;border:0;border-radius:16px 4px 16px 16px}
#jun-chat .jc-caption{font-size:11px;color:#647871;text-align:center;margin:0}#jun-chat .jc-quick{display:grid;gap:7px;flex-shrink:0}#jun-chat .jc-quick button{display:flex;align-items:center;gap:10px;text-align:left;background:white;color:#183c33;border:1px solid #dceae4;border-radius:11px;padding:11px;font-size:13px;font-weight:600;width:100%}#jun-chat .jc-quick button:hover{background:#e6f4ed;border-color:#008d68}#jun-chat .jc-quick i{color:#008d68;font-size:17px}#jun-chat .jc-quick i:last-child{margin-left:auto;font-size:12px}
#jun-chat .jc-form{display:flex;align-items:center;gap:8px;padding:13px 13px 8px;margin:0;background:white;border-top:1px solid #e1ebe6;flex-shrink:0}
#jun-chat input{flex:1;min-width:0;width:100%;margin:0;padding:12px;border:1px solid #cddfd6;border-radius:13px;font:inherit;font-size:16px;color:#183c33;background:white;height:46px}
#jun-chat .jc-send{width:46px;height:46px;flex-shrink:0;border:0;border-radius:13px;background:#008D68;color:white;font-size:18px;display:grid;place-items:center;padding:0}#jun-chat .jc-send:hover{background:#006b50}
#jun-chat .jc-note{padding:0 12px 12px;text-align:center;font-size:11px;color:#526e62;background:white;flex-shrink:0}
#jun-chat button:focus-visible,#jun-launch:focus-visible,#jun-chat input:focus-visible{outline:3px solid #45bfa0;outline-offset:2px}
@media(max-width:480px){#jun-chat{right:12px;bottom:82px;width:calc(100vw - 24px);height:min(620px,calc(100vh - 94px));height:min(620px,calc(100dvh - 94px))}#jun-launch{right:12px;bottom:12px;padding:13px 17px}}
@media(max-height:420px){#jun-chat{bottom:8px;height:calc(100dvh - 16px);z-index:10000}#jun-chat .jc-header{padding:10px}#jun-chat .jc-header p{display:none}}
@media(prefers-reduced-motion:reduce){#jun-chat{animation:none}}
</style>
</head>

<body>

<div class="layout">

    <!-- MENU LATERAL -->

    <aside class="sidebar">

        <div class="jun-brand">
            <div><i class="bi bi-heart-pulse-fill" aria-hidden="true"></i> JUN</div>
            <small>SALUD DIGNA</small>
        </div>

        <div class="menu-label">MENÚ PRINCIPAL</div>

        <nav class="menu">

            <a href="pendientes.php">
                <span class="menu-icon">⌂</span>
                Inicio
            </a>

            <a href="catalogo.php" class="active">
                <span class="menu-icon">▦</span>
                Catálogo de servicios
            </a>

        </nav>

        <div class="sidebar-bottom">

            <div class="help-card">
                <div class="help-icon">💬</div>
                <strong>¿Necesitas ayuda?</strong>
                <p>Estamos trabajando para ofrecerte una atención continua y sencilla.</p>
            </div>

            <nav class="menu">
                <a href="logout.php" class="logout">
                    <span class="menu-icon">↪</span>
                    Cerrar sesión
                </a>
            </nav>

        </div>

    </aside>

    <!-- CONTENIDO PRINCIPAL -->

    <main class="main">

        <header class="topbar">

            <div class="breadcrumb">
                Portal del paciente / <strong>Catálogo</strong>
            </div>

            <div class="user-info">

                <div class="avatar">
                    <?= strtoupper(substr(e($_SESSION["nombre"] ?? "P"), 0, 1)) ?>
                </div>

                <div>
                    <div class="user-name">
                        <?= e($_SESSION["nombre"] ?? "Paciente") ?>
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
                    Tu salud merece una atención
                    <span class="jun-acento">sin interrupciones.</span>
                </h1>

                <p>
                    Explora nuestro catálogo de estudios y servicios.
                    Comienza tu proceso y continúa cuando quieras,
                    sin perder tu avance.
                </p>

            </div>

            <div class="hero-art">⚕</div>

        </section>

        <!-- ESTADISTICAS -->

        <section class="stats">

            <div class="stat-card">
                <div class="stat-icon green">🧪</div>

                <div>
                    <div class="stat-number">
                        <?= count($productos) ?>
                    </div>

                    <div class="stat-text">
                        Servicios disponibles
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon blue">🏥</div>

                <div>
                    <div class="stat-number">
                        <?= count($categorias) ?>
                    </div>

                    <div class="stat-text">
                        Categorías disponibles
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon orange">🕒</div>

                <div>
                    <div class="stat-number">24/7</div>

                    <div class="stat-text">
                        Acceso a tu portal
                    </div>
                </div>
            </div>

        </section>

        <!-- CATALOGO -->

        <section>

            <div class="section-heading">

                <div>
                    <h2>Explora nuestros servicios</h2>

                    <p>
                        Encuentra el estudio que estás buscando.
                    </p>
                </div>

            </div>

            <!-- BUSCADOR -->

            <div class="search-box">
                <span>⌕</span>

                <input
                    type="text"
                    id="buscador"
                    placeholder="Buscar por nombre o categoría..."
                >
            </div>

            <!-- CATEGORIAS -->

            <div class="category-heading">

                <div class="category-heading-icon">▦</div>

                <h3>Explorar por categoría</h3>

            </div>

            <div class="filters">

                <button class="filter active" data-categoria="todos">

                    <span class="filter-icon">
                        <?= iconoMedico("default") ?>
                    </span>

                    Todos los servicios

                </button>

                <?php foreach ($categorias as $categoria): ?>

                    <button
                        class="filter"
                        data-categoria="<?= e($categoria) ?>"
                    >

                        <span class="filter-icon">
                            <?= iconoMedico(iconoCategoria($categoria)) ?>
                        </span>

                        <?= e($categoria) ?>

                    </button>

                <?php endforeach; ?>

            </div>

            <!-- PRODUCTOS -->

            <div class="services" id="servicios">

                <?php foreach ($productos as $producto): ?>

                    <article
                        class="service-card producto"
                        data-nombre="<?= e($producto["nombre"]) ?>"
                        data-categoria="<?= e($producto["categoria"] ?? "General") ?>"
                    >

                        <div class="service-top">

                            <div class="service-icon">

                                <?= iconoMedico(
                                    iconoProducto(
                                        $producto["nombre"],
                                        $producto["categoria"] ?? ""
                                    )
                                ) ?>

                            </div>

                            <div class="service-tag">

                                <span class="service-tag-icon">
                                    <?= iconoMedico(
                                        iconoCategoria($producto["categoria"] ?? "")
                                    ) ?>
                                </span>

                                <?= e($producto["categoria"] ?? "General") ?>

                            </div>

                        </div>

                        <h3>
                            <?= e($producto["nombre"]) ?>
                        </h3>

                        <p class="service-description">
                            <?= e($producto["descripcion"] ?? "Consulta la información de este servicio.") ?>
                        </p>

                        <div class="service-price">

                            <span class="price-icon">💰</span>

                            <?php if ($producto["precio"] !== null): ?>

                                $<?= number_format((float)$producto["precio"], 2) ?>

                            <?php else: ?>

                                Consulta el precio

                            <?php endif; ?>

                        </div>

                        <form action="guardar_proceso.php" method="POST">

                            <input
                                type="hidden"
                                name="producto_id"
                                value="<?= (int)$producto["id"] ?>"
                            >

                            <button type="submit" class="service-button">
                                Seleccionar servicio <span>→</span>
                            </button>

                        </form>

                    </article>

                <?php endforeach; ?>

                <div class="empty" id="sinResultados">

                    <div class="empty-icon">
                        <?= iconoMedico("laboratorio") ?>
                    </div>

                    <h3>No encontramos resultados</h3>

                    <p>
                        Intenta con otro nombre o selecciona otra categoría.
                    </p>

                </div>

                <?php if (count($productos) === 0): ?>

                    <div class="empty jun-visible">

                        <div class="empty-icon">
                            <?= iconoMedico("laboratorio") ?>
                        </div>

                        <h3>Estamos preparando el catálogo</h3>

                        <p>
                            Todavía no hay servicios registrados.
                        </p>

                    </div>

                <?php endif; ?>

            </div>

        </section>

        <!-- PIE -->

        <footer class="footer">

            <span>Salud Digna Connect · Prototipo HackaTec ERROR404</span>

            <span>Tu atención, siempre conectada.</span>

        </footer>

    </main>

</div>

<!-- BUSCADOR Y FILTROS -->

<script src="../js/catalogo.js"></script>

<!-- ASISTENTE FLOTANTE JUN -->
<button id="jun-launch" type="button" aria-label="Abrir asistente JUN" aria-controls="jun-chat" aria-expanded="false"><i class="bi bi-chat-dots-fill" aria-hidden="true"></i><span>¿Necesitas ayuda?</span></button>
<section id="jun-chat" aria-label="Asistente virtual JUN" hidden>
    <div class="jc-header">
        <div class="jc-headrow">
            <div class="jc-logo"><i class="bi bi-heart-pulse-fill" aria-hidden="true"></i></div>
            <div><strong>JUN Asistente</strong><small>Asistente virtual automático</small></div>
            <button class="jc-close" type="button" aria-label="Cerrar chat"><i class="bi bi-x-lg" aria-hidden="true"></i></button>
        </div>
        <p>Estamos para orientarte.</p>
    </div>
    <div class="jc-messages" role="log" aria-live="polite">
        <p class="jc-caption">Inicio de la conversación</p>
        <div class="jc-msg">¡Hola! Bienvenido a JUN. Puedo ayudarte a resolver tus dudas sobre el catálogo y tus procesos. ¿Qué necesitas consultar?</div>
        <p class="jc-caption">CONSULTAS FRECUENTES</p>
        <div class="jc-quick">
            <button type="button"><i class="bi bi-wallet2" aria-hidden="true"></i>Consultar precios<i class="bi bi-chevron-right" aria-hidden="true"></i></button>
            <button type="button"><i class="bi bi-clipboard-check" aria-hidden="true"></i>Seleccionar un servicio<i class="bi bi-chevron-right" aria-hidden="true"></i></button>
            <button type="button"><i class="bi bi-clock-history" aria-hidden="true"></i>Continuar un proceso<i class="bi bi-chevron-right" aria-hidden="true"></i></button>
            <button type="button"><i class="bi bi-file-earmark-text" aria-hidden="true"></i>Consultar resultados<i class="bi bi-chevron-right" aria-hidden="true"></i></button>
        </div>
    </div>
    <form class="jc-form" autocomplete="off">
        <input type="text" maxlength="300" aria-label="Escribe tu pregunta" placeholder="Escribe tu consulta..." required>
        <button type="submit" class="jc-send" aria-label="Enviar pregunta"><i class="bi bi-send-fill" aria-hidden="true"></i></button>
    </form>
    <div class="jc-note">No compartas datos personales ni médicos. Chat informativo.</div>
</section>
<script>
(() => {
    const chat = document.getElementById('jun-chat');
    const launch = document.getElementById('jun-launch');
    const log = chat.querySelector('.jc-messages');
    const form = chat.querySelector('.jc-form');
    const input = form.querySelector('input');
    function toggle(open) {
        chat.hidden = !open;
        launch.setAttribute('aria-expanded', String(open));
        launch.setAttribute('aria-label', open ? 'Cerrar asistente JUN' : 'Abrir asistente JUN');
        launch.querySelector('i').className = open ? 'bi bi-x-lg' : 'bi bi-chat-dots-fill';
        launch.querySelector('span').textContent = open ? 'Cerrar chat' : '¿Necesitas ayuda?';
        if(open) chat.querySelector('.jc-close').focus(); else launch.focus();
    }
    launch.addEventListener('click', () => toggle(chat.hidden));
    chat.querySelector('.jc-close').addEventListener('click', () => toggle(false));
    chat.addEventListener('keydown', e => { if(e.key === 'Escape') toggle(false); });
    function normalize(t) { return t.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g,''); }
    function reply(question) {
        const q = normalize(question);
        if (/urgencia|emergencia|no puedo respirar/.test(q)) return 'Si se trata de una emergencia médica, llama al 911 en México. Este chat no ofrece atención médica.';
        if (/precio|costo|cuanto cuesta/.test(q)) {
            const matches = [...document.querySelectorAll('.service-card.producto')].filter(card => { const name = normalize(card.dataset.nombre || ''); return name && q.includes(name); });
            if(matches.length) return matches.slice(0,5).map(card => card.dataset.nombre + ': ' + (card.querySelector('.service-price')?.textContent.replace(/💰/g,'').trim() || 'Consulta el precio')).join('\n');
            return 'Cada servicio muestra su precio en el catálogo cuando está disponible. Puedes buscar el estudio por nombre y consultar su tarjeta.';
        }
        if (/continu|pendiente|avance|retomar/.test(q)) return 'Entra a Inicio, busca Mis procesos pendientes y presiona Continuar en el servicio correspondiente.';
        if (/resultado/.test(q)) return 'Este catálogo no muestra resultados médicos. Consulta los canales oficiales de Salud Digna para conocer cómo obtenerlos.';
        if (/seleccion|servicio|catalogo|estudio|buscar/.test(q)) return 'Utiliza el buscador o los filtros de categoría. Cuando encuentres el servicio que necesitas, presiona Seleccionar servicio.';
        if (/preparacion|ayuno|sintoma|diagnostico|medicamento/.test(q)) return 'Para indicaciones médicas o preparación de estudios, confirma la información con el personal de salud. Este chat solo orienta sobre el portal.';
        if (/hola|buenas|buenos/.test(q)) return '¡Hola! Puedes preguntarme por precios, servicios o procesos pendientes.';
        return 'No tengo una respuesta para esa consulta. Puedo orientarte sobre precios, selección de servicios y procesos pendientes. Para otras dudas, consulta los canales oficiales de atención.';
    }
    function add(text, user) {
        const el = document.createElement('div');
        el.className = 'jc-msg' + (user ? ' jc-user' : '');
        el.textContent = text;
        log.appendChild(el);
        log.scrollTop = log.scrollHeight;
    }
    function ask(text) { if(!text.trim()) return; add(text.trim(), true); add(reply(text), false); }
    form.addEventListener('submit', e => { e.preventDefault(); ask(input.value); input.value = ''; input.focus(); });
    chat.querySelectorAll('.jc-quick button').forEach(btn => btn.addEventListener('click', () => ask(btn.textContent)));
})();
</script>

</body>
</html>

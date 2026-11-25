
<!DOCTYPE html>
<html lang="es">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Crear cuenta | JUN - Salud Digna</title>

    <!-- Estilos originales -->
    <link rel="stylesheet" href="../css/estilos.css">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>

        :root {
            --verde: #008D68;
            --verde-oscuro: #006B50;
            --texto: #183C33;
            --gris: #647871;
            --borde: #D6E3DE;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #DDF2E9, #F7FBF9);
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 30px 20px;
        }

        .registro-container {
            width: 100%;
            max-width: 560px;
        }

        .registro-card {
            background: #ffffff;
            padding: 35px 40px;
            border-radius: 25px;
            border: 1px solid #E0EDE6;
            box-shadow: 0 15px 50px rgba(0, 107, 80, 0.10);
            animation: entrada 0.5s ease;
        }

        @keyframes entrada {
            from {
                opacity: 0;
                transform: translateY(15px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* LOGO JUN */

        .logo-jun {
            text-align: center;
            color: var(--verde);
            margin-bottom: 25px;
        }

        .logo-principal {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            font-size: 43px;
            font-weight: 800;
            letter-spacing: 2px;
        }

        .logo-principal i {
            font-size: 40px;
        }

        .logo-jun span {
            display: block;
            margin-top: 3px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 5px;
        }

        /* ENCABEZADO */

        .titulo {
            text-align: center;
            color: var(--texto);
            font-size: 27px;
            font-weight: 700;
            margin: 0 0 8px;
        }

        .subtitulo {
            text-align: center;
            color: var(--gris);
            font-size: 14px;
            margin-bottom: 28px;
        }

        /* SECCIONES */

        .seccion {
            display: flex;
            align-items: center;
            gap: 9px;
            color: var(--verde);
            font-size: 14px;
            font-weight: 600;
            padding-bottom: 12px;
            margin: 22px 0 18px;
            border-bottom: 1px solid #E0EDE6;
        }

        .seccion i {
            font-size: 18px;
        }

        /* FORMULARIO */

        .registro-card form {
            display: block;
            margin: 0;
        }

        .grupo {
            margin-bottom: 17px;
            min-width: 0;
        }

        .fila {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .registro-card label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: var(--texto);
            margin-bottom: 7px;
        }

        .opcional {
            color: var(--gris);
            font-size: 11px;
            font-weight: 400;
        }

        .campo {
            position: relative;
        }

        .campo-icono {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 17px;
            color: var(--verde);
            pointer-events: none;
        }

        .registro-card input {
            display: block;
            width: 100%;
            height: 49px;
            margin: 0;
            padding: 0 15px 0 43px;
            background: #FAFCFB;
            border: 1px solid var(--borde);
            border-radius: 11px;
            font-size: 14px;
            color: var(--texto);
            outline: none;
            transition: 0.2s ease;
        }

        .registro-card input:focus {
            background: white;
            border-color: var(--verde);
            box-shadow: 0 0 0 4px rgba(0, 141, 104, 0.09);
        }

        .registro-card input::placeholder {
            color: #899B94;
            font-size: 13px;
        }

        .registro-card input[type="password"],
        .registro-card .input-password {
            padding-right: 45px;
        }

        .registro-card .mostrar-password {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            width: auto;
            height: auto;
            margin: 0;
            padding: 5px;
            background: transparent;
            border: none;
            color: var(--gris);
            font-size: 17px;
            cursor: pointer;
        }

        .ayuda {
            display: block;
            margin-top: 6px;
            color: var(--gris);
            font-size: 11px;
        }

        /* BOTÓN REGISTRO */

        .registro-card .btn-registro {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            width: 100%;
            height: 52px;
            background: var(--verde);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 600;
            margin-top: 23px;
            cursor: pointer;
            transition: 0.2s ease;
        }

        .registro-card .btn-registro:hover {
            background: var(--verde-oscuro);
            box-shadow: 0 6px 18px rgba(0, 141, 104, 0.20);
            transform: translateY(-1px);
        }

        /* INICIAR SESIÓN */

        .login-link {
            text-align: center;
            font-size: 14px;
            color: var(--gris);
            margin: 24px 0 0;
            padding-top: 20px;
            border-top: 1px solid #E0EDE6;
        }

        .login-link a {
            color: var(--verde);
            text-decoration: none;
            font-weight: 700;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        /* MENSAJES DE ERROR */

        .alert-error {
            display: flex;
            align-items: center;
            gap: 10px;
            background: #FFF1F1;
            border: 1px solid #F5CCCC;
            color: #B42318;
            padding: 13px;
            border-radius: 11px;
            font-size: 13px;
            margin-bottom: 20px;
        }

        /* PIE DE PÁGINA */

        .footer {
            text-align: center;
            color: var(--gris);
            font-size: 12px;
            margin-top: 22px;
        }

        /* RESPONSIVO */

        @media (max-width: 480px) {

            .registro-card {
                padding: 28px 22px;
            }

            .fila {
                grid-template-columns: 1fr;
                gap: 0;
            }

            .titulo {
                font-size: 24px;
            }

            .logo-principal {
                font-size: 37px;
            }

        }

        @media (prefers-reduced-motion: reduce) {

            .registro-card {
                animation: none;
            }

            * {
                transition: none !important;
            }

        }

    </style>

</head>

<body>

    <main class="registro-container">

        <div class="registro-card">

            <!-- LOGO ORIGINAL JUN -->

            <div class="logo-jun">

                <div class="logo-principal">
                    <i class="bi bi-heart-pulse-fill"></i>
                    JUN
                </div>

                <span>SALUD DIGNA</span>

            </div>

            <h1 class="titulo">Crea tu cuenta</h1>

            <p class="subtitulo">
                Estás a un paso de conectar con tu salud.
            </p>

            <!-- MENSAJE ORIGINAL -->

            <?php if ($mensaje): ?>

                <div class="alert-error" role="alert">

                    <i class="bi bi-exclamation-circle-fill"></i>

                    <?= htmlspecialchars($mensaje) ?>

                </div>

            <?php endif; ?>

            <form method="POST">

                <!-- INFORMACIÓN PERSONAL -->

                <div class="seccion">

                    <i class="bi bi-person-circle"></i>
                    Información personal

                </div>

                <div class="grupo">

                    <label for="nombre">Nombre completo</label>

                    <div class="campo">

                        <i class="bi bi-person campo-icono"></i>

                        <input
                            type="text"
                            id="nombre"
                            name="nombre"
                            placeholder="Ingresa tu nombre completo"
                            autocomplete="name"
                            required
                        >

                    </div>

                </div>

                <div class="fila">

                    <div class="grupo">

                        <label for="usuario">Nombre de usuario</label>

                        <div class="campo">

                            <i class="bi bi-at campo-icono"></i>

                            <input
                                type="text"
                                id="usuario"
                                name="usuario"
                                placeholder="Tu usuario"
                                autocomplete="username"
                                required
                            >

                        </div>

                    </div>

                    <div class="grupo">

                        <label for="telefono">Teléfono</label>

                        <div class="campo">

                            <i class="bi bi-telephone campo-icono"></i>

                            <input
                                type="tel"
                                id="telefono"
                                name="telefono"
                                placeholder="Tu teléfono"
                                autocomplete="tel"
                                required
                            >

                        </div>

                    </div>

                </div>

                <div class="grupo">

                    <label for="ciudad">
                        Ciudad
                        <span class="opcional">(Opcional)</span>
                    </label>

                    <div class="campo">

                        <i class="bi bi-geo-alt campo-icono"></i>

                        <input
                            type="text"
                            id="ciudad"
                            name="ciudad"
                            placeholder="Ingresa tu ciudad"
                            autocomplete="address-level2"
                        >

                    </div>

                </div>

                <!-- SEGURIDAD -->

                <div class="seccion">

                    <i class="bi bi-shield-check"></i>
                    Seguridad de tu cuenta

                </div>

                <div class="grupo">

                    <label for="password">Contraseña</label>

                    <div class="campo">

                        <i class="bi bi-lock campo-icono"></i>

                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="input-password"
                            placeholder="Crea una contraseña"
                            autocomplete="new-password"
                            minlength="8"
                            required
                        >

                        <button
                            type="button"
                            class="mostrar-password"
                            onclick="mostrarPassword('password', this)"
                            aria-label="Mostrar contraseña"
                        >

                            <i class="bi bi-eye"></i>

                        </button>

                    </div>

                    <span class="ayuda">
                        Utiliza al menos 8 caracteres.
                    </span>

                </div>

                <div class="grupo">

                    <label for="confirmar">
                        Confirmar contraseña
                    </label>

                    <div class="campo">

                        <i class="bi bi-lock campo-icono"></i>

                        <input
                            type="password"
                            id="confirmar"
                            name="confirmar"
                            class="input-password"
                            placeholder="Repite tu contraseña"
                            autocomplete="new-password"
                            required
                        >

                        <button
                            type="button"
                            class="mostrar-password"
                            onclick="mostrarPassword('confirmar', this)"
                            aria-label="Mostrar contraseña"
                        >

                            <i class="bi bi-eye"></i>

                        </button>

                    </div>

                </div>

                <!-- BOTÓN REGISTRAR -->

                <button type="submit" class="btn-registro">

                    <i class="bi bi-person-plus"></i>
                    Crear mi cuenta

                </button>

            </form>

            <!-- REGRESAR AL LOGIN -->

            <p class="login-link">

                ¿Ya tienes cuenta?

                <a href="login.php">Iniciar sesión</a>

            </p>

        </div>

        <footer class="footer">

            © <?= date("Y") ?> JUN · Plataforma omnicanal de salud

        </footer>

    </main>

    <script>

        function mostrarPassword(id, boton) {

            const campo = document.getElementById(id);
            const icono = boton.querySelector("i");

            if (campo.type === "password") {

                campo.type = "text";
                icono.className = "bi bi-eye-slash";
                boton.setAttribute("aria-label", "Ocultar contraseña");

            } else {

                campo.type = "password";
                icono.className = "bi bi-eye";
                boton.setAttribute("aria-label", "Mostrar contraseña");

            }

        }

    </script>

</body>
</html>

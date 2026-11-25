
<!DOCTYPE html>
<html lang="es">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>JUN | Salud Digna</title>

    <!-- Icono de la pestaña -->
    <link rel="icon" type="image/svg+xml"
        href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3E%3Cpath fill='%23008D68' d='M1.5 1.5C4-1 7 1 8 3c1-2 4-4 6.5-1.5C19 6 11 13 8 15 5 13-3 6 1.5 1.5Z'/%3E%3Cpath fill='none' stroke='white' stroke-width='1.2' d='M1 7h4l1.5-3 2 6 2-3H15'/%3E%3C/svg%3E">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>

        :root {
            --verde: #008D68;
            --verde-oscuro: #006B50;
            --verde-claro: #E8F5EF;
            --texto: #183C33;
            --gris: #647871;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(
                135deg,
                #DDF2E9,
                #F7FBF9
            );
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 25px;
        }

        /* ANIMACIÓN INICIAL DEL LOGO */

        .pantalla-carga {
            position: fixed;
            inset: 0;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            animation: desaparecer 0.4s ease 1.2s forwards;
            pointer-events: none;
        }

        .logo-carga {
            text-align: center;
            color: var(--verde);
            animation: aparecer 0.7s ease;
        }

        .logo-carga i {
            font-size: 65px;
            animation: latido 0.8s ease infinite;
        }

        .logo-carga strong {
            font-size: 65px;
            letter-spacing: 3px;
        }

        .logo-carga span {
            display: block;
            font-size: 15px;
            letter-spacing: 7px;
            font-weight: 600;
        }

        @keyframes latido {
            0%, 100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.12);
            }
        }

        @keyframes aparecer {
            from {
                opacity: 0;
                transform: scale(0.85);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        @keyframes desaparecer {
            to {
                opacity: 0;
                visibility: hidden;
            }
        }

        /* CONTENEDOR PRINCIPAL */

        .login-container {
            width: 100%;
            max-width: 440px;
        }

        .login-card {
            background: white;
            padding: 38px;
            border-radius: 25px;
            border: 1px solid #E0EDE6;
            box-shadow: 0 15px 50px rgba(0, 107, 80, 0.12);
        }

        /* LOGO JUN */

        .logo {
            color: var(--verde);
            margin-bottom: 30px;
        }

        .logo-principal {
            font-size: 43px;
            font-weight: 800;
            letter-spacing: 2px;
        }

        .logo-principal i {
            font-size: 40px;
        }

        .logo span {
            font-size: 12px;
            letter-spacing: 5px;
            font-weight: 600;
        }

        /* BIENVENIDA */

        .bienvenida {
            text-align: center;
            font-size: 27px;
            font-weight: 700;
            color: var(--texto);
        }

        .subtitulo {
            text-align: center;
            color: var(--gris);
            font-size: 14px;
            margin-bottom: 30px;
        }

        /* FORMULARIO */

        .form-label {
            color: var(--texto);
            font-size: 14px;
            font-weight: 600;
        }

        .campo {
            position: relative;
        }

        .campo-icono {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--verde);
            font-size: 18px;
        }

        .form-control {
            height: 54px;
            padding-left: 45px;
            padding-right: 45px;
            border-radius: 12px;
            border: 1px solid #D6E3DE;
            background: #FAFCFB;
            font-size: 14px;
        }

        .form-control:focus {
            border-color: var(--verde);
            background: white;
            box-shadow: 0 0 0 4px rgba(0, 141, 104, 0.10);
        }

        .mostrar-password {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: transparent;
            border: none;
            color: var(--gris);
            font-size: 18px;
            cursor: pointer;
        }

        /* BOTÓN INICIAR SESIÓN */

        .btn-login {
            width: 100%;
            height: 54px;
            background: var(--verde);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 600;
            transition: 0.2s;
        }

        .btn-login:hover {
            background: var(--verde-oscuro);
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(0, 141, 104, 0.20);
        }

        /* REGISTRARSE */

        .registro {
            text-align: center;
            margin-top: 25px;
            padding-top: 23px;
            border-top: 1px solid #E0EDE6;
        }

        .registro p {
            color: var(--gris);
            font-size: 14px;
            margin-bottom: 12px;
        }

        .btn-registro {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            width: 100%;
            min-height: 49px;
            border: 1.5px solid var(--verde);
            border-radius: 12px;
            text-decoration: none;
            color: var(--verde);
            font-size: 14px;
            font-weight: 600;
            transition: 0.2s;
        }

        .btn-registro:hover {
            background: var(--verde-claro);
            color: var(--verde-oscuro);
        }

        /* MENSAJE DE ERROR */

        .alert-error {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 13px;
            background: #FFF1F1;
            color: #B42318;
            border: 1px solid #F5CCCC;
            border-radius: 12px;
            font-size: 13px;
            margin-bottom: 22px;
        }

        /* PIE DE PÁGINA */

        .seguridad {
            text-align: center;
            color: var(--gris);
            font-size: 12px;
            margin-top: 23px;
        }

        .seguridad i {
            color: var(--verde);
        }

        .footer {
            text-align: center;
            color: var(--gris);
            font-size: 12px;
            margin-top: 24px;
        }

        /* DISEÑO RESPONSIVO */

        @media (max-width: 480px) {

            .login-card {
                padding: 30px 23px;
            }

            .logo-principal {
                font-size: 37px;
            }

            .bienvenida {
                font-size: 24px;
            }

        }

        /* ACCESIBILIDAD */

        @media (prefers-reduced-motion: reduce) {

            .pantalla-carga {
                display: none;
            }

            * {
                animation: none !important;
                transition: none !important;
            }

        }

    </style>

</head>

<body>

    <!-- PANTALLA DE INICIO CON LOGO -->

    <div class="pantalla-carga" aria-hidden="true">

        <div class="logo-carga">

            <div class="d-flex align-items-center justify-content-center gap-3">

                <i class="bi bi-heart-pulse-fill"></i>

                <strong>JUN</strong>

            </div>

            <span>SALUD DIGNA</span>

        </div>

    </div>

    <!-- LOGIN -->

    <main class="login-container">

        <div class="login-card">

            <!-- LOGO ORIGINAL -->

            <div class="logo text-center">

                <div class="logo-principal d-flex align-items-center justify-content-center gap-2">

                    <i class="bi bi-heart-pulse-fill"></i>

                    JUN

                </div>

                <span class="d-block mt-1">
                    SALUD DIGNA
                </span>

            </div>

            <h1 class="bienvenida">
                ¡Bienvenido!
            </h1>

            <p class="subtitulo">
                Tu salud, conectada en un solo lugar.
            </p>

            <!-- MENSAJE DE ERROR -->

            <?php if (!empty($mensaje)): ?>

                <div class="alert-error" role="alert">

                    <i class="bi bi-exclamation-circle-fill"></i>

                    <span>
                        <?= htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8') ?>
                    </span>

                </div>

            <?php endif; ?>

            <!-- FORMULARIO -->

            <form method="POST">

                <div class="mb-4">

                    <label for="identificador" class="form-label">
                        Usuario o teléfono
                    </label>

                    <div class="campo">

                        <i class="bi bi-person campo-icono"></i>

                        <input
                            type="text"
                            name="identificador"
                            id="identificador"
                            class="form-control"
                            placeholder="Ingresa tu usuario o teléfono"
                            autocomplete="username"
                            required
                        >

                    </div>

                </div>

                <div class="mb-4">

                    <label for="password" class="form-label">
                        Contraseña
                    </label>

                    <div class="campo">

                        <i class="bi bi-lock campo-icono"></i>

                        <input
                            type="password"
                            name="password"
                            id="password"
                            class="form-control"
                            placeholder="Ingresa tu contraseña"
                            autocomplete="current-password"
                            required
                        >

                        <button
                            type="button"
                            class="mostrar-password"
                            id="botonPassword"
                            onclick="mostrarPassword()"
                            aria-label="Mostrar contraseña"
                        >

                            <i class="bi bi-eye" id="iconoPassword"></i>

                        </button>

                    </div>

                </div>

                <button type="submit" class="btn-login">

                    Iniciar sesión

                    <i class="bi bi-arrow-right ms-2"></i>

                </button>

            </form>

            <!-- REGISTRO DE USUARIOS -->

            <div class="registro">

                <p>¿Todavía no tienes una cuenta?</p>

                <a href="registro.php" class="btn-registro">

                    <i class="bi bi-person-plus"></i>

                    Registrarse

                </a>

            </div>

            <div class="seguridad">

                <i class="bi bi-shield-check me-1"></i>

                Acceso a tu cuenta personal

            </div>

        </div>

        <footer class="footer">

            © <?= date("Y") ?> JUN · Plataforma omnicanal de salud

        </footer>

    </main>

    <script>

        function mostrarPassword() {

            const password = document.getElementById("password");
            const icono = document.getElementById("iconoPassword");
            const boton = document.getElementById("botonPassword");

            if (password.type === "password") {

                password.type = "text";
                icono.className = "bi bi-eye-slash";
                boton.setAttribute("aria-label", "Ocultar contraseña");

            } else {

                password.type = "password";
                icono.className = "bi bi-eye";
                boton.setAttribute("aria-label", "Mostrar contraseña");

            }

        }

    </script>

</body>
</html>

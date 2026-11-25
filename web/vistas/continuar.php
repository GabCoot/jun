<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Continuar proceso | Salud Digna Connect</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<link rel="stylesheet" href="../css/continuar.css">
</head>

<body>

<header class="header">
    <div class="brand">
        <strong>Salud Digna</strong>
        <span>CONNECT</span>
    </div>

    <div class="header-actions">
        <a href="pendientes.php" class="btn-back">← Volver a pendientes</a>
        <a href="logout.php" class="btn-exit">↪ Salir</a>
    </div>
</header>

<main class="container">

    <section class="welcome">
        <small>Seguimiento de servicios</small>
        <h1>Continúa donde te quedaste</h1>
        <p>
            Hola, <?= e($nombre) ?>. Puedes revisar y actualizar
            tus datos personales. Tus cambios se guardarán automáticamente.
        </p>
    </section>

    <section class="process-card">

        <div class="process-header">
            <div class="product-info">
                <div class="product-icon">▤</div>
                <div>
                    <small>SERVICIO SELECCIONADO</small>
                    <h2><?= e($proceso["producto"]) ?></h2>
                </div>
            </div>
            <span class="status">● Proceso pendiente</span>
        </div>

        <div class="steps">
            <div class="step <?= $paso === "informacion" ? "active" : "" ?>">
                <div class="step-number">1</div>
                <div>
                    <strong>Información</strong>
                    <span>Revisar los datos del paciente</span>
                </div>
            </div>

            <div class="step <?= $paso === "confirmacion" ? "active" : "" ?>">
                <div class="step-number">2</div>
                <div>
                    <strong>Confirmación</strong>
                    <span>Última etapa del proceso</span>
                </div>
            </div>
        </div>

        <form id="formulario" autocomplete="on">

            <input type="hidden" id="proceso_id" value="<?= (int)$proceso["id"] ?>">

            <div class="form-title">
                <h3>Información del paciente</h3>
                <p>Ingresa los datos solicitados. Si necesitas salir, podrás regresar después y continuar desde donde te quedaste.</p>
            </div>

            <h4 class="section-title">01. Datos personales</h4>

            <div class="fields">
                <div class="field full">
                    <label for="curp">CURP</label>
                    <input type="text" id="curp" maxlength="18"
                        placeholder="Ingresa tu CURP"
                        value="<?= e($expediente["curp"]) ?>">
                </div>

                <div class="field">
                    <label for="nombres">Nombre(s) *</label>
                    <input type="text" id="nombres" required
                        placeholder="Nombre(s)"
                        value="<?= e($expediente["nombres"]) ?>">
                </div>

                <div class="field">
                    <label for="apellidos">Apellidos *</label>
                    <input type="text" id="apellidos" required
                        placeholder="Apellidos"
                        value="<?= e($expediente["apellidos"]) ?>">
                </div>

                <div class="field">
                    <label for="fecha_nacimiento">Fecha de nacimiento</label>
                    <input type="date" id="fecha_nacimiento"
                        value="<?= e($expediente["fecha_nacimiento"]) ?>">
                </div>

                <div class="field">
                    <label for="sexo">Sexo</label>
                    <select id="sexo">
                        <option value="">Seleccionar</option>
                        <option value="H" <?= ($expediente["sexo"] ?? "") === "H" ? "selected" : "" ?>>Hombre</option>
                        <option value="M" <?= ($expediente["sexo"] ?? "") === "M" ? "selected" : "" ?>>Mujer</option>
                        <option value="X" <?= ($expediente["sexo"] ?? "") === "X" ? "selected" : "" ?>>No especificado</option>
                    </select>
                </div>

                <div class="field">
                    <label for="estado_civil">Estado civil</label>
                    <select id="estado_civil">
                        <option value="">Seleccionar</option>
                        <?php
                        $estadosCiviles = [
                            "Soltero/a", "Casado/a", "Unión libre",
                            "Divorciado/a", "Viudo/a"
                        ];
                        foreach ($estadosCiviles as $estadoCivil):
                        ?>
                            <option value="<?= e($estadoCivil) ?>"
                                <?= ($expediente["estado_civil"] ?? "") === $estadoCivil ? "selected" : "" ?>>
                                <?= e($estadoCivil) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <h4 class="section-title">02. Datos de contacto</h4>

            <div class="fields">
                <div class="field">
                    <label for="telefono_celular">Teléfono celular</label>
                    <input type="tel" id="telefono_celular" maxlength="15"
                        placeholder="Número de teléfono"
                        value="<?= e($expediente["telefono_celular"]) ?>">
                </div>

                <div class="field">
                    <label for="correo_electronico">Correo electrónico *</label>
                    <input type="email" id="correo_electronico"
                        placeholder="correo@ejemplo.com"
                        value="<?= e($expediente["correo_electronico"]) ?>">
                </div>
            </div>

            <h4 class="section-title">03. Domicilio</h4>

            <div class="fields">
                <div class="field">
                    <label for="codigo_postal">Código postal</label>
                    <input type="text" id="codigo_postal" maxlength="5"
                        placeholder="Código postal"
                        value="<?= e($expediente["codigo_postal"]) ?>">
                </div>

                <div class="field">
                    <label for="estado">Estado</label>
                    <input type="text" id="estado"
                        placeholder="Estado"
                        value="<?= e($expediente["estado"]) ?>">
                </div>

                <div class="field">
                    <label for="municipio">Municipio</label>
                    <input type="text" id="municipio"
                        placeholder="Municipio"
                        value="<?= e($expediente["municipio"]) ?>">
                </div>

                <div class="field">
                    <label for="colonia">Colonia</label>
                    <input type="text" id="colonia"
                        placeholder="Colonia"
                        value="<?= e($expediente["colonia"]) ?>">
                </div>

                <div class="field full">
                    <label for="calle">Calle y número</label>
                    <input type="text" id="calle"
                        placeholder="Calle, número exterior e interior"
                        value="<?= e($expediente["calle"]) ?>">
                </div>
            </div>

            <div class="field jun-espacio">
                <label for="paso">Selecciona el paso actual</label>
                <select id="paso">
                    <option value="informacion" <?= $paso === "informacion" ? "selected" : "" ?>>
                        1. Información
                    </option>
                    <option value="confirmacion" <?= $paso === "confirmacion" ? "selected" : "" ?>>
                        2. Confirmación
                    </option>
                </select>
            </div>

            <div class="save-status">
                <span class="save-dot" id="save_dot"></span>
                <span id="estado_guardado">Cambios guardados anteriormente.</span>
            </div>

            <div class="actions">
                <a href="pendientes.php" class="btn-cancel">← Regresar sin completar</a>
                <button type="submit" class="btn-finish" id="btn_finish">
                    ✓ Enviar expediente a revisión
                </button>
            </div>

        </form>
    </section>

    <footer class="footer">
        © <?= date("Y") ?> Salud Digna Connect. Todos los derechos reservados.
    </footer>

</main>

<script src="../js/continuar.js"></script>

</body>
</html>

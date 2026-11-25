<?php
require_once __DIR__ . '/../config/acceso.php';

require_once __DIR__ . '/Expediente.php';

$pendientes = [];
$errorCarga = false;

try {
    $pendientes = Expediente::incompletos();
} catch (Throwable $e) {
    error_log($e->getMessage());
    $errorCarga = true;
}

?>

<div class="row justify-content-center">
    <div class="col-lg-12">

        <!-- ENCABEZADO -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">Módulo Call Center</h4>
            <span class="text-muted small">
                Unificación Web / WhatsApp
            </span>
        </div>

        <!-- BUSCADOR -->
        <div class="card p-3 mb-4 shadow-sm border-0">

            <div class="row g-2">

                <div class="col-md-9">
                    <input type="text"
                           id="inputBuscar"
                           class="form-control form-control-lg"
                           placeholder="Ingrese CURP o teléfono celular"
                           maxlength="18">
                </div>

                <div class="col-md-3">
                    <button class="btn btn-success btn-lg w-100"
                            onclick="buscar()">
                        Buscar
                    </button>
                </div>

            </div>

            <div id="msgBuscar" class="mt-2 small"></div>

        </div>

        <!-- EDITOR DE EXPEDIENTE -->
        <div class="card p-3 mb-4 shadow-sm border-0"
             id="panelExpediente"
             style="display:none;">

            <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">

                <div>
                    <h5 class="mb-0" id="tituloExpediente"></h5>
                    <small class="text-muted" id="metaExpediente"></small>
                </div>

                <span id="badgeEstatus" class="badge"></span>

            </div>

            <form id="formExpediente">

                <input type="hidden" id="id_expediente">

                <div class="row g-3">

                    <!-- IDENTIDAD -->

                    <div class="col-12 section-title text-success">
                        Identidad
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-bold">CURP</label>
                        <input type="text"
                               class="form-control"
                               id="curp"
                               maxlength="18"
                               style="text-transform:uppercase">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Nombre(s)</label>
                        <input type="text" class="form-control" id="nombres" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Apellidos</label>
                        <input type="text" class="form-control" id="apellidos" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-bold">
                            Fecha de nacimiento
                        </label>
                        <input type="date" class="form-control" id="fecha_nacimiento">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Sexo</label>

                        <select class="form-select" id="sexo">
                            <option value="">Seleccionar</option>
                            <option value="H">Hombre</option>
                            <option value="M">Mujer</option>
                            <option value="X">No binario</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Estado civil</label>

                        <select class="form-select" id="estado_civil">
                            <option value="">Seleccionar</option>
                            <option value="soltero">Soltero/a</option>
                            <option value="casado">Casado/a</option>
                            <option value="divorciado">Divorciado/a</option>
                            <option value="viudo">Viudo/a</option>
                            <option value="union_libre">Unión libre</option>
                        </select>
                    </div>

                    <!-- CONTACTO -->

                    <div class="col-12 section-title text-success mt-4">
                        Contacto
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-bold">
                            Teléfono celular
                        </label>
                        <input type="tel" class="form-control" id="telefono_celular">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-bold">
                            Correo electrónico
                        </label>
                        <input type="email" class="form-control" id="correo_electronico">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-bold">
                            Código postal
                        </label>
                        <input type="text" class="form-control" id="codigo_postal" maxlength="5">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Estado</label>
                        <input type="text" class="form-control" id="estado">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Municipio</label>
                        <input type="text" class="form-control" id="municipio">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Colonia</label>
                        <input type="text" class="form-control" id="colonia">
                    </div>

                    <div class="col-12">
                        <label class="form-label small fw-bold">
                            Calle y número
                        </label>
                        <input type="text" class="form-control" id="calle">
                    </div>

                    <!-- WHATSAPP -->

                    <div class="col-12 section-title text-success mt-4">
                        Vinculación WhatsApp
                    </div>

                    <div class="col-md-6">
                        <div class="form-check">

                            <input class="form-check-input"
                                   type="checkbox"
                                   id="whatsapp_vinculado">

                            <label class="form-check-label small"
                                   for="whatsapp_vinculado">
                                Teléfono vinculado a WhatsApp
                            </label>

                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-check">

                            <input class="form-check-input"
                                   type="checkbox"
                                   id="whatsapp_opt_in">

                            <label class="form-check-label small"
                                   for="whatsapp_opt_in">
                                Cliente acepta recibir mensajes
                            </label>

                        </div>
                    </div>

                </div>

                <div class="mt-4 d-flex gap-2">

                    <button type="button"
                            class="btn btn-success"
                            onclick="guardar()">
                        Guardar cambios
                    </button>

                    <button type="button"
                            class="btn btn-outline-secondary"
                            onclick="limpiar()">
                        Cancelar
                    </button>

                </div>

                <div id="msgGuardar" class="mt-2 small"></div>

            </form>

        </div>

        <!-- BANDEJA DE EXPEDIENTES PENDIENTES -->

        <div class="card p-3 shadow-sm border-0">

            <div class="d-flex justify-content-between align-items-center mb-3">

                <h5 class="mb-0">
                    Expedientes pendientes (Web / WhatsApp)
                </h5>

                <button class="btn btn-sm btn-outline-success"
                        onclick="window.location.reload()">
                    ↻ Actualizar
                </button>

            </div>

            <div class="table-responsive">

                <table class="table table-sm table-hover align-middle">

                    <thead class="table-light">
                        <tr>
                            <th>Fecha</th>
                            <th>Nombre</th>
                            <th>Teléfono</th>
                            <th>Origen</th>
                            <th>Estatus</th>
                            <th>Acción</th>
                        </tr>
                    </thead>

                    <tbody id="tablaIncompletos">

                        <?php if ($errorCarga): ?>

                            <tr>
                                <td colspan="6" class="text-center text-danger py-3">
                                    Error al consultar la base de datos.
                                </td>
                            </tr>

                        <?php elseif (empty($pendientes)): ?>

                            <tr>
                                <td colspan="6" class="text-center text-muted py-3">
                                    No hay expedientes pendientes.
                                </td>
                            </tr>

                        <?php else: ?>

                            <?php foreach ($pendientes as $exp): ?>

                                <tr>

                                    <td>
                                        <?= htmlspecialchars($exp['fecha_creacion_web'] ?? '') ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars(trim(
                                            ($exp['nombres'] ?? '') . ' ' .
                                            ($exp['apellidos'] ?? '')
                                        )) ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($exp['telefono_celular'] ?? '') ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($exp['origen_registro'] ?? '') ?>
                                    </td>

                                    <td>
                                        <span class="badge bg-warning text-dark">
                                            <?= htmlspecialchars($exp['estatus_expediente'] ?? '') ?>
                                        </span>
                                    </td>

                                    <td>
                                        <button type="button"
                                                class="btn btn-sm btn-outline-success"
                                                data-id="<?= (int)$exp['id_expediente'] ?>" onclick="atender(this.dataset.id)">
                                            Atender
                                        </button>
                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>
</div>
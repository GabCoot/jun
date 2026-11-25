<?php
require_once __DIR__ . '/../config/acceso.php';

require_once __DIR__ . '/Expediente.php';

$expedientes = [];
$error = null;

try {
    $expedientes = Expediente::listar();
} catch (Throwable $e) {
    error_log($e->getMessage());
    $error = 'No se pudieron cargar los expedientes.';
}

?>

<div class="container-fluid p-0">

    <!-- ENCABEZADO -->

    <div class="d-flex justify-content-between
                align-items-center mb-4">

        <div>
            <h2>Expedientes</h2>
            <p class="text-muted mb-0">
                Gestión de expedientes de pacientes
            </p>
        </div>

        <a href="index.php?pagina=expedientes"
           class="btn btn-success">

            <i class="bi bi-arrow-clockwise"></i>
            Actualizar

        </a>

    </div>

    <!-- TABLA DE EXPEDIENTES -->

    <div class="card">

        <div class="card-body">

            <h5 class="mb-4">
                Expedientes registrados
            </h5>

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Fecha</th>
                            <th>Nombre</th>
                            <th>Teléfono</th>
                            <th>Origen</th>
                            <th>Estatus</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php if ($error): ?>

                        <tr>
                            <td colspan="6"
                                class="text-center text-danger">
                                <?= htmlspecialchars($error) ?>
                            </td>
                        </tr>

                    <?php elseif (empty($expedientes)): ?>

                        <tr>
                            <td colspan="6"
                                class="text-center text-muted">
                                No hay expedientes registrados.
                            </td>
                        </tr>

                    <?php else: ?>

                        <?php foreach ($expedientes as $exp): ?>

                            <tr>

                                <td>
                                    <?= (int) $exp['id_expediente'] ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars(
                                        $exp['fecha_creacion_web'] ?? ''
                                    ) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars(
                                        trim(
                                            ($exp['nombres'] ?? '') . ' ' .
                                            ($exp['apellidos'] ?? '')
                                        )
                                    ) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars(
                                        $exp['telefono_celular'] ?? ''
                                    ) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars(
                                        $exp['origen_registro'] ?? ''
                                    ) ?>
                                </td>

                                <td>

                                    <?php if (
                                        $exp['estatus_expediente'] === 'completo'
                                    ): ?>

                                        <span class="badge bg-success">
                                            Completo
                                        </span>

                                    <?php else: ?>

                                        <span class="badge bg-warning text-dark">
                                            <?= htmlspecialchars(
                                                $exp['estatus_expediente']
                                                ?? 'Sin estatus'
                                            ) ?>
                                        </span>

                                    <?php endif; ?>

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
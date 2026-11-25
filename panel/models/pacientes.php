<?php
require_once __DIR__.'/../config/acceso.php';
require_once __DIR__.'/Expediente.php';
?>
<h2>Pacientes</h2>
<p class="text-muted">Directorio de expedientes registrados</p>
<div class="card p-3">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Teléfono</th>
                </tr>
            </thead>
            <tbody>
<?php try { foreach(Expediente::listar() as $d): ?>                <tr>
                    <td><?= (int)$d['id_expediente'] ?>                    </td>
                    <td><?= htmlspecialchars(trim(($d['nombres']??'').' '.($d['apellidos']??'')),ENT_QUOTES,'UTF-8') ?>                    </td>
                    <td><?= htmlspecialchars($d['telefono_celular']??'',ENT_QUOTES,'UTF-8') ?>                    </td>
                </tr><?php endforeach; }catch(Throwable $e){error_log($e->getMessage());echo '<tr><td colspan="3">Error al consultar MySQL.</td></tr>';} ?>
            </tbody>
        </table>
    </div>
</div>

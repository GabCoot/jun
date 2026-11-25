<?php
require_once __DIR__.'/../config/acceso.php';
require_once __DIR__.'/Agenda.php';
$canales=['Web','Call Center','WhatsApp']; $estados=['agendada','confirmada','atendida','cancelada','no_asistio'];
try {
 $pdo=getDB();
 $servicios=$pdo->query('SELECT * FROM jun_servicios WHERE activo=1 ORDER BY nombre')->fetchAll();
 $q=trim((string)($_GET['q']??''));
 $sqlPacientes="SELECT id_expediente,nombres,apellidos FROM expedientes";
 if($q!=='') {
  $s=$pdo->prepare($sqlPacientes." WHERE CONCAT_WS(' ',nombres,apellidos) LIKE ? OR CAST(id_expediente AS CHAR)=? OR telefono_celular=? ORDER BY nombres,apellidos LIMIT 100");
  $s->execute(['%'.$q.'%',$q,$q]);
 } else {
  $s=$pdo->query($sqlPacientes." ORDER BY id_expediente DESC LIMIT 100");
 }
 $pacientes=$s->fetchAll(PDO::FETCH_ASSOC);
 $totalPacientes=(int)$pdo->query('SELECT COUNT(*) FROM expedientes')->fetchColumn();
 $where=[];$args=[];
 foreach(['canal'=>'canal_origen','estatus'=>'estatus','dia'=>'DATE(fecha)'] as $k=>$col) { if(!empty($_GET[$k]) && is_string($_GET[$k])) {$where[]="$col=?";$args[]=$_GET[$k];} }
 $s=$pdo->prepare('SELECT c.*,s.nombre AS servicio,e.nombres,e.apellidos FROM jun_citas c JOIN jun_servicios s ON s.id=c.servicio_id LEFT JOIN expedientes e ON e.id_expediente=c.expediente_id'.($where?' WHERE '.implode(' AND ',$where):'').' ORDER BY c.fecha DESC LIMIT 200');$s->execute($args);$citas=$s->fetchAll();
 $stats=$pdo->query("SELECT COUNT(*) total, SUM(DATE(fecha)=CURDATE() AND estatus IN ('agendada','confirmada')) hoy, SUM(estatus='confirmada') confirmadas FROM jun_citas")->fetch();
} catch(Throwable $e) {error_log($e->getMessage());echo '<div class="alert alert-danger">No se pudo cargar la agenda. Importe sql/agenda.sql en la base JUN.</div>';return;}
$token=agendaH($_SESSION['agenda_token']);
?>
<style>.agenda-hero{background:linear-gradient(115deg,#075c67,#008f87 65%,#25b7a5);color:white;padding:28px;border-radius:20px;margin-bottom:24px}.agenda-hero h2{color:white;margin:0 0 8px}.agenda-kpi{background:white;border:1px solid #e8eef1;border-radius:16px;padding:20px;height:100%}.agenda-kpi strong{font-size:30px;color:#006e6a;display:block}.agenda-form{background:white;border:1px solid #e8eef1;border-radius:16px;padding:22px;margin-bottom:20px}.agenda-form summary{cursor:pointer;color:#006e6a;font-weight:700}.agenda-table details{min-width:180px}.agenda-table details form{min-width:220px}.agenda-table summary{cursor:pointer;color:#006e6a}</style>
<div class="agenda-hero">
    <h2>
    <i class="bi bi-calendar2-week">
</i> Agenda de citas</h2>
<p class="mb-0">Gestión de servicios y continuidad omnicanal</p>
</div>
<?php if(isset($_SESSION['agenda_flash'])){[$tipo,$msg]=$_SESSION['agenda_flash'];unset($_SESSION['agenda_flash']);echo '<div class="alert alert-'.agendaH($tipo).'">'.agendaH($msg).'</div>';} ?>
<div class="row g-3 mb-4"><?php foreach(['total'=>'Citas registradas','hoy'=>'Citas de hoy','confirmadas'=>'Confirmadas'] as $k=>$v): ?>    <div class="col-md-4">
        <div class="agenda-kpi"><?=agendaH($v)?>            <strong><?= (int)$stats[$k] ?>        </strong>
    </div>
</div><?php endforeach; ?></div>
<div class="agenda-form">
    <h4>Nueva cita</h4>
    <form method="get" class="d-flex gap-2 mb-3">
        <input type="hidden" name="pagina" value="citas">
        <input class="form-control" name="q" value="<?=agendaH($q)?>" placeholder="Buscar por nombre, ID o teléfono">
        <button class="btn btn-primary">Buscar</button>
    </form>
    <p class="text-muted small">Pacientes registrados: <?= $totalPacientes ?>. Se muestran hasta 100; utiliza el buscador para encontrar otros.</p><?php if(!$pacientes): ?>    <p>No se encontraron pacientes.</p><?php endif; ?>
    <form method="post" class="row g-3">
        <input type="hidden" name="token" value="<?=$token?>">
        <input type="hidden" name="accion" value="crear">
        <div class="col-md-6">
            <label class="form-label">Paciente</label>
            <select class="form-select" name="paciente" required>
                <option value="">Seleccione un paciente registrado</option><?php foreach($pacientes as $p): ?>                <option value="<?=(int)$p['id_expediente']?>"><?=agendaH($p['nombres'].' '.$p['apellidos'].' · #'.$p['id_expediente'])?>            </option><?php endforeach;?>        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">Servicio</label>
        <select class="form-select" name="servicio" required>
            <option value="">Seleccione</option><?php foreach($servicios as $s): ?>            <option value="<?=(int)$s['id']?>"><?=agendaH($s['nombre'])?>        </option><?php endforeach;?>    </select>
</div>
<div class="col-md-6">
    <label class="form-label">Fecha y hora</label>
    <input class="form-control" type="datetime-local" name="fecha" min="<?=date('Y-m-d\TH:i')?>" required>
</div>
<div class="col-md-6">
    <label class="form-label">Canal de origen</label>
    <select class="form-select" name="canal" required><?php foreach($canales as $c):?>        <option><?=agendaH($c)?>    </option><?php endforeach;?></select>
</div>
<div class="col-12">
    <button class="btn btn-primary" <?=!$pacientes?'disabled':''?>>Asignar cita</button>
</div>
</form>
</div>
<details class="agenda-form">
<summary>Agregar servicio al catálogo</summary>
<form method="post" class="d-flex gap-2 mt-3">
    <input type="hidden" name="token" value="<?=$token?>">
    <input type="hidden" name="accion" value="servicio">
    <input class="form-control" name="nombre" maxlength="150" required placeholder="Nombre del estudio">
    <button class="btn btn-primary">Agregar</button>
</form>
</details>
<div class="agenda-form">
    <h4>Citas registradas</h4>
    <form method="get" class="row g-2 mb-3">
        <input type="hidden" name="pagina" value="citas">
        <div class="col-md-3">
            <input type="date" class="form-control" name="dia" value="<?=agendaH($_GET['dia']??'')?>">
        </div>
        <div class="col-md-3">
            <select class="form-select" name="canal">
                <option value="">Todos los canales</option><?php foreach($canales as $c):?>                <option <?=($_GET['canal']??'')===$c?'selected':''?>><?=agendaH($c)?>            </option><?php endforeach;?>        </select>
    </div>
    <div class="col-md-3">
        <select class="form-select" name="estatus">
            <option value="">Todos los estatus</option><?php foreach($estados as $e):?>            <option <?=($_GET['estatus']??'')===$e?'selected':''?>><?=agendaH($e)?>        </option><?php endforeach;?>    </select>
</div>
<div class="col-md-3">
    <button class="btn btn-primary">Filtrar</button>
    <a href="index.php?pagina=citas" class="btn btn-outline-primary">Limpiar</a>
</div>
</form>
<div class="table-responsive agenda-table">
    <table class="table table-hover">
        <thead>
            <tr>
                <th>Paciente / servicio</th>
                <th>Fecha</th>
                <th>Origen / último canal</th>
                <th>Estatus</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody><?php foreach($citas as $c):?>            <tr>
                <td><?=agendaH(($c['nombres']??'').' '.($c['apellidos']??'').' #'.$c['expediente_id'])?>                    <br>
                    <small><?=agendaH($c['servicio'])?>                </small>
            </td>
            <td><?=agendaH($c['fecha'])?>            </td>
            <td><?=agendaH($c['canal_origen'])?>                <br>
                <small><?=agendaH($c['ultimo_canal'])?>            </small>
        </td>
        <td>
            <span class="badge badge-<?=agendaH($c['estatus'])?>"><?=agendaH($c['estatus'])?>        </span>
    </td>
    <td>
        <details>
        <summary>Gestionar cita</summary>
        <form method="post" class="mt-2">
            <input type="hidden" name="token" value="<?=$token?>">
            <input type="hidden" name="accion" value="editar">
            <input type="hidden" name="id" value="<?=(int)$c['id']?>">
            <label>Fecha y hora</label>
            <input class="form-control mb-2" type="datetime-local" name="fecha" value="<?=agendaH(str_replace(' ','T',substr($c['fecha'],0,16)))?>" required>
            <label>Estatus</label>
            <select class="form-select mb-2" name="estatus"><?php foreach($estados as $e):?>                <option <?=$c['estatus']===$e?'selected':''?>><?=agendaH($e)?>            </option><?php endforeach;?>        </select>
        <label>Canal de esta atención</label>
        <select class="form-select mb-2" name="canal"><?php foreach($canales as $ch):?>            <option <?=$ch===$c['ultimo_canal']?'selected':''?>><?=agendaH($ch)?>        </option><?php endforeach;?>    </select>
    <button class="btn btn-primary btn-sm">Guardar cambios</button>
</form>
<details class="mt-2">
<summary>Historial</summary><?php $h=$pdo->prepare('SELECT * FROM jun_citas_historial WHERE cita_id=? ORDER BY id DESC');$h->execute([$c['id']]);foreach($h as $r):?><p class="small mb-2"><?=agendaH($r['registrado'].' · '.$r['canal'])?><br><?=agendaH($r['fecha_anterior'].' → '.$r['fecha_nueva'])?><br><?=agendaH($r['estatus_anterior'].' → '.$r['estatus_nuevo'])?></p><?php endforeach;?></details>
</details>
</td>
</tr><?php endforeach;?><?php if(!$citas):?><tr>
    <td colspan="5">No hay citas para mostrar.</td>
</tr><?php endif;?></tbody>
</table>
</div>
<small class="text-muted">Se muestran hasta 200 citas. La disponibilidad se verifica manualmente.</small>
</div>

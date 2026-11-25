<?php
require_once __DIR__.'/seguridad.php';
// Configurar credenciales administrativas independientes en el hosting.
$user=getenv('JUN_ADMIN_USER'); $hash=getenv('JUN_ADMIN_HASH');
if (!$user || !$hash) {http_response_code(503);exit('Administración no configurada.');}
if (!hash_equals($user,$_SERVER['PHP_AUTH_USER']??'') || !password_verify($_SERVER['PHP_AUTH_PW']??'', $hash)) {
 header('WWW-Authenticate: Basic realm="JUN Admin"');http_response_code(401);exit('Acceso administrativo requerido.');
}
require_once __DIR__.'/conexion.php';
function h($v){return htmlspecialchars((string)$v,ENT_QUOTES,'UTF-8');}
$q=strtoupper(trim((string)($_POST['curp']??'')));$rows=[];$error='';
try {if($q!==''){ $s=getDB()->prepare('SELECT t.*, e.id_expediente AS paciente FROM jun_chat_tramites_curp t LEFT JOIN expedientes e ON e.curp=t.curp WHERE t.curp=? ORDER BY t.id DESC LIMIT 100');$s->execute([$q]);$rows=$s->fetchAll();}}catch(Throwable $e){error_log($e->getMessage());$error='No se pudo consultar MySQL.';}
?><!doctype html><html lang="es"><meta charset="utf-8"><title>JUN | Trámites WhatsApp</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"><body style="background:#f4f8fa;color:#183a50"><main class="container py-4"><a href="../index.php">Volver al panel JUN</a><h2 class="mt-3">Trámites WhatsApp</h2><form method="post" class="d-flex gap-2 my-3"><input class="form-control" name="curp" maxlength="18" placeholder="CURP del paciente" value="<?=h($q)?>" required><button class="btn btn-success">Buscar</button></form><p><?=h($error)?></p><?php if($q!==''):?><div class="table-responsive"><table class="table"><thead><tr><th>CURP</th><th>Expediente</th><th>Nombre declarado</th><th>Servicio</th><th>Fecha solicitada</th><th>Estado</th><th>Canal</th></tr></thead><tbody><?php foreach($rows as $r):?><tr><td><?=h($r['curp'])?></td><td><?=h($r['paciente']??'Pendiente de vincular')?></td><td><?=h($r['nombre'])?></td><td><?=h($r['servicio'])?></td><td><?=h($r['fecha_preferida'])?></td><td><?=h($r['estatus'])?></td><td><?=h($r['canal_actual'])?></td></tr><?php endforeach;?></tbody></table></div><?php if(!$rows):?><p>No hay solicitudes para esta CURP.</p><?php endif; endif;?></main></body></html>

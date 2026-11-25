<?php
require __DIR__.'/seguridad.php';
header('Content-Type: application/json; charset=utf-8');
require __DIR__.'/conexion.php';
require __DIR__.'/lenguaje.php';
require __DIR__.'/registro.php';
date_default_timezone_set('America/Merida');
function responder($mensaje){echo json_encode(['mensaje'=>$mensaje],JSON_UNESCAPED_UNICODE);exit;}

function mostrarPerfilDemo(PDO $db,string $curp): void {

 responder('Por privacidad, la consulta de expedientes requiere verificación de identidad en la clínica. Puedes recuperar una solicitud con tu código privado.');
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !hash_equals($_SESSION['chat_csrf']??'!', $_SERVER['HTTP_X_CHAT_TOKEN']??'')) {http_response_code(403);responder('Actualiza la página.');}
$texto=trim((string)($_POST['mensaje']??''));
if ($texto==='' || strlen($texto)>300) responder('Escribe un mensaje de hasta 300 caracteres.');
$t=normalizar($texto);
$menu="¿En qué puedo ayudarte?\n1. Servicios\n2. Iniciar trámite\n3. Recuperar trámite\n4. Horarios e información\n5. Consultar expediente";
$servicios=['1'=>'Análisis clínicos','2'=>'Ultrasonido','3'=>'Rayos X','4'=>'Electrocardiograma','5'=>'Mastografía'];
$lista="Selecciona un servicio:\n1. Análisis clínicos\n2. Ultrasonido\n3. Rayos X\n4. Electrocardiograma\n5. Mastografía";
try {
 if (in_array($t,['menu','salir','volver al menu','cancelar','reiniciar'],true)) {unset($_SESSION['tramite'],$_SESSION['recuperar'],$_SESSION['identificar'],$_SESSION['curp_busqueda'],$_SESSION['perfil_curp'],$_SESSION['registro_paso'],$_SESSION['registro_datos']);responder($menu);}
 $db=getDB();
 if (!empty($_SESSION['ultimo_mensaje']) && microtime(true)-$_SESSION['ultimo_mensaje']<0.4) responder('Espera un momento.');
 $_SESSION['ultimo_mensaje']=microtime(true);
 flujoRegistro($db,$texto,$t);
 if(in_array($t,['crear cuenta','registrarme','registro','registrar paciente','no tengo cuenta','tengo cuenta'],true)){$_SESSION['registro_paso']='cuenta';responder(preguntaCuenta());}
 // Consulta de perfil exclusivamente para demostración local con datos ficticios.
 if (!empty($_SESSION['perfil_curp'])) {
  $curp=strtoupper(preg_replace('/\s+/','',$texto));
  if (!preg_match('/^[A-Z]{4}\d{6}[HMX][A-Z]{5}[A-Z0-9]\d$/',$curp)) responder('Escribe una CURP ficticia de 18 caracteres.');
  unset($_SESSION['perfil_curp']);
  mostrarPerfilDemo($db,$curp);
 }
 if (in_array($t,['5','mi expediente','mis datos','consultar expediente','ver expediente','consultar mis datos','ver mis datos'],true)) {
  $_SESSION['perfil_curp']=true; responder('Escribe la CURP del paciente ficticio para consultar su expediente de demostración.');
 }

 if (!empty($_SESSION['identificar'])) {
  $curp=strtoupper(preg_replace('/\s+/','',$texto));
  if (!preg_match('/^[A-Z]{4}\d{6}[HMX][A-Z]{5}[A-Z0-9]\d$/',$curp)) responder('Escribe una CURP ficticia de 18 caracteres con formato válido.');
  $modo=$_SESSION['identificar'];
  $rows=[];
  $aviso='CURP recibida. La clínica verificará la identidad y vinculará el expediente si corresponde.';
  unset($_SESSION['identificar']);
  if ($modo==='recuperar') {$_SESSION['curp_busqueda']=$curp;$_SESSION['recuperar']=true;responder($aviso."\nEscribe tu código privado de recuperación.");}
  $codigo=strtoupper(bin2hex(random_bytes(16)));
  $db->prepare('INSERT INTO jun_chat_tramites_curp (codigo_hash,curp,expediente_id) VALUES (?,?,?)')->execute([hash('sha256',$codigo),$curp,$rows[0]['id_expediente']??null]);
  $_SESSION['tramite']=$db->lastInsertId();
  responder("$aviso\nGuarda tu código privado de recuperación:\n$codigo\n¿Cuál es tu nombre ficticio?");
 }
 if (!empty($_SESSION['recuperar'])) {
  $s=$db->prepare('SELECT * FROM jun_chat_tramites_curp WHERE codigo_hash=? AND curp=?');$s->execute([hash('sha256',strtoupper(preg_replace('/\s+/','',$texto))),$_SESSION['curp_busqueda']??'']);$r=$s->fetch();
  if (!$r) responder('Código no encontrado. Intenta nuevamente o escribe menú.');
  unset($_SESSION['recuperar']);$_SESSION['tramite']=$r['id'];
  responder('Trámite recuperado. Estado: '.$r['estatus']."\nServicio: ".($r['servicio']??'Sin seleccionar')."\nFecha preferida: ".($r['fecha_preferida']??'Sin seleccionar')."\n".match($r['paso']){'nombre'=>'Escribe tu nombre ficticio.','servicio'=>$lista,'fecha'=>'Escribe la fecha preferida (AAAA-MM-DD).','confirmar'=>'Escribe confirmar para enviar la solicitud.',default=>'Solicitud guardada. Escribe menú para volver.'});
 }
 if (!empty($_SESSION['tramite'])) {
  $s=$db->prepare('SELECT * FROM jun_chat_tramites_curp WHERE id=?');$s->execute([$_SESSION['tramite']]);$r=$s->fetch();
  if ($r && $r['paso']!=='fin') {
   $id=$r['id'];
   switch($r['paso']) {
    case 'nombre': if(strlen($texto)>120 || !preg_match('/^[\p{L} .-]+$/u',$texto)) responder('Escribe un nombre ficticio válido.');$db->prepare("UPDATE jun_chat_tramites_curp SET nombre=?,paso='servicio' WHERE id=?")->execute([$texto,$id]);responder($lista);
    case 'servicio': $elegido=servicioElegido($texto,$servicios);if(!$elegido) responder('No identifiqué un único servicio. '.$lista);$db->prepare("UPDATE jun_chat_tramites_curp SET servicio=?,paso='fecha' WHERE id=?")->execute([$elegido,$id]);responder('¿Qué fecha prefieres? Escribe AAAA-MM-DD.');
    case 'fecha': $fecha=fechaElegida($texto);if(!$fecha) responder('¿Qué día prefieres? Puedes escribir mañana o una fecha como 25/11/2026.');$texto=$fecha;$db->prepare("UPDATE jun_chat_tramites_curp SET fecha_preferida=?,paso='confirmar' WHERE id=?")->execute([$texto,$id]);responder("Servicio: {$r['servicio']}\nFecha preferida: $texto\nEscribe confirmar para guardar tu solicitud. No es una cita confirmada.");
    case 'confirmar': if(!in_array($t,['confirmar','confirmo','si','si confirmo','si por favor','de acuerdo','acepto','correcto','adelante','ok'],true)) responder('Escribe confirmar o menú para salir y continuar después.');$db->prepare("UPDATE jun_chat_tramites_curp SET paso='fin',estatus='finalizado',canal_finalizacion='whatsapp' WHERE id=?")->execute([$id]);unset($_SESSION['tramite']);responder('Solicitud registrada correctamente. La clínica deberá confirmar disponibilidad. Escribe menú para volver.');
   }
  }
 }
 if(preg_match('/^[A-Z]{4}\d{6}[HMX][A-Z]{5}[A-Z0-9]\d$/',strtoupper(preg_replace('/\s+/','',$texto)))) {
  mostrarPerfilDemo($db,strtoupper(preg_replace('/\s+/','',$texto)));
 }
 $intent=['1'=>'servicios','2'=>'nuevo','3'=>'recuperar','4'=>'info'][$t]??intencion($texto);
 switch($intent){
 case 'nuevo': $_SESSION['identificar']='nuevo';responder('Con gusto te ayudo a iniciar tu solicitud. ¿Cuál es la CURP ficticia del paciente?');
 case 'recuperar': $_SESSION['identificar']='recuperar';responder('Vamos a continuar tu trámite. Escribe la CURP del paciente.');
 case 'servicios': responder("Estos son nuestros servicios de demostración:\n".implode("\n",$servicios)."\nLos precios y disponibilidad deben confirmarse con la clínica. Si deseas solicitar una cita, escribe agendar cita.");
 case 'info': responder('Los horarios y ubicación deben confirmarse con la clínica. ¿Quieres consultar servicios o iniciar una solicitud?');
 case 'saludo': responder("¡Hola! Soy el asistente virtual de JUN. 😊\n".$menu);
 case 'gracias': responder('¡Con gusto! Si necesitas algo más, escribe menú.');
 default: responder("No entendí del todo tu mensaje. ¿Buscas información, agendar una cita o continuar un trámite?\n".$menu);
 }

} catch(Throwable $e){error_log($e->getMessage());responder('No pude consultar la base de datos. Verifica MySQL y la instalación de instalar.sql.');}

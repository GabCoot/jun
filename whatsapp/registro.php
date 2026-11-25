<?php
// Registro conversacional para demostración con datos ficticios.
function preguntaCuenta(): string { return "¿Ya tienes una cuenta en JUN?\nResponde sí o no."; }
function flujoRegistro(PDO $db,string $texto,string $t): void {
 $paso=$_SESSION['registro_paso']??'';
 $si=in_array($t,['si','si tengo','si quiero','si por favor','claro','acepto','confirmar','confirmo','correcto','ok','de acuerdo'],true);
 $no=in_array($t,['no','no tengo','no tengo cuenta','aun no','todavia no','no gracias'],true);
 if ($paso==='') return;
 if (in_array($t,['call center','llamar','llamar al call center','asesor','hablar con un asesor'],true)) {
  unset($_SESSION['registro_paso'],$_SESSION['registro_datos']);
  $tel=getenv('JUN_CALL_CENTER');
  responder('Puedes solicitar ayuda al Call Center. '.($tel?'Teléfono: '.$tel:'Consulta el número oficial de la clínica.').' Escribe menú para volver.');
 }
 if ($paso==='cuenta') {
  if($si){$_SESSION['registro_paso']='existente';responder('Escribe tu CURP. Para acceder a una cuenta existente se requiere verificación de identidad en la clínica.');}
  if($no){$_SESSION['registro_paso']='oferta';responder('¿Quieres crear una cuenta en JUN por este chat? Responde sí, no o Call Center.');}
  responder(preguntaCuenta());
 }
 if ($paso==='oferta') {
  if($no){unset($_SESSION['registro_paso']);responder('De acuerdo. Puedes contactar al Call Center o escribir menú.');}
  if(!$si)responder('¿Deseas registrarte? Responde sí, no o Call Center.');
  $_SESSION['registro_datos']=[];$_SESSION['registro_paso']='curp';responder('Usa únicamente datos ficticios en este prototipo. ¿Cuál es tu CURP de prueba?');
 }
 if(in_array($paso,['curp','existente'],true)){
  $curp=strtoupper(preg_replace('/\s+/','',$texto));
  if(!preg_match('/^[A-Z]{4}\d{6}[HMX][A-Z]{5}[A-Z0-9]\d$/',$curp))responder('Escribe una CURP de 18 caracteres o Call Center.');
  if($paso==='existente') {$_SESSION['registro_paso']='oferta';responder('Si ya tienes cuenta, verifica tu identidad con el Call Center. Si todavía no tienes cuenta, ¿quieres iniciar un registro nuevo? Responde sí, no o Call Center.');}
  $_SESSION['registro_datos']=['curp'=>$curp];$_SESSION['registro_paso']='nombres';responder('¿Cuáles son tus nombres?');
 }
 $d=&$_SESSION['registro_datos'];
 if(in_array($paso,['nombres','apellidos'],true)){
  if(strlen($texto)>100||!preg_match('/^[\p{L} .\-]+$/u',$texto))responder('Escribe un nombre válido.');
  $d[$paso]=$texto;$_SESSION['registro_paso']=$paso==='nombres'?'apellidos':'telefono';responder($paso==='nombres'?'¿Cuáles son tus apellidos?':'¿Cuál es tu teléfono ficticio de 10 dígitos?');
 }
 if($paso==='telefono'){
  $tel=preg_replace('/[\s()-]/','',$texto);if(!preg_match('/^\d{10}$/',$tel))responder('Escribe 10 dígitos.');
  $d['telefono']=$tel;$_SESSION['registro_paso']='correo';responder('¿Cuál es tu correo ficticio? Puedes escribir omitir.');
 }
 if($paso==='correo'){
  if($t!=='omitir'&&(!filter_var($texto,FILTER_VALIDATE_EMAIL)||strlen($texto)>100))responder('Escribe un correo válido u omitir.');
  $d['correo']=$t==='omitir'?null:$texto;$_SESSION['registro_paso']='confirmar';responder("Revisa tus datos:\nNombre: {$d['nombres']} {$d['apellidos']}\nCURP: {$d['curp']}\nTeléfono: {$d['telefono']}\nCorreo: ".($d['correo']??'Sin registrar')."\n¿Confirmas el registro? Escribe confirmar o cancelar.");
 }
 if($paso==='confirmar'){
  if(!$si)responder('Escribe confirmar o cancelar.');
  // No revelar si una CURP pertenece a otra persona ni sobrescribir expedientes.
  if(getenv('JUN_REGISTRO_DEMO')!=='1'){unset($_SESSION['registro_paso'],$_SESSION['registro_datos']);responder('Registro de demostración desactivado. Contacta al Call Center.');}
  try {
   $s=$db->prepare("INSERT INTO expedientes (curp,nombres,apellidos,telefono_celular,correo_electronico,origen_registro,estatus_expediente) VALUES (?,?,?,?,?,'whatsapp','incompleto')");
   $s->execute([$d['curp'],$d['nombres'],$d['apellidos'],$d['telefono'],$d['correo']]);
  }catch(PDOException $e){if(($e->errorInfo[1]??0)!=1062)throw $e;}
  unset($_SESSION['registro_paso'],$_SESSION['registro_datos']);
  responder('Solicitud de registro procesada. La clínica deberá verificar tu identidad y confirmar tu cuenta. Si ya existía un expediente, no se modificó. Escribe menú para consultar servicios o iniciar un trámite.');
 }
}

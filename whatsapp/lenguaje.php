<?php
function normalizar(string $s): string {
 $s=strtr($s,['Á'=>'a','É'=>'e','Í'=>'i','Ó'=>'o','Ú'=>'u','Ü'=>'u','Ñ'=>'n','á'=>'a','é'=>'e','í'=>'i','ó'=>'o','ú'=>'u','ü'=>'u','ñ'=>'n']);
 $s=strtolower($s); $s=preg_replace('/[^a-z0-9 ]/',' ',$s); return trim(preg_replace('/\s+/',' ',$s));
}
function tiene(string $s,array $palabras): bool {
 foreach($palabras as $p){if(str_contains(' '.$s.' ',' '.$p.' '))return true; if(strlen($p)>=5 && !str_contains($p,' '))foreach(explode(' ',$s) as $w){if(strlen($w)>=4 && levenshtein($w,$p)<=1)return true;}}
 return false;
}
function intencion(string $s): string {
 $s=normalizar($s);
 if(tiene($s,['recuperar','continuar','retomar','pendiente','ya empece','ya habia','seguir','mi tramite','mi registro']))return 'recuperar';
 if(tiene($s,['precio','precios','cuanto','cuesta','costo','costos','servicios','informacion de estudios','que estudios']))return 'servicios';
 if(tiene($s,['horario','horarios','ubicacion','direccion','donde','abren','cierran']))return 'info';
 if(tiene($s,['iniciar','agendar','agenda','cita','registro','registrar','tramite','reservar','hacerme','sacar cita']))return 'nuevo';
 if(tiene($s,['ultrasonido','analisis','rayos x','electrocardiograma','mastografia','estudios','estudio']))return 'servicios';
 if(tiene($s,['hola','buenas','buenos dias','buenas tardes','buenas noches','hey']))return 'saludo';
 if(tiene($s,['gracias','muchas gracias']))return 'gracias';
 return 'desconocido';
}
function servicioElegido(string $s,array $servicios): ?string {
 $n=normalizar($s); if(isset($servicios[$n]))return $servicios[$n];
 $alias=[1=>['analisis','laboratorio','sangre','analisis clinicos'],2=>['ultrasonido','eco','ecografia'],3=>['rayos x','radiografia'],4=>['electrocardiograma','electro'],5=>['mastografia','mamografia']];
 $matches=[];foreach($alias as $i=>$a)if(tiene($n,$a))$matches[]=$servicios[$i];return count($matches)===1?$matches[0]:null;
}
function fechaElegida(string $s): ?string {
 $n=normalizar($s); $h=new DateTimeImmutable('today');
 if(in_array($n,['hoy','para hoy']))return $h->format('Y-m-d');
 if(in_array($n,['manana','para manana']))return $h->modify('+1 day')->format('Y-m-d');
 if(in_array($n,['pasado manana','para pasado manana']))return $h->modify('+2 days')->format('Y-m-d');
 foreach(['Y-m-d','d/m/Y','d-m-Y'] as $f){$d=DateTimeImmutable::createFromFormat('!'.$f,trim($s));if($d && $d->format($f)===trim($s) && $d >= $h)return $d->format('Y-m-d');}return null;
}

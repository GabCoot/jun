<?php
require_once __DIR__ . '/../config/acceso.php';
require_once __DIR__.'/../models/Expediente.php';
try {
    $id=filter_var($_GET['id']??null,FILTER_VALIDATE_INT);
    if(!$id||$id<1) jsonResponse(['error'=>'ID inválido'],400);
    $d=Expediente::porId($id);
    if(!$d) jsonResponse(['error'=>'Expediente no encontrado'],404);
    jsonResponse(['success'=>true,'data'=>$d]);
} catch(Throwable $e){
    error_log($e->getMessage());
    jsonResponse(['error'=>'Error al consultar'],500);
}

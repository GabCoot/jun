<?php
require_once __DIR__ . '/../config/acceso.php';
require_once __DIR__.'/../models/Expediente.php';
try {
    jsonResponse(Expediente::incompletos());
}catch(Throwable $e){
    error_log($e->getMessage());
    jsonResponse(['error'=>'Error al consultar'],500);
}

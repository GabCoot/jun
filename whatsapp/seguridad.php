<?php
// Hosting: HTTPS obligatorio. No confiar en cabeceras de proxy sin configurar.
$local=in_array($_SERVER['REMOTE_ADDR']??'', ['127.0.0.1','::1'],true);
$https=!empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS'])!=='off';
if (!$local && !$https) {http_response_code(403);exit('Accede mediante HTTPS.');}
header('Cache-Control: no-store');
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: no-referrer');
ini_set('session.use_strict_mode','1');
session_set_cookie_params(['httponly'=>true,'secure'=>$https,'samesite'=>'Strict','path'=>'/']);
session_start();

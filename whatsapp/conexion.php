<?php
function getDB(): PDO {
 static $db;
 if ($db) return $db;
 $host=getenv('JUN_DB_HOST') ?: 'localhost';
 $name=getenv('JUN_DB_NAME'); $user=getenv('JUN_DB_USER'); $pass=getenv('JUN_DB_PASSWORD');
 if (!$name || !$user || $pass===false) throw new RuntimeException('Configura las variables de MySQL.');
 return $db=new PDO("mysql:host=$host;dbname=$name;charset=utf8mb4",$user,$pass,[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC,PDO::ATTR_EMULATE_PREPARES=>false]);
}

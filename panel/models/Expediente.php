<?php
require_once __DIR__.'/../config/database.php';
class Expediente {
    private const CAMPOS = ['curp','nombres','apellidos','fecha_nacimiento','sexo','estado_civil','telefono_celular','correo_electronico','codigo_postal','estado','municipio','colonia','calle','whatsapp_vinculado','whatsapp_opt_in'];
    public static function listar(int $limite=100): array {
        return self::lista($limite,false);
    }
    public static function incompletos(int $limite=50): array {
        return self::lista($limite,true);
    }
    private static function lista(int $limite,bool $pendientes): array {
        $sql='SELECT id_expediente,nombres,apellidos,telefono_celular,origen_registro,estatus_expediente,fecha_creacion_web FROM expedientes'.($pendientes?" WHERE estatus_expediente = 'incompleto'":'').' ORDER BY fecha_creacion_web DESC LIMIT ?';
        $s=getDB()->prepare($sql);
        $s->bindValue(1,max(1,$limite),PDO::PARAM_INT);
        $s->execute();
        return $s->fetchAll();
    }
    public static function buscar(string $q): ?array {
        $q=trim($q);
        $curp=preg_match('/^[A-Z]{4}\d{6}[HMX][A-Z]{5}[A-Z0-9]\d$/',strtoupper($q));
        $s=getDB()->prepare('SELECT * FROM expedientes WHERE '.($curp?'curp':'telefono_celular').' = ? LIMIT 2');
        $s->execute([$curp?strtoupper($q):$q]);
        $rows=$s->fetchAll();
        if(count($rows)>1) throw new InvalidArgumentException('Hay varios expedientes con ese teléfono; seleccione el expediente por ID.');
        return $rows[0]??null;
    }
    public static function porId(int $id): ?array {
        $s=getDB()->prepare('SELECT * FROM expedientes WHERE id_expediente = ?');
        $s->execute([$id]);
        return $s->fetch()?:null;
    }
    public static function actualizar(int $id,array $datos,string $agente): void {
        $sets=[];
        $params=[];
        foreach(self::CAMPOS as $c){
            if(array_key_exists($c,$datos)){
                $v=$datos[$c];
                if(!is_scalar($v)&&$v!==null) throw new InvalidArgumentException('Dato inválido');
                if($c==='curp' && $v!==' ' && $v!==null && $v!==''){
                    $v=strtoupper(trim((string)$v));
                    if(!preg_match('/^[A-Z]{4}\d{6}[HMX][A-Z]{5}[A-Z0-9]\d$/',$v)) throw new InvalidArgumentException('Formato de CURP inválido');
                } $sets[]="$c = ?";
                $params[]=($v===''?null:$v);
            }
        }
        if(!$sets) throw new InvalidArgumentException('No hay campos para actualizar');
        $sets[]='atendido_por_call_center = ?';
        $params[]=$agente;
        $params[]=$id;
        getDB()->prepare('UPDATE expedientes SET '.implode(', ',$sets).' WHERE id_expediente = ?')->execute($params);
    }
}

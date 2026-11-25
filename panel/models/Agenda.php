<?php
require_once __DIR__.'/../config/database.php';
function agendaH($v): string {
    return htmlspecialchars((string)$v, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
function agendaFecha($v): string {
    $d=DateTimeImmutable::createFromFormat('!Y-m-d\TH:i',(string)$v);
    if (!$d || $d->format('Y-m-d\TH:i')!==$v) throw new InvalidArgumentException('Fecha u hora inválida.');
    return $d->format('Y-m-d H:i:s');
}
function agendaPost(): void {
    if ($_SERVER['REQUEST_METHOD']!=='POST') return;
    if (!hash_equals($_SESSION['agenda_token'], (string)($_POST['token']??''))) {
        http_response_code(403);
        exit('Solicitud inválida.');
    }
    $pdo=getDB();
    try {
        $accion=$_POST['accion']??'';
        if ($accion==='servicio') {
            $nombre=trim((string)($_POST['nombre']??''));
            if ($nombre==='' || strlen($nombre)>150) throw new InvalidArgumentException('Nombre inválido.');
            $pdo->prepare('INSERT INTO jun_servicios(nombre) VALUES (?)')->execute([$nombre]);
        } else {
            $canal=$_POST['canal']??'';
            if (!in_array($canal,['Web','Call Center','WhatsApp'],true)) throw new InvalidArgumentException('Seleccione un canal válido.');
            $fecha=agendaFecha($_POST['fecha']??'');
            $pdo->beginTransaction();
            if ($accion==='crear') {
                $id=filter_var($_POST['paciente']??null,FILTER_VALIDATE_INT);
                $servicio=filter_var($_POST['servicio']??null,FILTER_VALIDATE_INT);
                $s=$pdo->prepare('SELECT id_expediente FROM expedientes WHERE id_expediente=?');
                $s->execute([$id]);
                if (!$id || !$s->fetch()) throw new InvalidArgumentException('Paciente no encontrado.');
                $s=$pdo->prepare('SELECT id FROM jun_servicios WHERE id=? AND activo=1');
                $s->execute([$servicio]);
                if (!$servicio || !$s->fetch()) throw new InvalidArgumentException('Servicio inválido.');
                if ($fecha<date('Y-m-d H:i:s')) throw new InvalidArgumentException('Seleccione una fecha futura.');
                $pdo->prepare('INSERT INTO jun_citas(expediente_id,servicio_id,fecha,canal_origen,ultimo_canal) VALUES (?,?,?,?,?)')->execute([$id,$servicio,$fecha,$canal,$canal]);
            } elseif ($accion==='editar') {
                $id=filter_var($_POST['id']??null,FILTER_VALIDATE_INT);
                $estado=$_POST['estatus']??'';
                if (!in_array($estado,['agendada','confirmada','atendida','cancelada','no_asistio'],true)) throw new InvalidArgumentException('Estatus inválido.');
                $s=$pdo->prepare('SELECT * FROM jun_citas WHERE id=? FOR UPDATE');
                $s->execute([$id]);
                $old=$s->fetch();
                if (!$old) throw new InvalidArgumentException('Cita no encontrada.');
                if ($fecha!==$old['fecha'] && $fecha<date('Y-m-d H:i:s')) throw new InvalidArgumentException('La nueva fecha debe ser futura.');
                $pdo->prepare('UPDATE jun_citas SET fecha=?,estatus=?,ultimo_canal=? WHERE id=?')->execute([$fecha,$estado,$canal,$id]);
                $pdo->prepare('INSERT INTO jun_citas_historial(cita_id,fecha_anterior,fecha_nueva,estatus_anterior,estatus_nuevo,canal) VALUES (?,?,?,?,?,?)')->execute([$id,$old['fecha'],$fecha,$old['estatus'],$estado,$canal]);
            } else throw new InvalidArgumentException('Acción inválida.');
            $pdo->commit();
        }
        $_SESSION['agenda_flash']=['success','Cambios guardados.'];
    } catch(Throwable $e) {
        if($pdo->inTransaction()) $pdo->rollBack();
        error_log($e->getMessage());
        $_SESSION['agenda_flash']=['danger',$e instanceof InvalidArgumentException?$e->getMessage():'No se pudo guardar. Revise la instalación SQL.'];
    }
    header('Location: index.php?pagina=citas');
    exit;
}

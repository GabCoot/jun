<?php
require_once __DIR__ . '/config/sesion.php';
require_once __DIR__ . '/config/database.php';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals($_SESSION['panel_csrf'], (string) ($_POST['token'] ?? ''))) {
        http_response_code(403);
        exit('Solicitud inválida.');
    }
    $usuario = trim((string) ($_POST['usuario'] ?? ''));
    $clave = (string) ($_POST['clave'] ?? '');
    try {
        $pdo = getDB();
        $stmt = $pdo->prepare('SELECT id, usuario, clave_hash, intentos, bloqueado_hasta FROM jun_panel_usuarios WHERE usuario = ? AND activo = 1');
        $stmt->execute([$usuario]);
        $fila = $stmt->fetch();
        $bloqueado = $fila && $fila['bloqueado_hasta'] && strtotime($fila['bloqueado_hasta']) > time();
        if ($fila && !$bloqueado && password_verify($clave, $fila['clave_hash'])) {
            $pdo->prepare('UPDATE jun_panel_usuarios SET intentos = 0, bloqueado_hasta = NULL WHERE id = ?')->execute([$fila['id']]);
            session_regenerate_id(true);
            $_SESSION['panel_usuario'] = $fila['usuario'];
            $_SESSION['panel_actividad'] = time();
            $_SESSION['panel_csrf'] = bin2hex(random_bytes(32));
            header('Location: index.php');
            exit;
        }
        if ($fila && !$bloqueado) {
            $pdo->prepare('UPDATE jun_panel_usuarios SET bloqueado_hasta = IF(intentos >= 4, DATE_ADD(NOW(), INTERVAL 15 MINUTE), NULL), intentos = IF(intentos >= 4, 0, intentos + 1) WHERE id = ?')->execute([$fila['id']]);
        }
        $error = 'Credenciales incorrectas o acceso temporalmente bloqueado.';
    } catch (Throwable $e) {
        error_log($e->getMessage());
        $error = 'No se pudo iniciar sesión. Revise la configuración de la base de datos.';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>JUN | Inicio de sesión</title>
    <link rel="stylesheet" href="assets/css/login.css">
</head>
<body>
    <main class="login-card">
        <div class="brand">JUN <span>·</span> SALUD DIGNA</div>
        <h1>Bienvenido</h1>
        <p>Acceso al panel administrativo</p>
        <?php if ($error): ?>
            <p class="error" role="alert"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif; ?>
        <form method="post">
            <input type="hidden" name="token" value="<?= htmlspecialchars($_SESSION['panel_csrf'], ENT_QUOTES, 'UTF-8') ?>">
            <label for="usuario">Usuario</label>
            <input id="usuario" name="usuario" autocomplete="username" required maxlength="80">
            <label for="clave">Contraseña</label>
            <input id="clave" name="clave" type="password" autocomplete="current-password" required>
            <button type="submit">Iniciar sesión</button>
        </form>
    </main>
</body>
</html>

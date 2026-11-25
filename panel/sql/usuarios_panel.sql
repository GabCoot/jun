CREATE TABLE IF NOT EXISTS jun_panel_usuarios (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(80) NOT NULL UNIQUE,
    clave_hash VARCHAR(255) NOT NULL,
    activo TINYINT(1) NOT NULL DEFAULT 1,
    intentos INT NOT NULL DEFAULT 0,
    bloqueado_hasta DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT IGNORE INTO jun_panel_usuarios (usuario, clave_hash)
VALUES ('admin', '$2y$12$eb/1VGAVDVuOiv7EKfhzhO5B862ooO1VxsPwltoJvnHl5MaOVou7.');

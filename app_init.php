<?php
declare(strict_types=1);
require_once __DIR__ . '/conexion.php';

/** Inicialización compatible con el esquema original: agrega solo campos/tablas faltantes. */
function dogvive_db(): PDO {
    static $pdo = null;
    if ($pdo instanceof PDO) return $pdo;
    $pdo = dogvive_pdo();
    $columns = [
        'usuario' => [
            'Documento' => 'VARCHAR(20) NULL', 'Usuario' => 'VARCHAR(60) NULL', 'Ciudad' => 'VARCHAR(100) NULL',
            'Tiene_mascotas' => 'VARCHAR(10) NULL', 'Relacion_mascota' => 'VARCHAR(40) NULL', 'Tipo_mascota' => 'VARCHAR(40) NULL',
            'Telefono_emergencia' => 'VARCHAR(20) NULL', 'Tipo_certificacion' => 'VARCHAR(100) NULL', 'Documento_profesional' => 'VARCHAR(100) NULL',
            'Foto_perfil' => 'VARCHAR(255) NULL', 'Estado_verificacion' => "VARCHAR(30) NOT NULL DEFAULT 'Pendiente'"
        ],
        'mascota' => [
            'Fecha_nacimiento' => 'DATE NULL', 'Color' => 'VARCHAR(60) NULL', 'Microchip' => 'VARCHAR(80) NULL',
            'Alergias' => 'TEXT NULL', 'Condiciones_medicas' => 'TEXT NULL', 'Contacto_veterinario' => 'VARCHAR(150) NULL',
            'Observaciones' => 'TEXT NULL', 'Foto' => 'VARCHAR(255) NULL'
        ],
        'certificacion' => ['Documento_archivo' => 'VARCHAR(255) NULL', 'Estado_revision' => "VARCHAR(30) NOT NULL DEFAULT 'Pendiente'"],
        'reserva' => ['id_mascota' => 'INT NULL', 'id_entrenador' => 'INT NULL', 'Descripcion' => 'TEXT NULL'],
        'mensaje' => ['id_destinatario' => 'INT NULL', 'Leido' => "TINYINT(1) NOT NULL DEFAULT 0"],
        'notificacion' => ['Enlace' => 'VARCHAR(255) NULL']
    ];
    foreach ($columns as $table => $fields) {
        $tableExists = $pdo->query("SHOW TABLES LIKE " . $pdo->quote($table))->fetchColumn();
        if (!$tableExists) continue;
        foreach ($fields as $name => $definition) {
            $exists = $pdo->query("SHOW COLUMNS FROM `$table` LIKE " . $pdo->quote($name))->fetch();
            if (!$exists) $pdo->exec("ALTER TABLE `$table` ADD COLUMN `$name` $definition");
        }
    }
    $pdo->exec("CREATE TABLE IF NOT EXISTS vacuna (
        id_vacuna INT AUTO_INCREMENT PRIMARY KEY, id_mascota INT NOT NULL, Nombre VARCHAR(120) NOT NULL,
        Fecha_aplicacion DATE NOT NULL, Proxima_dosis DATE NULL, Veterinaria VARCHAR(150) NULL,
        Lote VARCHAR(80) NULL, Documento_archivo VARCHAR(255) NULL, Observaciones TEXT NULL,
        Creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        CONSTRAINT fk_vacuna_mascota FOREIGN KEY (id_mascota) REFERENCES mascota(id_mascota) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    $pdo->exec("CREATE TABLE IF NOT EXISTS registro_sesion (
        id_evento INT AUTO_INCREMENT PRIMARY KEY, id_usuario INT NULL, Evento VARCHAR(30) NOT NULL,
        Fecha_hora DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, IP VARCHAR(45) NULL,
        INDEX idx_evento_usuario (id_usuario, Fecha_hora)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    return $pdo;
}
function dogvive_user_id(): int {
    return (int)($_SESSION['dogvibe_user']['id_usuario'] ?? 0);
}
function dogvive_flash(string $key, ?string $message = null): ?string {
    if ($message !== null) { $_SESSION['dogvive_flash'][$key] = $message; return null; }
    $value = $_SESSION['dogvive_flash'][$key] ?? null; unset($_SESSION['dogvive_flash'][$key]); return $value;
}
function dogvive_upload(array $file, string $folder, array $allowed = ['image/jpeg','image/png','image/webp','application/pdf']): ?string {
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) return null;
    if (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) throw new RuntimeException('No se pudo subir el archivo. Intenta nuevamente.');
    if (($file['size'] ?? 0) > 5 * 1024 * 1024) throw new RuntimeException('El archivo supera el máximo permitido de 5 MB.');
    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);
    if (!in_array($mime, $allowed, true)) throw new RuntimeException('Formato no permitido. Usa JPG, PNG, WEBP o PDF.');
    $ext = ['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp','application/pdf'=>'pdf'][$mime] ?? 'bin';
    $relative = 'uploads/' . trim($folder, '/') . '/' . bin2hex(random_bytes(16)) . '.' . $ext;
    $target = __DIR__ . '/' . $relative;
    if (!is_dir(dirname($target)) && !mkdir(dirname($target), 0755, true) && !is_dir(dirname($target))) throw new RuntimeException('No se pudo preparar la carpeta de archivos.');
    if (!move_uploaded_file($file['tmp_name'], $target)) throw new RuntimeException('No se pudo guardar el archivo subido.');
    return $relative;
}
function dogvive_log_event(?int $userId, string $event): void {
    try { $pdo = dogvive_db(); $s=$pdo->prepare('INSERT INTO registro_sesion (id_usuario, Evento, IP) VALUES (?, ?, ?)'); $s->execute([$userId ?: null, $event, substr((string)($_SERVER['REMOTE_ADDR'] ?? ''),0,45)]); } catch (Throwable $e) { error_log('DogVive session log: '.$e->getMessage()); }
}
function dogvive_notice(PDO $pdo, int $userId, string $type, string $content, ?string $link = null): void {
    $s=$pdo->prepare('INSERT INTO notificacion (Tipo, Contenido, Fecha, Hora, Estado, id_usuario, Enlace) VALUES (?, ?, CURDATE(), CURTIME(), ?, ?, ?)');
    $s->execute([$type,$content,'Pendiente',$userId,$link]);
}

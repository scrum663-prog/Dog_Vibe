<?php
declare(strict_types=1);
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/app_init.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . base_url() . '/login.php');
    exit;
}

function dogvibe11_normalize_role(string $role): string {
    $role = trim($role);
    return in_array(mb_strtolower($role), ['dueño', 'dueno', 'propietario'], true) ? 'Propietario' : $role;
}

$identificador = trim((string)($_POST['identificador'] ?? ''));
$password = (string)($_POST['password'] ?? '');
$rolSolicitado = dogvibe11_normalize_role((string)($_POST['rol'] ?? ''));
if ($identificador === '' || $password === '' || !in_array($rolSolicitado, ['Propietario', 'Entrenador'], true)) {
    $_SESSION['login_error'] = 'Completa usuario/correo, contraseña y rol.';
    header('Location: ' . base_url() . '/login.php');
    exit;
}

try {
    $pdo = dogvive_db();
    // Permite iniciar sesión con email en la base original y con usuario en DogVive 1.1.
    $column = $pdo->query("SHOW COLUMNS FROM `usuario` LIKE 'Usuario'")->fetch();
    $hasUsername = (bool)$column;
    if ($hasUsername) {
    $stmt = $pdo->prepare(
        'SELECT * FROM usuario
         WHERE Correo = :email OR Usuario = :username
         LIMIT 1'
    );

    $stmt->execute([
        'email' => $identificador,
        'username' => $identificador
    ]);
} else {
    $stmt = $pdo->prepare(
        'SELECT * FROM usuario
         WHERE Correo = :email
         LIMIT 1'
    );

    $stmt->execute([
        'email' => $identificador
    ]);
}

$account = $stmt->fetch();

    $storedRole = dogvibe11_normalize_role((string)($account['Tipo_usuario'] ?? ''));
    $storedPassword = (string)($account['Contraseña'] ?? '');
    $passwordOk = false;
    if ($account) {
        $passwordInfo = password_get_info($storedPassword);
        if (($passwordInfo['algo'] ?? null) !== null && ($passwordInfo['algo'] ?? 0) !== 0) {
            $passwordOk = password_verify($password, $storedPassword);
        } else {
            // Compatibilidad temporal con las cuentas de prueba antiguas que guardaban 12345 en texto plano.
            $passwordOk = hash_equals($storedPassword, $password);
            if ($passwordOk) {
                $newHash = password_hash($password, PASSWORD_DEFAULT);
                $upgrade = $pdo->prepare('UPDATE usuario SET `Contraseña` = :password WHERE id_usuario = :id');
                $upgrade->execute(['password' => $newHash, 'id' => $account['id_usuario']]);
            }
        }
    }

    if (!$account || !$passwordOk || $storedRole !== $rolSolicitado) {
        $_SESSION['login_error'] = 'El usuario/correo, la contraseña o el rol no coinciden.';
        header('Location: ' . base_url() . '/login.php');
        exit;
    }

    $fullName = trim((string)($account['Nombre'] ?? '') . ' ' . (string)($account['Apellido'] ?? ''));
    $profile = [
        'id_usuario' => (int)$account['id_usuario'],
        'nombre' => $fullName,
        'documento' => (string)($account['Documento'] ?? ''),
        'correo' => (string)($account['Correo'] ?? ''),
        'telefono' => (string)($account['Telefono'] ?? ''),
        'ciudad' => (string)($account['Ciudad'] ?? $account['Direccion'] ?? ''),
        'usuario' => (string)($account['Usuario'] ?? $account['Correo'] ?? ''),
        'rol' => $storedRole,
        'especialidad' => (string)($account['Especialidad'] ?? ''),
        'experiencia' => (int)($account['Experiencia'] ?? 0),
        'certificacion' => (string)($account['Registro_profesional'] ?? ''),
        'tipo_certificacion' => (string)($account['Tipo_certificacion'] ?? ''),
        'documento_profesional' => (string)($account['Documento_profesional'] ?? ''),
        'tiene_mascotas' => (string)($account['Tiene_mascotas'] ?? ''),
        'relacion_mascota' => (string)($account['Relacion_mascota'] ?? ''),
        'tipo_mascota' => (string)($account['Tipo_mascota'] ?? ''),
        'telefono_emergencia' => (string)($account['Telefono_emergencia'] ?? ''),
    ];

    session_regenerate_id(true);
    $_SESSION['dogvibe_user'] = $profile;
    $_SESSION['dogvibe_auth'] = true;
    dogvive_log_event((int)$account['id_usuario'], 'LOGIN');
    unset($_SESSION['dogvibe_account'], $_SESSION['login_error']);
    $destination = $storedRole === 'Entrenador' ? 'dashboard-entrenador.php' : 'dashboard.php';
    header('Location: ' . base_url() . '/' . $destination);
    exit;
} catch (PDOException $e) {
    error_log('DogVive1.1 login DB: ' . $e->getMessage());
    $_SESSION['login_error'] = 'No se pudo conectar con la base de datos. Verifica MySQL, la base DOG_NEXT y la configuración de conexión.';
    header('Location: ' . base_url() . '/login.php');
    exit;
} catch (Throwable $e) {
    error_log('DogVive1.1 login: ' . $e->getMessage());
    $_SESSION['login_error'] = 'Ocurrió un error al iniciar sesión. Revisa el registro de errores de PHP.';
    header('Location: ' . base_url() . '/login.php');
    exit;
}

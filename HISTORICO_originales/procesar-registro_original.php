<?php
require_once __DIR__ . '/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: registro.php');
    exit;
}

$nombre = trim($_POST['nombre'] ?? '');
$documento = trim($_POST['documento'] ?? '');
$correo = trim($_POST['correo'] ?? '');
$telefono = trim($_POST['telefono'] ?? '');
$ciudad = trim($_POST['ciudad'] ?? '');
$usuario = trim($_POST['usuario'] ?? '');
$password = (string)($_POST['password'] ?? '');
$rol = trim($_POST['rol'] ?? '');
$tiene_mascotas = trim($_POST['tiene_mascotas'] ?? '');
$relacion_mascota = trim($_POST['relacion_mascota'] ?? '');
$tipo_mascota = trim($_POST['tipo_mascota'] ?? '');
$telefono_emergencia = trim($_POST['telefono_emergencia'] ?? '');
$documento_profesional = trim($_POST['documento_profesional'] ?? '');

$errores = [];
if ($nombre === '' || strlen($nombre) < 3) $errores[] = 'El nombre completo es obligatorio.';
if (!preg_match('/^[0-9]{6,12}$/', $documento)) $errores[] = 'El documento debe tener entre 6 y 12 números.';
if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) $errores[] = 'El correo electrónico no es válido.';
if ($telefono === '') $errores[] = 'El teléfono es obligatorio.';
if ($ciudad === '') $errores[] = 'La ciudad es obligatoria.';
if ($usuario === '') $errores[] = 'El nombre de usuario es obligatorio.';
if (strlen($password) < 8) $errores[] = 'La contraseña debe tener mínimo 8 caracteres.';
if (!in_array($rol, ['Propietario', 'Entrenador'], true)) $errores[] = 'Debes seleccionar Propietario o Entrenador.';
if (!isset($_POST['terminos'])) $errores[] = 'Debes aceptar los términos de uso.';
if ($rol === 'Propietario') {
    if ($tiene_mascotas === '') $errores[] = 'El propietario debe indicar si tiene mascotas.';
    if ($relacion_mascota === '') $errores[] = 'El propietario debe indicar su relación con la mascota.';
    if ($tipo_mascota === '') $errores[] = 'El propietario debe indicar el tipo de mascota principal.';
}

if ($rol === 'Entrenador') {
    if (trim($_POST['especialidad'] ?? '') === '') $errores[] = 'El entrenador debe indicar su especialidad.';
    if (!isset($_POST['experiencia']) || $_POST['experiencia'] === '' || (int)$_POST['experiencia'] < 0) $errores[] = 'El entrenador debe indicar sus años de experiencia.';
    if (trim($_POST['certificacion'] ?? '') === '') $errores[] = 'El entrenador debe indicar su número de certificación.';
    if (trim($_POST['tipo_certificacion'] ?? '') === '') $errores[] = 'El entrenador debe indicar el tipo de certificación.';
    if ($documento_profesional === '') $errores[] = 'El entrenador debe indicar su documento profesional o número de identificación laboral.';
}

if ($errores) {
    render_header('Error de registro');
    echo '<div class="center-page"><div class="card"><h1>No se pudo completar el registro</h1><ul class="error-list">';
    foreach ($errores as $error) echo '<li>' . e($error) . '</li>';
    echo '</ul><div style="margin-top:20px">' . btn('Volver al registro', page_url('registro'), 'btn-primary full') . '</div></div></div></body></html>';
    exit;
}

// Demo local: se guarda temporalmente en sesión, sin base de datos.
$profile = [
    'nombre' => $nombre, 'documento' => $documento, 'correo' => $correo, 'telefono' => $telefono,
    'ciudad' => $ciudad, 'usuario' => $usuario, 'rol' => $rol,
];
if ($rol === 'Propietario') {
    $profile['tiene_mascotas'] = $tiene_mascotas;
    $profile['relacion_mascota'] = $relacion_mascota;
    $profile['tipo_mascota'] = $tipo_mascota;
    $profile['telefono_emergencia'] = $telefono_emergencia;
} else {
    $profile['especialidad'] = trim($_POST['especialidad']);
    $profile['experiencia'] = (int)$_POST['experiencia'];
    $profile['certificacion'] = trim($_POST['certificacion']);
    $profile['tipo_certificacion'] = trim($_POST['tipo_certificacion']);
    $profile['documento_profesional'] = $documento_profesional;
}
$_SESSION['dogvibe_account'] = [
    'usuario' => $usuario, 'correo' => $correo, 'password' => password_hash($password, PASSWORD_DEFAULT),
    'rol' => $rol, 'profile' => $profile,
];
$_SESSION['dogvibe_user'] = $profile;
$_SESSION['dogvibe_auth'] = false;
header('Location: confirmacion-registro.php');
exit;

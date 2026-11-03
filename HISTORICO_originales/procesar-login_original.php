<?php
require_once __DIR__ . '/functions.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: login.php'); exit; }
$identificador = trim($_POST['identificador'] ?? '');
$password = (string)($_POST['password'] ?? '');
$rol = trim($_POST['rol'] ?? '');
$account = $_SESSION['dogvibe_account'] ?? null;
if (!$account) { $_SESSION['login_error'] = 'No existe una cuenta registrada en esta sesión. Primero crea tu cuenta.'; header('Location: login.php'); exit; }
$okId = strcasecmp($identificador, $account['usuario']) === 0 || strcasecmp($identificador, $account['correo']) === 0;
$okPass = password_verify($password, $account['password']);
$okRol = $rol === $account['rol'];
if (!$okId || !$okPass || !$okRol) { $_SESSION['login_error'] = 'Los datos de inicio de sesión o el rol no coinciden con la cuenta registrada.'; header('Location: login.php'); exit; }
$_SESSION['dogvibe_auth'] = true; $_SESSION['dogvibe_user'] = $account['profile'];
header('Location: ' . base_url() . '/' . ($account['rol'] === 'Entrenador' ? 'dashboard-entrenador.php' : 'dashboard.php')); exit;

<?php
require_once __DIR__ . '/functions.php';
if (is_logged_in()) { header('Location: ' . base_url() . '/dashboard.php'); exit; }
$error = $_SESSION['login_error'] ?? ''; unset($_SESSION['login_error']);
render_header('Iniciar sesión');
?>
<div class="auth-shell">
    <div class="auth-visual"><div class="auth-visual-content">
        <div class="brand brand-light"><img class="logo-img logo-img-login" src="<?= e(base_url() . '/assets/images/dog-vibe-logo.jpg') ?>" alt="Logo Dog Vibe"></div>
        <h1>Tu perro merece<br>el mejor cuidado</h1>
        <p>Conecta con entrenadores certificados, gestiona sesiones y sigue el progreso de tu mascota.</p>
        <div class="stats-row"><div><strong>200+</strong><small>Entrenadores</small></div><div><strong>4.8★</strong><small>Calificación</small></div><div><strong>3.5k</strong><small>Sesiones</small></div></div>
    </div></div>
    <div class="auth-form"><div class="mobile-brand brand"><img class="logo-img logo-img-login" src="<?= e(base_url() . '/assets/images/dog-vibe-logo.jpg') ?>" alt="Logo Dog Vibe"></div>
        <div class="form-box"><h2>Bienvenido de vuelta</h2><p class="muted">Inicia sesión para acceder a las funciones de Dog Vibe.</p>
        <?php if (isset($_GET['required'])): ?><div class="notice">🔒 Primero debes registrarte o iniciar sesión para entrar a las funciones.</div><?php endif; ?>
        <?php if ($error): ?><div class="error-box"><?= e($error) ?></div><?php endif; ?>
        <form action="procesar-login.php" method="post">
            <label>Usuario o correo electrónico<input type="text" name="identificador" placeholder="Tu usuario o correo" required></label>
            <label>Contraseña<input type="password" name="password" placeholder="••••••••" required></label>
            <label>Rol<select name="rol" required><option value="">Selecciona tu rol</option><option>Propietario</option><option>Entrenador</option></select></label>
            <div class="forgot"><a href="restablecer-contrasena.php">¿Olvidaste tu contraseña?</a></div>
            <button class="btn btn-primary full" type="submit">Iniciar sesión</button>
        </form>
        <div class="divider">o</div><?php echo btn('Crear una cuenta nueva', page_url('registro'), 'btn-secondary full'); ?>
        <p class="legal">Dog Vibe maneja dos roles: Propietario y Entrenador.</p></div>
    </div>
</div></body></html>
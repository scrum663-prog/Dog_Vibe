<?php
require_once __DIR__ . '/functions.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }
$user = $_SESSION['dogvibe_user'] ?? null;
render_header('Registro completado');
?>
<div class="center-page">
    <div class="check">✓</div>
    <h1>¡Registro completado!</h1>
    <?php if ($user): ?>
        <p class="muted">Se validaron los campos básicos del perfil de <strong><?= e($user['nombre']) ?></strong>.</p>
        <div class="card role-summary">
            <div class="info-list">
                <div><span>👤 Rol</span><strong><?= e($user['rol']) ?></strong></div>
                <div><span>📧 Correo</span><strong><?= e($user['correo']) ?></strong></div>
                <div><span>📍 Ciudad</span><strong><?= e($user['ciudad']) ?></strong></div>
                <?php if ($user['rol'] === 'Entrenador'): ?>
                    <div><span>🎓 Especialidad</span><strong><?= e($user['especialidad']) ?></strong></div>
                    <div><span>⏱ Experiencia</span><strong><?= e((string)$user['experiencia']) ?> años</strong></div>
                    <div><span>📜 Certificación</span><strong><?= e($user['certificacion']) ?></strong></div><div><span>🪪 Documento profesional</span><strong><?= e($user['documento_profesional']) ?></strong></div>
                <?php else: ?>
                    <div><span>🐾 Mascotas</span><strong><?= e($user['tiene_mascotas'] ?? 'Información registrada') ?></strong></div><div><span>🐕 Tipo</span><strong><?= e($user['tipo_mascota'] ?? 'No especificado') ?></strong></div>
                <?php endif; ?>
            </div>
        </div>
    <?php else: ?>
        <p class="muted">El formulario fue procesado correctamente.</p>
    <?php endif; ?>
    <div style="display:flex;gap:10px;margin-top:20px">
        <?= btn('Ir al inicio', page_url('index'), 'btn-secondary full') ?>
        <?= btn('Iniciar sesión', page_url('login'), 'btn-primary full') ?>
    </div>
</div>
</body></html>

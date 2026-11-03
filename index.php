<?php
require_once __DIR__ . '/functions.php';
render_header('Inicio');
$logo = base_url() . '/assets/images/dog-vibe-logo.jpg';
?>

<header class="dv-home-header youth-nav">
    <a class="dv-brand" href="index.php" aria-label="Dog Vibe inicio">
        <span class="brand-logo-wrap"><img src="<?= e($logo) ?>" alt="Logo Dog Vibe"></span>
        <span>Dog <b>Vibe</b><small>TRAIN • CONNECT • GROW</small></span>
    </a>
    <nav class="dv-home-nav">
        <a href="#funciones">Funciones</a>
        <a href="#roles">Comunidad</a>
        <a href="#como-funciona">Cómo funciona</a>
        <a class="dv-nav-login" href="login.php">Iniciar sesión</a>
        <a class="dv-nav-cta" href="registro.php">Únete <span>↗</span></a>
    </nav>
</header>

<main class="dv-home youth-home">
    <section class="dv-hero youth-hero">
        <div class="youth-grid-bg"></div>
        <div class="dv-hero-glow dv-glow-one"></div>
        <div class="dv-hero-glow dv-glow-two"></div>
        <div class="hero-sticker sticker-one">🐾 + 🎓</div>
        <div class="hero-sticker sticker-two">#DogVibe</div>

        <div class="dv-hero-copy">
            <div class="dv-eyebrow youth-pill"><span>⚡</span> ENTRENAMIENTO CANINO, PERO MÁS VIBE</div>
            <h1>Tu perro.<br><span>Tu equipo.</span><br><em>Tu vibe.</em></h1>
            <p class="dv-hero-text">Conecta con entrenadores, organiza sesiones y mira cómo tu compañero de cuatro patas evoluciona. Todo desde un solo lugar.</p>
            <div class="dv-hero-actions">
                <a class="dv-btn dv-btn-primary youth-primary" href="registro.php">Crear cuenta <span>→</span></a>
                <a class="dv-btn dv-btn-ghost youth-ghost" href="#funciones"><span class="play">▶</span> Ver qué hay</a>
            </div>
            <div class="youth-social-proof">
                <div class="avatar-stack"><span>🐶</span><span>🐕</span><span>🦮</span><span>🐩</span></div>
                <div><strong>Una comunidad que ama a los perros</strong><small>Propietarios + entrenadores en un mismo espacio</small></div>
            </div>
        </div>

        <div class="dv-hero-visual youth-visual">
            <div class="hero-ring ring-one"></div><div class="hero-ring ring-two"></div>
            <div class="hero-burst">WOOF!<small>LET'S GO</small></div>
            <div class="dv-logo-card youth-logo-card">
                <div class="card-topline"><span>● ONLINE</span><b>01</b></div>
                <img src="<?= e($logo) ?>" alt="Dog Vibe - app de entrenamiento de perros">
                <div class="dv-logo-caption"><span class="status-dot"></span><div><b>Dog Vibe</b><small>Entrena • conecta • crece</small></div><span class="caption-arrow">↗</span></div>
            </div>
            <div class="dv-float-card float-trainer youth-float"><span class="float-icon">🎓</span><div><b>Coach encontrado</b><small>4.9 ★ · 8 años</small></div><span class="mini-check">✓</span></div>
            <div class="dv-float-card float-calendar youth-float"><span class="float-icon orange-icon">📅</span><div><b>Sesión agendada</b><small>Hoy · 5:00 PM</small></div></div>
            <div class="hero-mini-card mini-progress"><span>🐾</span><div><b>+75%</b><small>progreso</small></div></div>
        </div>
    </section>

    <div class="youth-marquee"><div><span>🐾 DOG VIBE</span><i>✦</i><span>ENTRENA</span><i>✦</i><span>CONECTA</span><i>✦</i><span>CRECE</span><i>✦</i><span>PERROS FELICES</span><i>✦</i><span>🐾 DOG VIBE</span><i>✦</i><span>ENTRENA</span><i>✦</i><span>CONECTA</span><i>✦</i></div></div>

    <section id="funciones" class="dv-section youth-section">
        <div class="dv-heading centered youth-heading">
            <div class="dv-section-label">TODO LO QUE NECESITAS</div>
            <h2>Hazlo fácil.<br><em>Hazlo Dog Vibe.</em></h2>
            <p>Menos vueltas, más momentos con tu perro. Estas son las herramientas que tienes a mano.</p>
        </div>
        <div class="dv-feature-grid youth-feature-grid">
            <a class="dv-feature featured youth-feature blue-card" href="<?= e(protected_url('mascotas')) ?>"><span class="feature-number">01</span><span class="feature-icon blue">🐾</span><h3>Mis mascotas</h3><p>Ten sus datos, sesiones y progreso organizados.</p><span class="feature-arrow">↗</span><span class="card-spark">✦</span></a>
            <a class="dv-feature youth-feature orange-card" href="<?= e(protected_url('entrenadores')) ?>"><span class="feature-number">02</span><span class="feature-icon orange">🎓</span><h3>Entrenadores</h3><p>Encuentra perfiles, experiencia y especialidades.</p><span class="feature-arrow">↗</span></a>
            <a class="dv-feature youth-feature dark-card" href="<?= e(protected_url('reservar')) ?>"><span class="feature-number">03</span><span class="feature-icon blue">📅</span><h3>Agenda</h3><p>Elige fecha, hora, entrenador y listo.</p><span class="feature-arrow">↗</span></a>
            <a class="dv-feature youth-feature" href="<?= e(protected_url('seguimiento')) ?>"><span class="feature-number">04</span><span class="feature-icon orange">📈</span><h3>Seguimiento</h3><p>Convierte cada pequeño avance en motivación.</p><span class="feature-arrow">↗</span></a>
            <a class="dv-feature youth-feature" href="<?= e(protected_url('mensajes')) ?>"><span class="feature-number">05</span><span class="feature-icon blue">💬</span><h3>Chat</h3><p>Habla con tu entrenador cuando lo necesites.</p><span class="feature-arrow">↗</span></a>
            <a class="dv-feature youth-feature" href="<?= e(protected_url('pqrsd')) ?>"><span class="feature-number">06</span><span class="feature-icon orange">📩</span><h3>PQRSD</h3><p>Tu opinión también hace crecer la comunidad.</p><span class="feature-arrow">↗</span></a>
        </div>
    </section>

    <section id="roles" class="dv-role-section youth-role-section">
        <div class="role-bg-shape"></div>
        <div class="dv-role-copy youth-role-copy">
            <div class="dv-section-label">TÚ ELIGES TU VIBE</div>
            <h2>Dos caminos.<br><em>Una comunidad.</em></h2>
            <p>Dog Vibe se enfoca en dos roles: propietarios y entrenadores. Cada uno tiene información y herramientas pensadas para su función.</p>
            <a class="dv-text-link" href="registro.php">Quiero entrar a Dog Vibe <span>→</span></a>
        </div>
        <div class="dv-role-cards youth-role-cards">
            <article class="dv-role-card owner youth-role-card">
                <div class="role-top"><span class="big-role-icon">🐾</span><span class="role-badge">01 · PROPIETARIO</span></div>
                <h3>Tu mejor amigo merece su propio espacio.</h3>
                <p>Organiza sus datos, busca entrenamiento y lleva su progreso contigo.</p>
                <ul><li>Perfil y datos personales</li><li>Registro de mascotas</li><li>Reservas y seguimiento</li><li>Calificaciones y PQRSD</li></ul>
                <a href="registro.php">Crear perfil <span>↗</span></a>
            </article>
            <article class="dv-role-card trainer youth-role-card">
                <div class="role-top"><span class="big-role-icon">🎓</span><span class="role-badge">02 · ENTRENADOR</span></div>
                <h3>Convierte tu experiencia en una gran comunidad.</h3>
                <p>Presenta tu perfil profesional para conectar con propietarios que buscan tus servicios.</p>
                <ul><li>Datos profesionales</li><li>Especialidad y experiencia</li><li>Certificación profesional</li><li>Ubicación y servicios</li></ul>
                <a href="registro.php">Crear perfil <span>↗</span></a>
            </article>
        </div>
    </section>

    <section id="como-funciona" class="dv-section dv-how youth-how">
        <div class="dv-heading youth-heading"><div class="dv-section-label">4 PASOS Y YA</div><h2>Así se vive <em>Dog Vibe.</em></h2><p>Una experiencia sencilla desde que creas tu cuenta hasta que ves el progreso.</p></div>
        <div class="dv-steps youth-steps">
            <div class="dv-step youth-step"><span>01</span><div><b>START</b><h3>Crea tu cuenta</h3><p>Elige Propietario o Entrenador y completa tus datos.</p></div></div>
            <div class="dv-step youth-step"><span>02</span><div><b>BUILD</b><h3>Completa tu perfil</h3><p>Agrega la información que hace útil tu perfil.</p></div></div>
            <div class="dv-step youth-step"><span>03</span><div><b>CONNECT</b><h3>Conecta y agenda</h3><p>Encuentra opciones y reserva una sesión.</p></div></div>
            <div class="dv-step youth-step"><span>04</span><div><b>GROW</b><h3>Haz seguimiento</h3><p>Mira avances y celebra cada nuevo logro.</p></div></div>
        </div>
    </section>

    <section class="dv-final-cta youth-final-cta">
        <div class="cta-orb"></div><div class="cta-paw">🐾</div>
        <div><div class="dv-section-label light">READY?</div><h2>Tu próxima aventura empieza aquí.</h2><p>Haz parte de Dog Vibe y dale a tu perro una experiencia de entrenamiento con más conexión.</p></div>
        <a class="dv-btn dv-btn-white" href="registro.php">Entrar a Dog Vibe <span>↗</span></a>
    </section>
</main>

<footer class="dv-footer youth-footer"><div class="dv-footer-brand"><img src="<?= e($logo) ?>" alt="Dog Vibe"><span>Dog <b>Vibe</b></span></div><p>App de entrenamiento de perros · Train • Connect • Grow</p><span>© <?= date('Y') ?> Dog Vibe</span></footer>
<script src="<?= e(base_url() . '/app.js') ?>"></script>
</body></html>

<?php

declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * URL base del proyecto.
 */
function base_url(): string
{
    $script = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '/');
    $base = dirname($script);

    return $base === '/' || $base === '.'
        ? ''
        : rtrim($base, '/');
}

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function url(string $path = ''): string
{
    $path = trim($path, '/');

    return $path === ''
        ? base_url() . '/index.php'
        : base_url() . '/' . $path . '.php';
}

function page_url(string $folder, string $file = ''): string
{
    $file = trim($file ?: $folder, '/');

    return base_url() . '/' . $file . '.php';
}

function is_logged_in(): bool
{
    return !empty($_SESSION['dogvibe_auth'])
        && !empty($_SESSION['dogvibe_user']);
}

function current_role(): string
{
    return $_SESSION['dogvibe_user']['rol'] ?? 'Propietario';
}

function require_login(): void
{
    if (!is_logged_in()) {
        header('Location: ' . base_url() . '/login.php?required=1');
        exit;
    }
}

/**
 * Comprueba que el usuario tenga el rol requerido.
 */
function require_role(string $role): void
{
    require_login();

    if (current_role() !== $role) {
        if (current_role() === 'Entrenador') {
            header('Location: ' . base_url() . '/dashboard-entrenador.php');
        } else {
            header('Location: ' . base_url() . '/dashboard.php');
        }

        exit;
    }
}

function protected_url(string $page): string
{
    return is_logged_in()
        ? page_url($page)
        : base_url() . '/login.php?required=1';
}

function render_header(string $title): void
{
    $active = basename($_SERVER['SCRIPT_FILENAME'] ?? '', '.php');

    echo '<!doctype html><html lang="es"><head><meta charset="UTF-8">';
    echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
    echo '<title>' . e($title) . ' | DogVibe</title>';
    echo '<link rel="preconnect" href="https://fonts.googleapis.com">';
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>';
    echo '<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">';
    echo '<link rel="stylesheet" href="' . e(base_url() . '/style.css') . '">';
    echo '</head><body>';
}

function render_sidebar(string $active = ''): void
{
    $role = current_role();

    if ($role === 'Entrenador') {
        $items = [
            ['dashboard-entrenador', 'Mi panel', '⌂'],
            ['reservas', 'Mi agenda', '📅'],
            ['entrenadores', 'Mi perfil', '🎓'],
            ['mensajes', 'Mensajes', '💬'],
            ['notificaciones', 'Notificaciones', '🔔'],
            ['seguimiento', 'Seguimiento', '📈'],
            ['perfil', 'Cuenta', '👤'],
            ['pqrsd', 'Soporte / PQRSD', '📩'],
        ];

        $roleLabel = 'Entrenador';
    } else {
        $items = [
            ['dashboard', 'Inicio', '⌂'],
            ['mascotas', 'Mis mascotas', '🐾'],
            ['entrenadores', 'Entrenadores', '🎓'],
            ['reservas', 'Reservas', '📅'],
            ['mensajes', 'Mensajes', '💬'],
            ['notificaciones', 'Notificaciones', '🔔'],
            ['solicitud-servicio', 'Solicitar servicio', '📝'],
            ['pago', 'Pago', '💳'],
            ['pqrsd', 'PQRSD', '📩'],
            ['perfil', 'Perfil', '👤'],
        ];

        $roleLabel = 'Propietario';
    }

    $displayName = $_SESSION['dogvibe_user']['nombre']
        ?? ($role === 'Entrenador' ? 'Entrenador Dog Vibe' : 'Usuario Dog Vibe');

    $initial = strtoupper(substr((string) $displayName, 0, 1));

    echo '<aside class="sidebar ' .
        ($role === 'Entrenador' ? 'trainer-sidebar' : '') . '">';

    echo '<a class="brand sidebar-brand" href="' .
        e(base_url() . '/index.php') . '">';

    echo '<img class="logo-img logo-img-sidebar" src="' .
        e(base_url() . '/assets/images/dog-vibe-logo.jpg') .
        '" alt="Logo Dog Vibe"></a>';

    echo '<div class="role-chip">' .
        ($role === 'Entrenador' ? '🎓 MODO ENTRENADOR' : '🐾 MODO PROPIETARIO') .
        '</div><nav class="nav-list">';

    foreach ($items as [$folder, $label, $icon]) {
        $class = $active === $folder ? 'nav-link active' : 'nav-link';
        $target = page_url($folder);

        if ($folder === 'dashboard-entrenador') {
            $target = base_url() . '/dashboard-entrenador.php';
        }

        echo '<a class="' . $class . '" href="' . e($target) . '">';
        echo '<span class="nav-icon">' . $icon . '</span>' . e($label);
        echo '</a>';
    }

    echo '</nav><div class="sidebar-user">';
    echo '<div class="avatar avatar-small">' . e($initial) . '</div>';
    echo '<div><strong>' . e((string) $displayName) . '</strong>';
    echo '<small>' . e($roleLabel) . '</small></div></div>';

    echo '<a class="logout" href="' .
        e(base_url() . '/logout.php') . '">↪ Cerrar sesión</a>';

    echo '</aside>';
}

function start_app(string $title, string $active): void
{
    require_login();
    render_header($title);

    echo '<div class="app-shell">';
    render_sidebar($active);
    echo '<main class="main-content">';
}

function end_app(): void
{
    echo '</main></div>';
    echo '<script src="' . e(base_url() . '/app.js') . '"></script>';
    echo '</body></html>';
}

function page_wrap_start(string $title, string $subtitle = ''): void
{
    echo '<section class="page-wrap"><div class="page-heading"><div>';
    echo '<h1>' . e($title) . '</h1>';

    if ($subtitle !== '') {
        echo '<p>' . e($subtitle) . '</p>';
    }

    echo '</div></div>';
}

function page_wrap_end(): void
{
    echo '</section>';
}

function btn(
    string $label,
    string $href = '#',
    string $class = 'btn-primary'
): string {
    return '<a class="btn ' . e($class) . '" href="' .
        e($href) . '">' . $label . '</a>';
}

/**
 * Datos de demostración de entrenadores.
 */
$TRAINERS = [
    [
        'name' => 'Carlos Mendoza',
        'specialty' => 'Obediencia Básica',
        'rating' => '4.9',
        'reviews' => '142',
        'location' => 'Chapinero, Bogotá',
        'fee' => '$80.000/sesión',
        'experience' => '8 años',
        'photo' => 'https://images.unsplash.com/photo-1672041056720-e132b18626cf?w=500&h=500&fit=crop&auto=format',
        'tags' => ['Cachorros', 'Socialización']
    ],
    [
        'name' => 'Laura Torres',
        'specialty' => 'Adiestramiento Avanzado',
        'rating' => '4.8',
        'reviews' => '98',
        'location' => 'Usaquén, Bogotá',
        'fee' => '$120.000/sesión',
        'experience' => '12 años',
        'photo' => 'https://images.unsplash.com/photo-1660849636221-9a1fc064d57a?w=500&h=500&fit=crop&auto=format',
        'tags' => ['Agility', 'Competencia']
    ],
    [
        'name' => 'Andrés Ríos',
        'specialty' => 'Terapia Conductual',
        'rating' => '4.7',
        'reviews' => '74',
        'location' => 'Suba, Bogotá',
        'fee' => '$95.000/sesión',
        'experience' => '6 años',
        'photo' => 'https://images.unsplash.com/photo-1646985560279-d6d2fe265591?w=500&h=500&fit=crop&auto=format',
        'tags' => ['Ansiedad', 'Rehabilitación']
    ],
];

/**
 * Datos de demostración de mascotas.
 */
$PETS = [
    [
        'name' => 'Max',
        'breed' => 'Golden Retriever',
        'age' => '2 años',
        'progress' => 75,
        'level' => 'Básico',
        'sessions' => 12,
        'photo' => 'https://images.unsplash.com/photo-1591160690555-5debfba289f0?w=600&h=400&fit=crop&auto=format',
        'trainer' => 'Carlos Mendoza'
    ],
    [
        'name' => 'Luna',
        'breed' => 'Border Collie',
        'age' => '4 años',
        'progress' => 90,
        'level' => 'Avanzado',
        'sessions' => 28,
        'photo' => 'https://images.unsplash.com/photo-1723874959019-8d4a214cbf6d?w=600&h=400&fit=crop&auto=format',
        'trainer' => 'Laura Torres'
    ],
];

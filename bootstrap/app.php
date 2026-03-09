<?php

// Strictly require PHP types where possible
declare(strict_types=1);

// Register a basic autoloader matching PSR-4 standards
spl_autoload_register(function ($class) {
    $prefix   = 'App\\';
    $base_dir = __DIR__ . '/../app/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

// Simple .env loader
function loadEnv($path)
{
    if (!file_exists($path)) return;
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        if (strpos($line, '=') === false) continue;
        list($name, $value) = explode('=', $line, 2);
        $_ENV[trim($name)] = trim($value);
    }
}

loadEnv(BASE_PATH . '/.env');

// ─── Secure Session Configuration ─────────────────────────────────────────────
// Must be called BEFORE session_start()
$isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (isset($_SERVER['SERVER_PORT']) && (int) $_SERVER['SERVER_PORT'] === 443);

session_set_cookie_params([
    'lifetime' => 0,           // Session cookie (expires when browser closes)
    'path'     => '/',
    'domain'   => '',
    'secure'   => $isHttps,    // Only transmit over HTTPS when available
    'httponly' => true,         // Block JavaScript access to session cookie
    'samesite' => 'Lax',        // CSRF mitigation
]);

session_start();

// Regenerate session ID periodically to prevent session fixation
// Store last regeneration time; regenerate every 30 minutes
if (!isset($_SESSION['_last_regen'])) {
    $_SESSION['_last_regen'] = time();
} elseif (time() - $_SESSION['_last_regen'] > 1800) {
    session_regenerate_id(true);
    $_SESSION['_last_regen'] = time();
}

// ─── CSRF Token ────────────────────────────────────────────────────────────────
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// ─── Global Helpers ────────────────────────────────────────────────────────────

// Escape output to prevent XSS
if (!function_exists('e')) {
    function e($string): string
    {
        return htmlspecialchars((string) $string, ENT_QUOTES, 'UTF-8');
    }
}

// Generate profile picture URL (avatar fallback or uploaded file)
if (!function_exists('get_profile_picture_url')) {
    function get_profile_picture_url($profilePicture, $firstName, $lastName): string
    {
        if (empty($profilePicture) || $profilePicture === 'default.png') {
            $name   = urlencode(trim($firstName . ' ' . $lastName));
            $colors = ['F87171', 'FBBF24', '34D399', '60A5FA', '818CF8', 'A78BFA', 'F472B6', '14B8A6'];
            $bg     = $colors[strlen($name) % count($colors)];
            return "https://ui-avatars.com/api/?name={$name}&background={$bg}&color=fff&size=256";
        }

        if (filter_var($profilePicture, FILTER_VALIDATE_URL)) {
            return $profilePicture;
        }

        return APP_URL . '/uploads/profile/' . rawurlencode($profilePicture);
    }
}

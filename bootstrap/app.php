<?php

// Strictly require PHP types where possible
declare(strict_types = 1)
;

// Register a basic autoloader matching PSR-4 standards
// so we don't have to require() every file manually.
spl_autoload_register(function ($class) {
    // Project-specific namespace prefix
    $prefix = 'App\\';

    // Base directory for the namespace prefix
    $base_dir = __DIR__ . '/../app/';

    // Does the class use the namespace prefix?
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return; // Move to the next registered autoloader
    }

    // Get the relative class name
    $relative_class = substr($class, $len);

    // Replace the namespace prefix with the base directory, replace namespace
    // separators with directory separators in the relative class name, append
    // with .php
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    // If the file exists, require it
    if (file_exists($file)) {
        require $file;
    }
});

// A manual, simple .env loader since we aren't using composer packages yet
function loadEnv($path)
{
    if (!file_exists($path))
        return;
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0)
            continue;
        list($name, $value) = explode('=', $line, 2);
        $_ENV[trim($name)] = trim($value);
    }
}

loadEnv(BASE_PATH . '/.env');

// Start the session globally here so all controllers have access to $_SESSION
session_start();

// Generate a CSRF token if one does not exist for the current session
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Global helper function to escape output and prevent Cross-Site Scripting (XSS)
if (!function_exists('e')) {
    function e($string)
    {
        return htmlspecialchars((string)$string, ENT_QUOTES, 'UTF-8');
    }
}

// Global helper function to generate or display profile picture
if (!function_exists('get_profile_picture_url')) {
    function get_profile_picture_url($profilePicture, $firstName, $lastName)
    {
        if (empty($profilePicture) || $profilePicture === 'default.png') {
            $name = urlencode(trim($firstName . ' ' . $lastName));
            // Use a persistent background color based on name length to avoid random changing per request
            $colors = ['F87171', 'FBBF24', '34D399', '60A5FA', '818CF8', 'A78BFA', 'F472B6', '14B8A6'];
            $bg = $colors[strlen($name) % count($colors)];
            return "https://ui-avatars.com/api/?name={$name}&background={$bg}&color=fff&size=256";
        }

        if (filter_var($profilePicture, FILTER_VALIDATE_URL)) {
            return $profilePicture;
        }

        return APP_URL . '/uploads/profile/' . htmlspecialchars($profilePicture);
    }
}

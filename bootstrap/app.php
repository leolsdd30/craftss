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

// Global helper for consistent category colors
if (!function_exists('get_category_classes')) {
    function get_category_classes($category)
    {
        $map = [
            'Plumbing'         => ['bg' => 'bg-blue-500', 'text' => 'text-blue-600', 'badge' => 'bg-blue-50 text-blue-700 ring-blue-600/20'],
            'Electrical'       => ['bg' => 'bg-yellow-500', 'text' => 'text-yellow-600', 'badge' => 'bg-yellow-50 text-yellow-700 ring-yellow-600/20'],
            'Carpentry'        => ['bg' => 'bg-orange-500', 'text' => 'text-orange-600', 'badge' => 'bg-orange-50 text-orange-700 ring-orange-600/20'],
            'Painting'         => ['bg' => 'bg-pink-500', 'text' => 'text-pink-600', 'badge' => 'bg-pink-50 text-pink-700 ring-pink-600/20'],
            'Roofing'          => ['bg' => 'bg-stone-500', 'text' => 'text-stone-600', 'badge' => 'bg-stone-200 text-stone-700 ring-stone-600/20'],
            'HVAC'             => ['bg' => 'bg-cyan-500', 'text' => 'text-cyan-600', 'badge' => 'bg-cyan-50 text-cyan-700 ring-cyan-600/20'],
            'Tiling'           => ['bg' => 'bg-teal-500', 'text' => 'text-teal-600', 'badge' => 'bg-teal-50 text-teal-700 ring-teal-600/20'],
            'Landscaping'      => ['bg' => 'bg-green-500', 'text' => 'text-green-600', 'badge' => 'bg-green-50 text-green-700 ring-green-600/20'],
            'General Handyman' => ['bg' => 'bg-indigo-500', 'text' => 'text-indigo-600', 'badge' => 'bg-indigo-50 text-indigo-700 ring-indigo-600/20'],
        ];
        
        return $map[$category] ?? ['bg' => 'bg-indigo-500', 'text' => 'text-indigo-600', 'badge' => 'bg-indigo-50 text-indigo-700 ring-indigo-600/20'];
    }
}

<?php

// Strictly require PHP types where possible
declare(strict_types = 1);

// Force the timezone to Algeria so live servers display the correct time
date_default_timezone_set('Africa/Algiers');

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

// ── Security: Error Reporting ──
// Hide errors from the screen in production to prevent leaking sensitive paths/credentials
// but keep them enabled internally so our custom Exception/Error handler can log them.
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
error_reporting(E_ALL);

// ── Security: Session Hardening ──
// Prevent JavaScript from accessing the session cookie (XSS protection)
// and restrict cross-site sharing (CSRF protection supplement)
session_set_cookie_params([
    'lifetime' => 86400 * 7, // 1 week
    'path' => '/',
    'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on', // Only send over HTTPS if active
    'httponly' => true,      // Prevent JS access
    'samesite' => 'Lax'      // Prevent cross-site tracking/attacks
]);

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

        if (strpos($profilePicture, '/') !== false) {
            return APP_URL . '/uploads/' . ltrim($profilePicture, '/');
        }

        return APP_URL . '/uploads/profile/' . htmlspecialchars($profilePicture);
    }
}

// Global helper for consistent category colors
if (!function_exists('get_category_classes')) {
    function get_category_classes($category)
    {
        $map = [
            'Plumbing'         => ['bg' => 'bg-blue-500', 'text' => 'text-blue-600', 'badge' => 'bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 ring-blue-600/20 dark:ring-blue-400/20', 'ring' => 'ring-blue-100 dark:ring-blue-900'],
            'Electrical'       => ['bg' => 'bg-yellow-500', 'text' => 'text-yellow-600', 'badge' => 'bg-yellow-50 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400 ring-yellow-600/20 dark:ring-yellow-400/20', 'ring' => 'ring-yellow-100 dark:ring-yellow-900'],
            'Carpentry'        => ['bg' => 'bg-orange-500', 'text' => 'text-orange-600', 'badge' => 'bg-orange-50 dark:bg-orange-900/30 text-orange-700 dark:text-orange-400 ring-orange-600/20 dark:ring-orange-400/20', 'ring' => 'ring-orange-100 dark:ring-orange-900'],
            'Painting'         => ['bg' => 'bg-pink-500', 'text' => 'text-pink-600', 'badge' => 'bg-pink-50 dark:bg-pink-900/30 text-pink-700 dark:text-pink-400 ring-pink-600/20 dark:ring-pink-400/20', 'ring' => 'ring-pink-100 dark:ring-pink-900'],
            'Roofing'          => ['bg' => 'bg-stone-500', 'text' => 'text-stone-600', 'badge' => 'bg-stone-200 dark:bg-stone-800 text-stone-700 dark:text-stone-400 ring-stone-600/20 dark:ring-stone-400/20', 'ring' => 'ring-stone-200 dark:ring-stone-700'],
            'HVAC'             => ['bg' => 'bg-cyan-500', 'text' => 'text-cyan-600', 'badge' => 'bg-cyan-50 dark:bg-cyan-900/30 text-cyan-700 dark:text-cyan-400 ring-cyan-600/20 dark:ring-cyan-400/20', 'ring' => 'ring-cyan-100 dark:ring-cyan-900'],
            'Tiling'           => ['bg' => 'bg-teal-500', 'text' => 'text-teal-600', 'badge' => 'bg-teal-50 dark:bg-teal-900/30 text-teal-700 dark:text-teal-400 ring-teal-600/20 dark:ring-teal-400/20', 'ring' => 'ring-teal-100 dark:ring-teal-900'],
            'Landscaping'      => ['bg' => 'bg-green-500', 'text' => 'text-green-600', 'badge' => 'bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-400 ring-green-600/20 dark:ring-green-400/20', 'ring' => 'ring-green-100 dark:ring-green-900'],
            'General Handyman' => ['bg' => 'bg-indigo-500', 'text' => 'text-indigo-600', 'badge' => 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-400 ring-indigo-600/20 dark:ring-indigo-400/20', 'ring' => 'ring-indigo-100 dark:ring-indigo-900'],
        ];
        
        return $map[$category] ?? ['bg' => 'bg-indigo-500', 'text' => 'text-indigo-600', 'badge' => 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-400 ring-indigo-600/20 dark:ring-indigo-400/20', 'ring' => 'ring-indigo-100 dark:ring-indigo-900'];
    }
}

// Global helper for formatting job timestamps
if (!function_exists('job_time_ago')) {
    function job_time_ago($datetime) {
        $diff = time() - strtotime($datetime);
        if      ($diff < 60)     return 'Just now';
        elseif  ($diff < 3600)   return floor($diff / 60) . 'm ago';
        elseif  ($diff < 86400)  return floor($diff / 3600) . 'h ago';
        elseif  ($diff < 604800) return floor($diff / 86400) . 'd ago';
        else                     return date('M d', strtotime($datetime));
    }
}

// Global helper for building paginated, filtered job URLs
if (!function_exists('build_job_url')) {
    function build_job_url($p, $filters) {
        $q = array_filter([
            'q'        => $filters['search']   ?? '',
            'category' => $filters['category'] ?? '',
            'wilaya'   => $filters['wilaya']   ?? '',
            'sort'     => $filters['sort']     ?? '',
            'page'     => $p > 1 ? $p : '',
        ]);
        return APP_URL . '/jobs' . (!empty($q) ? '?' . http_build_query($q) : '');
    }
}

// Global helper for timestamp formatting in message requests
if (!function_exists('req_time_ago')) {
    function req_time_ago($datetime) {
        if (!$datetime) return '';
        $diff = time() - strtotime($datetime);
        if ($diff < 60)     return 'Just now';
        if ($diff < 3600)   return floor($diff / 60) . 'm ago';
        if ($diff < 86400)  return floor($diff / 3600) . 'h ago';
        if ($diff < 604800) return floor($diff / 86400) . 'd ago';
        return date('M j', strtotime($datetime));
    }
}

// Global helper for day grouping in message conversations
if (!function_exists('format_message_date')) {
    function format_message_date($dt) {
        $diff = time() - strtotime($dt);
        if ($diff < 86400)  return 'Today';
        if ($diff < 172800) return 'Yesterday';
        if ($diff < 604800) return date('l', strtotime($dt));
        return date('M j, Y', strtotime($dt));
    }
}

// Global hook to catch native PHP Warnings & Notices and elevate them to Exceptions
if (($_ENV['APP_ENV'] ?? 'production') !== 'production') {
    set_error_handler(function ($severity, $message, $file, $line) {
        if (!(error_reporting() & $severity)) {
            return; // Exclude errors suppressed via @
        }
        throw new \ErrorException($message, 0, $severity, $file, $line);
    });
}

// Global hook intercepting any uncaught Exception, wiring it up directly to our Handler
set_exception_handler(function ($exception) {
    \App\Exceptions\Handler::handle($exception);
});


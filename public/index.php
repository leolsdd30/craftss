<?php
/**
 * FRONT CONTROLLER (index.php)
 * -------------------------------------------------------------------
 * This is the entry point for the entire application. Every HTTP request
 * is routed through this single file locally (via .htaccess) and in production.
 * This pattern allows us to centrally initialize security headers, database
 * connections, sessions, and error handling before any business logic runs.
 */

// 1. Security Headers: Hardening the app against common web vulnerabilities
header("X-Frame-Options: DENY"); // Prevents Clickjacking
header("X-XSS-Protection: 1; mode=block"); // Enforces browser XSS filtering
header("X-Content-Type-Options: nosniff"); // Prevents MIME-type sniffing
header("Referrer-Policy: strict-origin-when-cross-origin");

// 2. Define the absolute path to the root directory
define('BASE_PATH', dirname(__DIR__));

// 3. Boot the core framework requirements (Autoloader, Error Handlers, Sessions)
require BASE_PATH . '/bootstrap/app.php';

use App\Core\Router;

// 4. Dynamic Base Path Resolution
// This safely extracts the sub-directory path (like /project/public) if the app
// is not running on a top-level domain. This guarantees the router works seamlessly
// on both local XAMPP and live hosting environments.
$basePath = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
define('APP_URL', rtrim($basePath, '/'));

// 5. Instantiate our custom Router
$router = new Router();

// Load the defined web routes into the router memory
require BASE_PATH . '/routes/web.php';

// 6. Request Normalization & Dispatch
// We parse the exact path requested by the browser.
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Strip the base path from the URI so the Router only sees the actual route (e.g. /login)
if (strpos($uri, $basePath) === 0) {
    $uri = substr($uri, strlen($basePath));
}

// Ensure the URI is perfectly clean for the regex matcher
if (empty($uri) || $uri === '/' || $uri === '/index.php') {
    $uri = '/';
} else {
    // Strip '/index.php/' from the start if it exists (e.g. /index.php/login -> /login)
    if (strpos($uri, '/index.php/') === 0) {
        $uri = substr($uri, 10);
    }
}

// Allow HTML forms (which only support GET/POST) to spoof PUT/DELETE using a hidden _method input
$method = $_POST['_method'] ?? $_SERVER['REQUEST_METHOD'];

// 7. Execution: Pass the clean URI and Method to the Router
try {
    $router->dispatch($uri, $method);
} catch (\Throwable $e) {
    // If ANY exception is thrown anywhere in the app, it instantly bubbles up
    // to this global exception handler, preventing fatal white-screens.
    \App\Exceptions\Handler::handle($e);
}


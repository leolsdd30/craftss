<?php
// Security Headers
header("X-Frame-Options: DENY");
header("X-XSS-Protection: 1; mode=block");
header("X-Content-Type-Options: nosniff");
header("Referrer-Policy: strict-origin-when-cross-origin");
header("Content-Security-Policy: default-src 'self'; script-src 'self' https://cdn.tailwindcss.com 'unsafe-inline'; style-src 'self' https://fonts.googleapis.com 'unsafe-inline'; font-src 'self' https://fonts.gstatic.com; img-src 'self' https://ui-avatars.com data: blob:; connect-src 'self'; frame-ancestors 'none';");

// Define the absolute path to the root directory
define('BASE_PATH', dirname(__DIR__));

// Boot the core framework requirements
require BASE_PATH . '/bootstrap/app.php';

use App\Core\Router;

// Instantiate our custom router
$router = new Router();

// Load the defined web routes into the router
require BASE_PATH . '/routes/web.php';

// Dispatch the current request URL through the router
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// [FIX] Handling subdirectories (like /project/public/index.php)
// We want to extract only the part AFTER the script's directory.
$basePath = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
define('APP_URL', rtrim($basePath, '/'));

if (strpos($uri, $basePath) === 0) {
    $uri = substr($uri, strlen($basePath));
}

// Ensure the URI is normalized for the router
if (empty($uri) || $uri === '/' || $uri === '/index.php') {
    $uri = '/';
}
else {
    // Strip '/index.php/' from the start if it exists (e.g. /index.php/login -> /login)
    if (strpos($uri, '/index.php/') === 0) {
        $uri = substr($uri, 10);
    }
}

// Only allow known HTTP methods
$allowedMethods = ['GET', 'POST', 'DELETE', 'PUT', 'PATCH'];
if (!in_array(strtoupper($_SERVER['REQUEST_METHOD']), $allowedMethods)) {
    http_response_code(405);
    exit;
}

$method = $_POST['_method'] ?? $_SERVER['REQUEST_METHOD'];

try {
    $router->dispatch($uri, $method);
} catch (\Throwable $e) {
    // Log the error internally (can be expanded later)
    error_log($e->getMessage() . " in " . $e->getFile() . " on line " . $e->getLine());
    
    // Check if we are in a development environment where we'd want to see this
    // For now, always show the 500 page
    http_response_code(500);
    $viewPath = BASE_PATH . '/resources/views/errors/500.php';
    if (file_exists($viewPath)) {
        require $viewPath;
    } else {
        echo "500 - Internal Server Error";
    }
}

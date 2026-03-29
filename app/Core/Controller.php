<?php
namespace App\Core;

class Controller
{

    /**
     * Renders a view template and safely passes PHP variables into it.
     * 
     * @param string $view e.g. "public/home" or "auth/login"
     * @param array $data Associative array of data to pass into the view
     */
    protected function view(string $view, array $data = [])
    {
        // extract() takes an associative array and creates local variables.
        // Example: ['username' => 'Ahmed'] becomes $username = 'Ahmed';
        // This allows our view templates (like home.php) to use $username directly.
        extract($data);

        $path = BASE_PATH . "/resources/views/{$view}.php";

        if (file_exists($path)) {
            require $path;
        } else {
            throw new \Exception("View not found: " . $path);
        }
    }

    /**
     * Simple HTTP redirect helper.
     * Instantly stops script execution and tells the browser to navigate away.
     *
     * @param string $path The absolute URI to redirect to
     */
    protected function redirect(string $path)
    {
        header("Location: {$path}");
        exit(); // Crucial: prevents any remaining code below from executing
    }

    /**
     * Helper to return standard JSON responses.
     * Used primarily for all AJAX endpoints (like sending messages, marking notifications, etc.)
     */
    protected function json($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json'); // Tells the browser to parse the response as JSON
        echo json_encode($data);
        exit();
    }
}

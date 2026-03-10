<?php
namespace App\Core;

class Controller
{

    /**
     * Renders a view file and extracts data to make variables available.
     * 
     * @param string $view e.g. "public/home" or "auth/login"
     * @param array $data Data to extract into the view
     */
    protected function view(string $view, array $data = [])
    {
        // Extract array keys to variables (e.g., ['name' => 'John'] becomes $name)
        extract($data);

        $path = BASE_PATH . "/resources/views/{$view}.php";

        if (file_exists($path)) {
            require $path;
        }
        else {
            die("View not found: " . $path);
        }
    }

    /**
     * Simple redirect helper
     *
     * @param string $path The URI to redirect to
     */
    protected function redirect(string $path)
    {
        header("Location: {$path}");
        exit();
    }

    /**
     * Return JSON data, useful for AJAX/API endpoints
     */
    protected function json($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }
}

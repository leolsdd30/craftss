<?php
namespace App\Exceptions;

use Throwable;

class Handler
{
    /**
     * Handle the exception by logging it and rendering the appropriate view.
     *
     * @param Throwable $e
     */
    public static function handle(Throwable $e)
    {
        // Process internal error logging using the global Logger
        \App\Core\Logger::error($e->getMessage(), [
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]);

        // Delegate to specific renderers or generic fallback based on Exception type
        if ($e instanceof NotFoundException) {
            self::renderError(404, "Not Found");
        } else {
            self::renderError(500, "Internal Server Error");
        }
    }

    /**
     * Render the error view or fallback text.
     */
    private static function renderError(int $code, string $message)
    {
        http_response_code($code);
        
        if (defined('BASE_PATH')) {
            $viewPath = BASE_PATH . "/resources/views/errors/{$code}.php";
            if (file_exists($viewPath)) {
                require $viewPath;
                return;
            }
        }
        
        echo "{$code} - {$message}";
    }
}

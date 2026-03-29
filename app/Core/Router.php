<?php
namespace App\Core;

/**
 * CUSTOM ROUTER CLASS
 * -------------------------------------------------------------------
 * This class handles mapping HTTP URLs to their respective Controller methods.
 * It uses regular expressions (Regex) to support dynamic parameters in the URL
 * (e.g., /profile/{username}).
 */
class Router
{
    // Memory bank storing all registered routes from routes/web.php
    protected $routes = [];

    /**
     * Internal helper to register a route pattern to memory.
     */
    private function addRoute($method, $uri, $controller)
    {
        $this->routes[] = [
            'method' => $method,
            'uri' => $uri,
            'controller' => $controller
        ];
    }

    // HTTP VERB REGISTRATION METHODS
    public function get($uri, $controller) {
        $this->addRoute('GET', $uri, $controller);
    }

    public function post($uri, $controller) {
        $this->addRoute('POST', $uri, $controller);
    }

    public function delete($uri, $controller) {
        $this->addRoute('DELETE', $uri, $controller);
    }

    /**
     * core DISPATCH method.
     * Searches registered routes top-to-bottom for the first match to the given URI.
     */
    public function dispatch($uri, $method)
    {
        foreach ($this->routes as $route) {
            
            // 1. Convert our custom URL parameters like {id} or {username} into real Regex patterns
            // Example: "/profile/{username}" becomes "/profile/([^/]+)"
            $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([^/]+)', $route['uri']);
            
            // Add regex start (^) and end ($) markers so it strictly matches the entire string.
            $pattern = "@^" . $pattern . "$@D";

            // 2. Perform the Regex match against the actual requested URI
            if (preg_match($pattern, $uri, $matches) && $route['method'] === strtoupper($method)) {
                
                // $matches[0] contains the full URL match. We remove it so the array 
                // only contains the captured dynamic parameters (e.g., [ "ahmed123" ])
                array_shift($matches);

                $action = $route['controller'];

                // 3. Execution: Closure/Function style route
                if (is_callable($action)) {
                    // Passes the extracted URL parameters directly into the function
                    return call_user_func_array($action, $matches);
                }

                // 4. Execution: Controller Array style (e.g. [HomeController::class, 'index'])
                if (is_array($action)) {
                    $class = $action[0];      // e.g., "\App\Controllers\HomeController"
                    $methodName = $action[1]; // e.g., "index"

                    // Dynamically instantiate the Controller class
                    $controller = new $class();
                    
                    // Call the exact method on the Controller, passing any URL parameters as arguments
                    return call_user_func_array([$controller, $methodName], $matches);
                }
            }
        }

        // If the loop finishes and zero routes matched, throw an error
        $this->abort();
    }

    /**
     * Triggers a 404 error if no route matched.
     * This throws a specific NotFoundException, which is caught by the 
     * global Handler to render the beautiful 404.php error page.
     */
    protected function abort($code = 404)
    {
        if ($code === 404) {
            throw new \App\Exceptions\NotFoundException("Route not found executing Router");
        }
        throw new \Exception("Aborted with code {$code}", $code);
    }
}

<?php
namespace App\Core;

class Router
{
    protected $routes = [];

    private function addRoute($method, $uri, $controller)
    {
        $this->routes[] = [
            'method' => $method,
            'uri' => $uri,
            'controller' => $controller
        ];
    }

    public function get($uri, $controller)
    {
        $this->addRoute('GET', $uri, $controller);
    }

    public function post($uri, $controller)
    {
        $this->addRoute('POST', $uri, $controller);
    }

    public function delete($uri, $controller)
    {
        $this->addRoute('DELETE', $uri, $controller);
    }

    public function dispatch($uri, $method)
    {
        foreach ($this->routes as $route) {
            // Check if the route contains dynamic parameters (e.g., {username})
            $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([^/]+)', $route['uri']);
            $pattern = "@^" . $pattern . "$@D";

            if (preg_match($pattern, $uri, $matches) && $route['method'] === strtoupper($method)) {
                
                // Remove the full match from the array
                array_shift($matches);

                $action = $route['controller'];

                // Handle closure routes (e.g. $router->get('/', function() { ... }))
                if (is_callable($action)) {
                    return call_user_func_array($action, $matches);
                }

                // Handle Controller array syntax (e.g. [HomeController::class, 'index'])
                if (is_array($action)) {
                    $class = $action[0];
                    $methodName = $action[1];

                    $controller = new $class();
                    return call_user_func_array([$controller, $methodName], $matches);
                }
            }
        }

        $this->abort();
    }

    protected function abort($code = 404)
    {
        http_response_code($code);
        // If we have a view for it, display it. Otherwise raw text.
        $viewPath = BASE_PATH . "/resources/views/errors/{$code}.php";
        if (file_exists($viewPath)) {
            require $viewPath;
        }
        else {
            echo "{$code} - Route Not Found";
        }
        die();
    }
}

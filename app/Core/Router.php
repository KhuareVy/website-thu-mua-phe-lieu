<?php
namespace App\Core;

class Router
{
    private array $routes = [];

    public function get(string $path, $callback)
    {
        $this->routes['GET'][$path] = $callback;
    }

    public function post(string $path, $callback)
    {
        $this->routes['POST'][$path] = $callback;
    }

    public function resolve(Request $request)
    {
        $path = $request->getPath();
        $method = $request->getMethod();
        $callback = $this->routes[$method][$path] ?? null;

        if (!$callback) {
            http_response_code(404);
            return "404 Not Found";
        }

        // Nếu là array [Controller::class, 'method']
        if (is_array($callback)) {
            $controller = new $callback[0]();
            return call_user_func([$controller, $callback[1]], $request);
        }

        // Nếu là callback function
        if (is_callable($callback)) {
            return call_user_func($callback, $request);
        }

        throw new \Exception("Invalid route callback");
    }
}

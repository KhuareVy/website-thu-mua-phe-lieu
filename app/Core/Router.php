<?php
declare(strict_types=1);

namespace App\Core;

/**
 * URL Router with RESTful support
 */
class Router
{
    private array $routes = [];
    private array $middlewares = [];

    public function get(string $path, $handler, array $middlewares = []): void
    {
        $this->addRoute('GET', $path, $handler, $middlewares);
    }

    public function post(string $path, $handler, array $middlewares = []): void
    {
        $this->addRoute('POST', $path, $handler, $middlewares);
    }

    public function put(string $path, $handler, array $middlewares = []): void
    {
        $this->addRoute('PUT', $path, $handler, $middlewares);
    }

    public function delete(string $path, $handler, array $middlewares = []): void
    {
        $this->addRoute('DELETE', $path, $handler, $middlewares);
    }

    public function patch(string $path, $handler, array $middlewares = []): void
    {
        $this->addRoute('PATCH', $path, $handler, $middlewares);
    }

    private function addRoute(string $method, string $path, $handler, array $middlewares): void
    {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'handler' => $handler,
            'middlewares' => $middlewares,
            'pattern' => $this->convertToRegex($path),
        ];
    }

    private function convertToRegex(string $path): string
    {
        // Convert /user/{id} to /user/([^/]+)
        $pattern = preg_replace('/\{([^}]+)\}/', '([^/]+)', $path);
        // Escape forward slashes
        $pattern = str_replace('/', '\/', $pattern);
        return '/^' . $pattern . '$/';
    }

    public function resolve(Request $request): array
    {
        $method = $request->getMethod();
        $uri = $request->getUri();

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }
            if (preg_match($route['pattern'], $uri, $matches)) {
                array_shift($matches);
                return [
                    'handler' => $route['handler'],
                    'params' => $matches,
                    'middlewares' => $route['middlewares'],
                ];
            }
        }
        throw new \Exception('Route not found', 404);
    }

    public function group(string $prefix, callable $callback, array $middlewares = []): void
    {
        $originalMiddlewares = $this->middlewares;
        $this->middlewares = array_merge($this->middlewares, $middlewares);

        $originalRoutes = $this->routes;
        $this->routes = [];

        $callback($this);

        // Add prefix to all routes and merge back
        foreach ($this->routes as &$route) {
            $route['path'] = $prefix . $route['path'];
            $route['pattern'] = $this->convertToRegex($route['path']);
            $route['middlewares'] = array_merge($this->middlewares, $route['middlewares']);
        }

        $this->routes = array_merge($originalRoutes, $this->routes);
        $this->middlewares = $originalMiddlewares;
    }
}

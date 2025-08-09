<?php
namespace App\Core;

class Router {
    private $routes = [];
    private $basePath;

    public function __construct($basePath = '') {
        $this->basePath = rtrim($basePath, '/');
    }

    public function get($uri, $action) {
        $this->routes['GET'][$uri] = $action;
    }

    public function post($uri, $action) {
        $this->routes['POST'][$uri] = $action;
    }

    public function run() {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // Loại bỏ basePath nếu có
        if ($this->basePath && strpos($uri, $this->basePath) === 0) {
            $uri = substr($uri, strlen($this->basePath));
        }

        if ($uri === '') $uri = '/';

        $action = $this->routes[$method][$uri] ?? null;

        if ($action === null) {
            http_response_code(404);
            echo "404 Not Found";
            exit;
        }

        if (is_callable($action)) {
            call_user_func($action);
        } elseif (is_array($action)) {
            $controller = new $action[0]();
            $method = $action[1];
            $controller->$method();
        }
    }
}

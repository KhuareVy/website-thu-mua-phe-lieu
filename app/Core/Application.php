<?php
declare(strict_types=1);

namespace App\Core;

use App\Core\Middleware\MiddlewareInterface;
use App\Core\Middleware\RequestHandlerInterface;

/**
 * Main Application class
 * Bootstraps and runs the entire application
 */
class Application implements RequestHandlerInterface
{
    private Router $router;
    private Request $request;
    private Response $response;
    private View $view;
    private Session $session;
    private Database $database;
    private array $config;
    private array $middlewares = [];

    public function __construct(array $config = [])
    {
        $this->config = $config;
        $this->bootstrap();
    }

    /**
     * Bootstrap application services
     */
    private function bootstrap(): void
    {
        // Initialize core services
        $this->database = Database::getInstance($this->config['database'] ?? []);
        $this->session = Session::getInstance();
        $this->request = new Request();
        $this->response = new Response();
        $this->view = new View($this->config['views_path'] ?? '');
        $this->router = new Router();

        // Set error handling
        set_error_handler([$this, 'handleError']);
        set_exception_handler([$this, 'handleException']);

        // Set default timezone
        date_default_timezone_set($this->config['timezone'] ?? 'UTC');
    }

    /**
     * Add global middleware
     */
    public function addMiddleware(MiddlewareInterface $middleware): void
    {
        $this->middlewares[] = $middleware;
    }

    /**
     * Get router instance
     */
    public function getRouter(): Router
    {
        return $this->router;
    }

    /**
     * Get database instance
     */
    public function getDatabase(): Database
    {
        return $this->database;
    }

    /**
     * Run the application
     */
    public function run(): void
    {
        try {
            $response = $this->processMiddlewares($this->request);
            $response->send();
        } catch (\Throwable $e) {
            $this->handleException($e);
        }
    }

    /**
     * Process middlewares chain
     */
    private function processMiddlewares(Request $request): Response
    {
        $middlewares = $this->middlewares;
        
        $handler = function($request) use (&$middlewares, &$handler) {
            if (empty($middlewares)) {
                return $this->handle($request);
            }
            
            $middleware = array_shift($middlewares);
            return $middleware->process($request, new class($handler) implements RequestHandlerInterface {
                private $next;
                
                public function __construct(callable $next) {
                    $this->next = $next;
                }
                
                public function handle(Request $request): Response {
                    return ($this->next)($request);
                }
            });
        };

        return $handler($request);
    }

    /**
     * Handle request (PSR-15)
     */
    public function handle(Request $request): Response
    {
        try {
            $route = $this->router->resolve($request);
            
            // Process route middlewares
            if (!empty($route['middlewares'])) {
                foreach ($route['middlewares'] as $middlewareClass) {
                    $middleware = new $middlewareClass();
                    $response = $middleware->process($request, $this);
                    
                    if ($response->getStatusCode() !== 200) {
                        return $response;
                    }
                }
            }

            return $this->callController($route, $request);
        } catch (\Throwable $e) {
            return $this->handleRouteException($e);
        }
    }

    /**
     * Call controller method
     */
    private function callController(array $route, Request $request): Response
    {
        $handler = $route['handler'];
        $params = $route['params'] ?? [];

        if (is_string($handler)) {
            // Handle "Controller@method" format
            if (strpos($handler, '@') !== false) {
                [$controllerClass, $method] = explode('@', $handler);
            } else {
                $controllerClass = $handler;
                $method = 'index';
            }

            // Add namespace if not present
            if (strpos($controllerClass, '\\') === false) {
                $controllerClass = 'App\\Controllers\\' . $controllerClass; // Sửa lại Controllers (có "s")
            }

            if (!class_exists($controllerClass)) {
                throw new \Exception("Controller not found: {$controllerClass}", 404);
            }

            $controller = new $controllerClass($request, $this->response, $this->view, $this->session);

            if (!method_exists($controller, $method)) {
                throw new \Exception("Method not found: {$controllerClass}::{$method}", 404);
            }

            // SỬA DÒNG NÀY: truyền $request vào đầu tiên
            return $controller->$method($request, ...$params);
        }

        if (is_callable($handler)) {
            // Handle closure routes
            return $handler($request, $this->response, ...$params);
        }

        throw new \Exception("Invalid route handler", 500);
    }

    /**
     * Handle route exceptions
     */
    private function handleRouteException(\Throwable $e): Response
    {
        $statusCode = $e->getCode() ?: 500;
        
        if ($this->request->isAjax()) {
            return $this->response->json([
                'error' => $e->getMessage(),
                'code' => $statusCode
            ], $statusCode);
        }

        // Try to render error page
        try {
            $html = $this->view->render("errors/{$statusCode}", [
                'message' => $e->getMessage(),
                'code' => $statusCode
            ]);
            return $this->response->html($html, $statusCode);
        } catch (\Exception $viewException) {
            // Fallback to basic error page
            $html = "
            <!DOCTYPE html>
            <html>
            <head><title>Error {$statusCode}</title></head>
            <body>
                <h1>Error {$statusCode}</h1>
                <p>{$e->getMessage()}</p>
            </body>
            </html>";
            
            return $this->response->html($html, $statusCode);
        }
    }

    /**
     * Handle PHP errors
     */
    public function handleError(int $severity, string $message, string $file, int $line): void
    {
        if (!(error_reporting() & $severity)) {
            return;
        }

        throw new \ErrorException($message, 0, $severity, $file, $line);
    }

    /**
     * Handle uncaught exceptions
     */
    public function handleException(\Throwable $e): void
    {
        $response = $this->handleRouteException($e);
        $response->send();
        
        // Log error in production
        if (($this->config['environment'] ?? null) === 'production') {
            error_log(sprintf(
                "[%s] %s: %s in %s:%d\n",
                date('Y-m-d H:i:s'),
                get_class($e),
                $e->getMessage(),
                $e->getFile(),
                $e->getLine()
            ));
        }
    }
}

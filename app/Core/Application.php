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
    /** @var array<string,mixed> */
    private array $config;
    /** @var array<MiddlewareInterface|class-string<MiddlewareInterface>> */
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
        $this->session  = Session::getInstance();
        $this->request  = new Request();
        $this->response = new Response();
        $this->view     = new View($this->config['views_path'] ?? '');
        $this->router   = new Router();

        // Set error handling
        set_error_handler([$this, 'handleError']);
        set_exception_handler([$this, 'handleException']);

        // Set default timezone
        date_default_timezone_set($this->config['timezone'] ?? 'UTC');
    }

    /**
     * Add global middleware
     *
     * @param MiddlewareInterface|class-string<MiddlewareInterface> $middleware
     */
    public function addMiddleware(MiddlewareInterface|string $middleware): void
    {
        $this->middlewares[] = $middleware;
    }

    public function getRouter(): Router
    {
        return $this->router;
    }

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
            // Build global middleware pipeline; final is $this->handle()
            $response = $this->dispatchPipeline(
                $this->request,
                $this->normalizeMiddlewares($this->middlewares),
                // Final handler for global pipeline is "the application" itself
                fn(Request $req): Response => $this->handle($req)
            );
            $response->send();
        } catch (\Throwable $e) {
            $this->handleException($e);
        }
    }

    /**
     * PSR-15 handle(): resolve route, then run route-level middleware pipeline,
     * whose final handler invokes the controller.
     */
    public function handle(Request $request): Response
    {
        try {
            $route = $this->router->resolve($request);

            /** @var array<int, MiddlewareInterface|class-string<MiddlewareInterface>> $routeMiddlewares */
            $routeMiddlewares = $route['middlewares'] ?? [];

            // Build route pipeline; final handler calls controller
            return $this->dispatchPipeline(
                $request,
                $this->normalizeMiddlewares($routeMiddlewares),
                fn(Request $req): Response => $this->callController($route, $req)
            );
        } catch (\Throwable $e) {
            return $this->handleRouteException($e);
        }
    }

    /**
     * Build & run a middleware pipeline:
     * [mw1 -> mw2 -> ... -> finalHandler]
     *
     * @param array<int, MiddlewareInterface> $middlewares
     * @param callable(Request):Response $finalHandler
     */
    private function dispatchPipeline(
        Request $request,
        array $middlewares,
        callable $finalHandler
    ): Response {
        // Wrap final handler to match RequestHandlerInterface
        $handler = new class($finalHandler) implements RequestHandlerInterface {
            /** @var callable(Request):Response */
            private $final;
            public function __construct(callable $final) { $this->final = $final; }
            public function handle(Request $request): Response
            {
                return ($this->final)($request);
            }
        };

        // Fold middlewares from last to first
        for ($i = count($middlewares) - 1; $i >= 0; $i--) {
            $current = $middlewares[$i];
            $next = $handler;

            $handler = new class($current, $next) implements RequestHandlerInterface {
                private MiddlewareInterface $mw;
                private RequestHandlerInterface $next;
                public function __construct(MiddlewareInterface $mw, RequestHandlerInterface $next)
                {
                    $this->mw = $mw;
                    $this->next = $next;
                }
                public function handle(Request $request): Response
                {
                    // Delegate to middleware->process($request, $next)
                    return $this->mw->process($request, $this->next);
                }
            };
        }

        // Kick off the chain
        return $handler->handle($request);
    }

    /**
     * Ensure all middleware entries are instances (instantiate class-strings).
     *
     * @param array<int, MiddlewareInterface|class-string<MiddlewareInterface>> $list
     * @return array<int, MiddlewareInterface>
     */
    private function normalizeMiddlewares(array $list): array
    {
        $out = [];
        foreach ($list as $mw) {
            if (is_string($mw)) {
                // Instantiate lazily declared middleware classes
                /** @var class-string<MiddlewareInterface> $mw */
                $out[] = new $mw();
            } else {
                $out[] = $mw;
            }
        }
        return $out;
    }

    /**
     * Call controller method
     * Supports "Controller@method" or "Controller" (default method "index")
     */
    private function callController(array $route, Request $request): Response
    {
        $handler = $route['handler'];
        $params  = $route['params'] ?? [];

        if (is_string($handler)) {
            // "Controller@method"
            if (strpos($handler, '@') !== false) {
                [$controllerClass, $method] = explode('@', $handler, 2);
            } else {
                $controllerClass = $handler;
                $method = 'index';
            }

            // Add namespace if not present
            if (strpos($controllerClass, '\\') === false) {
                $controllerClass = 'App\\Controllers\\' . $controllerClass;
            }

            if (!class_exists($controllerClass)) {
                throw new \Exception("Controller not found: {$controllerClass}", 404);
            }

            $controller = new $controllerClass($request, $this->response, $this->view, $this->session);

            if (!method_exists($controller, $method)) {
                throw new \Exception("Method not found: {$controllerClass}::{$method}", 404);
            }

            // Call with $request first for consistency
            return $controller->$method($request, ...$params);
        }

        if (is_callable($handler)) {
            // Closure route: (Request $req, Response $res, ...$params)
            return $handler($request, $this->response, ...$params);
        }

        throw new \Exception("Invalid route handler", 500);
    }

    /**
     * Handle route exceptions
     */
    private function handleRouteException(\Throwable $e): Response
    {
        $statusCode = (int)($e->getCode() ?: 500);

        if ($this->request->isAjax()) {
            return $this->response->json([
                'error' => $e->getMessage(),
                'code'  => $statusCode
            ], $statusCode);
        }

        // Try to render error page
        try {
            $html = $this->view->render("errors/{$statusCode}", [
                'message' => $e->getMessage(),
                'code'    => $statusCode
            ]);
            return $this->response->html($html, $statusCode);
        } catch (\Exception $viewException) {
            // Fallback HTML
            $html = "<!DOCTYPE html>
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

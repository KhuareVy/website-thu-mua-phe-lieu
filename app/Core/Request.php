<?php
namespace App\Core;

class Request
{
    private array $body = [];
    private array $files = [];
    private array $headers = [];
    private array $routeParams = [];

    public function __construct()
    {
        $this->body = $this->parseBody();
        $this->files = $_FILES;
        $this->headers = $this->parseHeaders();
    }

    public function getMethod(): string
    {
        return strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
    }

    public function getPath(): string
    {
        $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
        $scriptName = dirname($_SERVER['SCRIPT_NAME'] ?? '');
        
        if ($scriptName !== '/' && strpos($uri, $scriptName) === 0) {
            $uri = substr($uri, strlen($scriptName));
        }
        
        $path = rtrim($uri, '/');
        return $path === '' ? '/' : $path;
    }

    public function getUri(): string
    {
        return $_SERVER['REQUEST_URI'] ?? '/';
    }

    public function getUrl(): string
    {
        $scheme = $this->isSecure() ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        return "$scheme://$host" . $this->getUri();
    }

    public function isSecure(): bool
    {
        return isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
    }

    public function getBody(): array
    {
        return $this->body;
    }

    private function parseBody(): array
    {
        $body = [];
        $method = $this->getMethod();
        
        if ($method === 'GET') {
            $body = $_GET;
        } elseif ($method === 'POST') {
            $body = $_POST;
        } else {
            // Handle PUT, PATCH, DELETE
            $input = file_get_contents('php://input');
            $contentType = $this->getHeader('Content-Type', '');
            
            if (strpos($contentType, 'application/json') !== false) {
                $body = json_decode($input, true) ?? [];
            } else {
                parse_str($input, $body);
            }
        }
        
        // Sanitize input
        return $this->sanitizeInput($body);
    }

    private function sanitizeInput(array $data): array
    {
        $sanitized = [];
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $sanitized[$key] = $this->sanitizeInput($value);
            } else {
                $sanitized[$key] = htmlspecialchars(strip_tags($value), ENT_QUOTES, 'UTF-8');
            }
        }
        return $sanitized;
    }

    private function parseHeaders(): array
    {
        $headers = [];
        foreach ($_SERVER as $key => $value) {
            if (strpos($key, 'HTTP_') === 0) {
                $header = str_replace('_', '-', substr($key, 5));
                $headers[ucwords(strtolower($header), '-')] = $value;
            }
        }
        return $headers;
    }

    public function get(?string $key = null, $default = null)
    {
        if ($key === null) {
            return $this->body;
        }
        return $this->body[$key] ?? $default;
    }

    public function post(?string $key = null, $default = null)
    {
        if ($this->getMethod() !== 'POST') {
            return $default;
        }
        
        if ($key === null) {
            return $this->body;
        }
        return $this->body[$key] ?? $default;
    }

    public function has(string $key): bool
    {
        return isset($this->body[$key]);
    }

    public function filled(string $key): bool
    {
        return isset($this->body[$key]) && !empty($this->body[$key]);
    }

    public function only(array $keys): array
    {
        return array_intersect_key($this->body, array_flip($keys));
    }

    public function except(array $keys): array
    {
        return array_diff_key($this->body, array_flip($keys));
    }

    public function file(string $key): ?array
    {
        return $this->files[$key] ?? null;
    }

    public function hasFile(string $key): bool
    {
        return isset($this->files[$key]) && $this->files[$key]['error'] === UPLOAD_ERR_OK;
    }

    public function getHeader(string $name, $default = null): mixed
    {
        return $this->headers[$name] ?? $default;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function isAjax(): bool
    {
        return $this->getHeader('X-Requested-With') === 'XMLHttpRequest';
    }

    public function isJson(): bool
    {
        return strpos($this->getHeader('Content-Type', ''), 'application/json') !== false;
    }

    public function expectsJson(): bool
    {
        return $this->isAjax() || $this->isJson() || 
               strpos($this->getHeader('Accept', ''), 'application/json') !== false;
    }

    public function ip(): string
    {
        return $_SERVER['HTTP_X_FORWARDED_FOR'] ?? 
               $_SERVER['HTTP_CLIENT_IP'] ?? 
               $_SERVER['REMOTE_ADDR'] ?? 
               '0.0.0.0';
    }

    public function userAgent(): string
    {
        return $_SERVER['HTTP_USER_AGENT'] ?? '';
    }

    public function setRouteParams(array $params): void
    {
        $this->routeParams = $params;
    }

    public function route(?string $key = null, $default = null)
    {
        if ($key === null) {
            return $this->routeParams;
        }
        return $this->routeParams[$key] ?? $default;
    }

    // Method checks
    public function isPost(): bool { return $this->getMethod() === 'POST'; }
    public function isGet(): bool { return $this->getMethod() === 'GET'; }
    public function isPut(): bool { return $this->getMethod() === 'PUT'; }
    public function isPatch(): bool { return $this->getMethod() === 'PATCH'; }
    public function isDelete(): bool { return $this->getMethod() === 'DELETE'; }
}
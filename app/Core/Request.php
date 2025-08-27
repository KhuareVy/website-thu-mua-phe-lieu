<?php
declare(strict_types=1);

namespace App\Core;

/**
 * HTTP Request handler (PSR-7 compatible)
 */
class Request
{
    private string $method;
    private string $uri;
    private array $headers;
    private array $queryParams;
    private array $bodyParams;
    private array $files;
    private array $cookies;
    private array $serverParams;

    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $this->uri = $this->getRequestUri();
        $this->headers = $this->getAllHeaders();
        $this->queryParams = $_GET;
    $this->bodyParams = $this->parseBodyParams();
        $this->files = $_FILES;
        $this->cookies = $_COOKIE;
        $this->serverParams = $_SERVER;
    }

    private function getRequestUri(): string
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        if (($pos = strpos($uri, '?')) !== false) {
            $uri = substr($uri, 0, $pos);
        }
        return $uri;
    }

    private function getAllHeaders(): array
    {
        if (function_exists('getallheaders')) {
            return getallheaders();
        }
        $headers = [];
        foreach ($_SERVER as $key => $value) {
            if (strpos($key, 'HTTP_') === 0) {
                $headerName = str_replace('_', '-', substr($key, 5));
                $headers[$headerName] = $value;
            }
        }
        return $headers;
    }

    private function parseBodyParams(): array
    {
        if ($this->method === 'GET') {
            return [];
        }
        $contentType = $this->getHeader('Content-Type');
        if (strpos($contentType, 'application/json') !== false) {
            $json = file_get_contents('php://input');
            return json_decode($json, true) ?? [];
        }
        return $_POST;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function getHeader(string $name): string
    {
        return $this->headers[$name] ?? '';
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getQueryParam(string $name, $default = null)
    {
        return $this->queryParams[$name] ?? $default;
    }

    public function getQueryParams(): array
    {
        return $this->queryParams;
    }

    public function getBodyParam(string $name, $default = null)
    {
        return $this->bodyParams[$name] ?? $default;
    }

    public function getBodyParams(): array
    {
        return $this->bodyParams;
    }

    public function getFile(string $name): ?array
    {
        return $this->files[$name] ?? null;
    }

    public function getFiles(): array
    {
        return $this->files;
    }

    public function getCookie(string $name, $default = null)
    {
        return $this->cookies[$name] ?? $default;
    }

    public function getCookies(): array
    {
        return $this->cookies;
    }

    public function getServerParam(string $name, $default = null)
    {
        return $this->serverParams[$name] ?? $default;
    }

    public function isPost(): bool
    {
        return $this->method === 'POST';
    }

    public function isGet(): bool
    {
        return $this->method === 'GET';
    }

    public function isAjax(): bool
    {
        return strtolower($this->getHeader('X-Requested-With')) === 'xmlhttprequest';
    }

    public function isSecure(): bool
    {
        return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            || $_SERVER['SERVER_PORT'] == 443;
    }
}
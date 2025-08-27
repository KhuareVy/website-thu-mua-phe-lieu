<?php
declare(strict_types=1);
namespace App\Core;

/**
 * HTTP Response handler (PSR-7 compatible style)
 */
class Response
{
    private int $statusCode = 200;
    private array $headers = [];
    private string $body = '';
    private array $statusTexts = [
        200 => 'OK',
        201 => 'Created',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        500 => 'Internal Server Error',
    ];

    public function setStatusCode(int $code): self
    {
        $this->statusCode = $code;
        return $this;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function setHeader(string $name, string $value): self
    {
        $this->headers[$name] = $value;
        return $this;
    }

    public function getHeader(string $name): string
    {
        return $this->headers[$name] ?? '';
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function setBody(string $body): self
    {
        $this->body = $body;
        return $this;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function json(array $data, int $statusCode = 200): self
    {
        $this->setStatusCode($statusCode)
             ->setHeader('Content-Type', 'application/json; charset=utf-8')
             ->setBody(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        return $this;
    }

    public function redirect(string $url, int $statusCode = 302): self
    {
        $this->setStatusCode($statusCode)
             ->setHeader('Location', $url);
        return $this;
    }

    public function html(string $html, int $statusCode = 200): self
    {
        $this->setStatusCode($statusCode)
             ->setHeader('Content-Type', 'text/html; charset=utf-8')
             ->setBody($html);
        return $this;
    }

    public function send(): void
    {
        // Send status code
        $statusText = $this->statusTexts[$this->statusCode] ?? 'Unknown';
        header("HTTP/1.1 {$this->statusCode} {$statusText}");
        // Send headers
        foreach ($this->headers as $name => $value) {
            header("{$name}: {$value}");
        }
        // Send body
        echo $this->body;
    }

    public function setCookie(
        string $name,
        string $value,
        int $expire = 0,
        string $path = '/',
        string $domain = '',
        bool $secure = false,
        bool $httponly = true
    ): self {
        setcookie($name, $value, $expire, $path, $domain, $secure, $httponly);
        return $this;
    }
}

<?php
namespace App\Core;

class Response
{
    public function setContent(string $content, int $statusCode = 200)
    {
        http_response_code($statusCode);
        echo $content;
        exit;
    }

    public function json(array $data, int $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }

    public function redirect(string $url)
    {
        header("Location: $url");
        exit;
    }

    public function setHeader(string $name, string $value)
    {
        header("$name: $value");
    }
}

<?php
namespace App\Core;

class Request
{
    /**
     * Lấy HTTP method
     */
    public function getMethod(): string
    {
        return strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
    }

    /**
     * Lấy path hiện tại, đã loại bỏ subfolder và query string
     */
    public function getPath(): string
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        $scriptName = $_SERVER['SCRIPT_NAME'] ?? ''; // ví dụ /website_thu_mua_phe_lieu/public/index.php

        // Tính base path
        $basePath = str_replace('index.php', '', $scriptName);

        // Loại bỏ base path
        if (strpos($uri, $basePath) === 0) {
            $uri = substr($uri, strlen($basePath));
        }

        // Loại bỏ query string
        $position = strpos($uri, '?');
        if ($position !== false) {
            $uri = substr($uri, 0, $position);
        }

        // Chuẩn hóa: bỏ trailing slash
        $path = rtrim($uri, '/');

        return $path === '' ? '/' : $path;
    }

    /**
     * Lấy toàn bộ dữ liệu GET/POST
     */
    public function getBody(): array
    {
        $body = [];
        if ($this->getMethod() === 'GET') {
            foreach ($_GET as $key => $value) {
                $body[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
        if ($this->getMethod() === 'POST') {
            foreach ($_POST as $key => $value) {
                $body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
        return $body;
    }

    /**
     * Lấy một giá trị cụ thể theo key
     */
    public function get(string $key, $default = null)
    {
        return $this->getBody()[$key] ?? $default;
    }
}

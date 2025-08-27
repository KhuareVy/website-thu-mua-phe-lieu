<?php

declare(strict_types=1);

namespace App\Core;

/**
 * View renderer with template engine features
 */
class View
{
    private string $viewsPath;
    private array $data = [];
    private string $layout = '';

    public function __construct(string $viewsPath = '')
    {
        $this->viewsPath = $viewsPath ?: __DIR__ . '/../../Views/';
    }

    /**
     * Set global data for all views
     */
    public function share(array $data): void
    {
        $this->data = array_merge($this->data, $data);
    }

    /**
     * Set layout template
     */
    public function setLayout(string $layout): void
    {
        $this->layout = $layout;
    }

    /**
     * Render a view
     */
    public function render(string $template, array $data = []): string
    {
        $data = array_merge($this->data, $data);
        $content = $this->renderTemplate($template, $data);

        if (!empty($this->layout)) {
            $data['content'] = $content;
            return $this->renderTemplate($this->layout, $data);
        }

        return $content;
    }

    /**
     * Render template file
     */
    private function renderTemplate(string $template, array $data): string
    {
        $templatePath = $this->viewsPath . $template . '.php';

        if (!file_exists($templatePath)) {
            throw new \Exception("View template not found: {$templatePath}");
        }

        // Extract variables for use in template
        extract($data);

        // Start output buffering
        ob_start();
        
        try {
            include $templatePath;
        } catch (\Throwable $e) {
            ob_end_clean();
            throw $e;
        }

        return ob_get_clean();
    }

    /**
     * Escape HTML output
     */
    public function escape(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    /**
     * Include partial view
     */
    public function partial(string $template, array $data = []): string
    {
        return $this->renderTemplate("partials/{$template}", $data);
    }

    /**
     * Generate URL
     */
    public function url(string $path = ''): string
    {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'];
        
        return $protocol . '://' . $host . '/' . ltrim($path, '/');
    }

    /**
     * Generate asset URL
     */
    public function asset(string $path): string
    {
        return $this->url('assets/' . ltrim($path, '/'));
    }

    /**
     * CSRF token for forms
     */
    public function csrfToken(): string
    {
        return Session::getInstance()->getCsrfToken();
    }
}
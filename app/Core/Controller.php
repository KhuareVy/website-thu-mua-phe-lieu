<?php
declare(strict_types=1);

namespace App\Core;

/**
 * Base Controller class
 * All controllers should extend this class
 */
abstract class Controller
{
    protected Request $request;
    protected Response $response;
    protected View $view;
    protected Session $session;

    public function __construct(
        Request $request,
        Response $response,
        View $view,
        Session $session
    ) {
        $this->request = $request;
        $this->response = $response;
        $this->view = $view;
        $this->session = $session;
    }

    /**
     * Render a view with data
     */
    protected function render(string $template, array $data = []): Response
    {
        $html = $this->view->render($template, $data);
        return $this->response->html($html);
    }

    /**
     * Return JSON response
     */
    protected function json(array $data, int $statusCode = 200): Response
    {
        return $this->response->json($data, $statusCode);
    }

    /**
     * Redirect to another URL
     */
    protected function redirect(string $url, int $statusCode = 302): Response
    {
        return $this->response->redirect($url, $statusCode);
    }

    /**
     * Get request parameter
     */
    protected function input(string $key, $default = null)
    {
        return $this->request->getBodyParam($key) ?? 
               $this->request->getQueryParam($key, $default);
    }

    /**
     * Validate request data
     */
    protected function validate(array $rules): array
    {
        $data = [];
        $errors = [];

        foreach ($rules as $field => $rule) {
            $value = $this->input($field);
            if (strpos($rule, 'required') !== false && empty($value)) {
                $errors[$field] = "The {$field} field is required.";
                continue;
            }
            if (strpos($rule, 'email') !== false && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                $errors[$field] = "The {$field} must be a valid email address.";
                continue;
            }
            if (preg_match('/min:(\d+)/', $rule, $matches)) {
                $min = (int) $matches[1];
                if (strlen((string)$value) < $min) {
                    $errors[$field] = "The {$field} must be at least {$min} characters.";
                    continue;
                }
            }
            if (preg_match('/max:(\d+)/', $rule, $matches)) {
                $max = (int) $matches[1];
                if (strlen((string)$value) > $max) {
                    $errors[$field] = "The {$field} may not be greater than {$max} characters.";
                    continue;
                }
            }
            $data[$field] = $value;
        }
        if (!empty($errors)) {
            throw new \InvalidArgumentException(json_encode($errors));
        }
        return $data;
    }
}

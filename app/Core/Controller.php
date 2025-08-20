<?php
namespace App\Core;

class Controller
{
    protected Response $response;

    public function __construct()
    {
        $this->response = new Response();
    }

    protected function view(string $view, array $data = [])
    {
        extract($data);
        $contentView = dirname(__DIR__) . "/Views/$view.php";
        require dirname(__DIR__) . "/Views/layouts/default/main.php";
    }

    protected function redirect(string $url)
    {
        $this->response->redirect($url);
    }

    protected function json(array $data)
    {
        $this->response->json($data);
    }
}

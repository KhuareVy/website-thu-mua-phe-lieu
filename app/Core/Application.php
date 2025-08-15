<?php
namespace App\Core;

class Application
{
    public Router $router;
    public array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
        $this->router = new Router();
        date_default_timezone_set($config['app']['timezone'] ?? 'Asia/Ho_Chi_Minh');
    }

    public function run()
    {
        $request = new Request();
        echo $this->router->resolve($request);
    }
}

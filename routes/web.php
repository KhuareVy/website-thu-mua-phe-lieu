
<?php

use App\Controllers\AuthController;
use App\Controllers\HomeController;

use App\Core\Middleware\AuthMiddleware;

$router = $app->getRouter();

// Public routes
$router->get('/', HomeController::class . '@home');

$router->get('/login', AuthController::class . '@showLogin');
$router->post('/login', AuthController::class . '@login');
$router->get('/register', AuthController::class . '@showRegister');
$router->post('/register', AuthController::class . '@register');

$router->get('/logout', AuthController::class . '@logout');
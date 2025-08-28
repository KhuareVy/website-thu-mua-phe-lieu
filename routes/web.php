<?php

use App\Controllers\AuthController;
use App\Controllers\HomeController;
use App\Controllers\AdminController;
use App\Core\Middleware\AuthMiddleware;
use App\Core\Middleware\RoleMiddleware;

$router = $app->getRouter();

// Public routes
$router->get('/', HomeController::class . '@home');

$router->get('/login', AuthController::class . '@showLogin');
$router->post('/login', AuthController::class . '@login');
$router->get('/register', AuthController::class . '@showRegister');
$router->post('/register', AuthController::class . '@register');

$router->get('/logout', AuthController::class . '@logout');

// Admin routes
$router->get('/dashboard', AdminController::class . '@dashboard', [
    new AuthMiddleware(),
    new RoleMiddleware(['admin'])
]);

$router->get('/403', function() {
    return (new \App\Core\Response())->html('<h1>403 Forbidden</h1><p>Bạn không có quyền truy cập trang này.</p>', 403);
});


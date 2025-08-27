
<?php

use App\Controllers\AuthController;
use App\Controllers\HomeController;
use App\Controllers\AdminController;
use App\Core\Middleware\AuthMiddleware;

$router = $app->getRouter();

// Public routes
$router->get('/', [HomeController::class, 'home']);
$router->get('/register', [AuthController::class, 'showRegister']);
$router->post('/register', [AuthController::class, 'register']);
$router->get('/login', [AuthController::class, 'showLogin']);
$router->post('/login', [AuthController::class, 'login']);

// Protected routes (require authentication)
$router->group('', function($router) {
	$router->get('/dashboard', [AdminController::class, 'showAdmin']);
	$router->get('/logout', [AuthController::class, 'logout']);
}, [AuthMiddleware::class]);

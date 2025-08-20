<?php
use App\Controllers\AuthController;
use App\Controllers\HomeController;
use App\Controllers\AdminController;


$app->router->get('/', [HomeController::class, 'index']);

$app->router->get('/register', [AuthController::class, 'showRegister']);
$app->router->post('/register', [AuthController::class, 'register']);

$app->router->get('/login', [AuthController::class, 'showLogin']);
$app->router->post('/login', [AuthController::class, 'login']);

$app->router->get('/login', [AuthController::class, 'showLogin']);
$app->router->post('/login', [AuthController::class, 'login']);

$app->router->get('/dashboard', [AdminController::class, 'showAdmin']);


$app->router->get('/logout', [AuthController::class, 'logout']);

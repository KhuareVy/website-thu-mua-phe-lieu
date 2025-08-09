<?php
// routes/web.php

use App\Controllers\HomeController;
use App\Controllers\AuthController;

// $router đã được khởi tạo và truyền vào từ index.php
// Ví dụ:

// Trang chủ
$router->get('/', [HomeController::class, 'index']);

// Đăng nhập (nếu bạn thêm AuthController)
$router->get('/login', [AuthController::class, 'showLoginForm']);
$router->post('/login', [AuthController::class, 'login']);

// Đăng ký
$router->get('/register', [AuthController::class, 'showRegisterForm']);
$router->post('/register', [AuthController::class, 'register']);

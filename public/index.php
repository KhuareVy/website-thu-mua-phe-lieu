<?php
session_start();

// Load cấu hình chung
$config = include __DIR__ . '/../config/app.php';

require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\Router;

$basePath = $config['basePath'];
$base_url = $config['base_url'];

// Khởi tạo Router với basePath
$router = new Router($basePath);

// Biến toàn cục hoặc hằng số để dùng trong controller/view
$GLOBALS['base_url'] = $base_url;

// Gọi file routes để đăng ký route
require_once __DIR__ . '/../routes/web.php';

// Chạy router để xử lý request
$router->run();

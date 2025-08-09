<?php
namespace App\Controllers;

class HomeController {
    public function index() {
        // Lấy biến base_url từ biến toàn cục đã đặt trong index.php
        $base_url = $GLOBALS['base_url'] ?? '';

        // Tùy chọn: bạn có thể truyền thêm dữ liệu khác nếu cần

        // Gọi view, view sẽ dùng biến $base_url để tạo link CSS, JS chính xác
        require_once __DIR__ . '/../Views/home/index.php';
    }
}

<?php
namespace App\Middleware;

class AuthMiddleware {
    public function handle() {
        if (empty($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
    }
}

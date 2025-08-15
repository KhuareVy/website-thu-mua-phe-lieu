<?php
namespace App\Controllers;

use App\Models\UserModel;

class AuthController {
    private $userModel;

    public function __construct($config) {
        $this->userModel = new UserModel($config);
    }

    public function register($data) {
        if (empty($data['name']) || empty($data['password'])) {
            return ['error' => 'Vui lòng điền đầy đủ thông tin'];
        }

        if ($this->userModel->findByName($data['name'])) {
            return ['error' => 'Tên người dùng đã tồn tại'];
        }

        if ($this->userModel->create($data['name'], $data['password'])) {
            return ['success' => 'Đăng ký thành công!'];
        }

        return ['error' => 'Đã xảy ra lỗi, thử lại sau'];
    }

    public function login($data) {
        if (empty($data['name']) || empty($data['password'])) {
            return ['error' => 'Vui lòng điền đầy đủ thông tin'];
        }

        $user = $this->userModel->findByName($data['name']);
        if (!$user || !password_verify($data['password'], $user['password'])) {
            return ['error' => 'Tên đăng nhập hoặc mật khẩu không đúng'];
        }

        session_start();
        $_SESSION['user'] = [
            'id' => $user['id'],
            'name' => $user['name'],
            'role' => $user['role']
        ];

        return ['success' => 'Đăng nhập thành công!'];
    }

    public function logout() {
        session_start();
        session_destroy();
        header('Location: /login');
        exit;
    }
}

<?php
namespace App\Controllers;

use App\Models\User;

class AuthController {
    public function showRegisterForm() {
        include_once __DIR__ . '/../Views/auth/register.php';
    }

    public function register() {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';

        if ($password !== $passwordConfirm) {
            $_SESSION['error'] = 'Mật khẩu không khớp.';
            header('Location: /register');
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = 'Email không hợp lệ.';
            header('Location: /register');
            exit;
        }

        if (User::findByEmail($email)) {
            $_SESSION['error'] = 'Email đã được đăng ký.';
            header('Location: /register');
            exit;
        }

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $user = new User();
        $user->name = $name;
        $user->email = $email;
        $user->password = $hashedPassword;
        $user->save();

        $_SESSION['success'] = 'Đăng ký thành công. Vui lòng đăng nhập.';
        header('Location: /login');
        exit;
    }

    public function showLoginForm() {
        include_once __DIR__ . '/../Views/auth/login.php';
    }

    public function login() {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        $user = User::findByEmail($email);

        if (!$user || !password_verify($password, $user->password)) {
            $_SESSION['error'] = 'Email hoặc mật khẩu không đúng.';
            header('Location: /login');
            exit;
        }

        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_name'] = $user->name;

        header('Location: /');
        exit;
    }

    public function logout() {
        session_destroy();
        header('Location: /login');
        exit;
    }
}

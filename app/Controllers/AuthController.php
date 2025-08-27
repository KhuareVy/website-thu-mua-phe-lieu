<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Response;
use App\Models\UserModel;

class AuthController extends Controller
{
    private function setLayout(): void
    {
        $this->view->setLayout('layouts/main');
    }

    public function showRegister(): Response
    {
        if ($this->session->get('user_id')) {
            return $this->redirect('/');
        }
        $this->setLayout();
        $csrf_token = $this->session->getCsrfToken();
        return $this->render('auth/register', ['csrf_token' => $csrf_token]);
    }

    public function register(): Response
    {
        $this->setLayout();
        $csrf_token = $this->session->getCsrfToken();
        try {
            $data = $this->validate([
                'name' => 'required',
                'email' => 'required|email',
                'phone' => 'required',
                'password' => 'required|min:6',
            ]);
        } catch (\InvalidArgumentException $e) {
            $err = json_decode($e->getMessage(), true);
            if (is_array($err)) {
                $err = empty($err) ? 'Có lỗi dữ liệu.' : implode('<br>', array_map('htmlspecialchars', array_values($err)));
            } else {
                $err = htmlspecialchars((string)$err);
            }
            return $this->render('auth/register', ['error' => $err, 'csrf_token' => $csrf_token]);
        }

        if (!preg_match('/^[0-9]{10,15}$/', $data['phone'])) {
            return $this->render('auth/register', ['error' => 'Số điện thoại không hợp lệ', 'csrf_token' => $csrf_token]);
        }

        $userModel = new UserModel();
        if ($userModel->findByEmail($data['email'])) {
            return $this->render('auth/register', ['error' => 'Email đã tồn tại', 'csrf_token' => $csrf_token]);
        }
        if ($userModel->findByPhone($data['phone'])) {
            return $this->render('auth/register', ['error' => 'Số điện thoại đã tồn tại', 'csrf_token' => $csrf_token]);
        }

        // Tạo user mới, tự động hash password
        UserModel::createUser($data);
        return $this->redirect('/login');
    }

    public function showLogin(): Response
    {
        if ($this->session->get('user_id')) {
            return $this->redirect('/');
        }
        $this->setLayout();
        $csrf_token = $this->session->getCsrfToken();
        return $this->render('auth/login', ['csrf_token' => $csrf_token]);
    }

    public function login(): Response
    {
        $this->setLayout();
        $csrf_token = $this->session->getCsrfToken();
        $email = $this->input('email');
        $password = $this->input('password');
        $userModel = new UserModel();
        $user = $userModel->findByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            $err = 'Email hoặc mật khẩu không đúng';
            $err = htmlspecialchars($err);
            return $this->render('auth/login', ['error' => $err, 'csrf_token' => $csrf_token]);
        }

        $this->session->set('user_id', $user['id']);
        $this->session->set('user_name', $user['name']);
        $this->session->set('user_role', $user['role']);

        return $this->redirect('/');
    }

    public function logout(): Response
    {
        $this->setLayout();
        $this->session->remove('user_id');
        $this->session->remove('user_name');
        $this->session->remove('user_role');
        $this->session->destroy();
        return $this->redirect('/');
    }
}
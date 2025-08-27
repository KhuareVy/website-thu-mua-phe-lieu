<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Models\UserModel;

class AuthController extends Controller
{
    public function showRegister(): \App\Core\Response
    {
        return $this->render('auth/register');
    }

    public function register(): \App\Core\Response
    {
        try {
            $data = $this->validate([
                'name' => 'required',
                'email' => 'required|email',
                'phone' => 'required',
                'password' => 'required|min:6',
            ]);
        } catch (\InvalidArgumentException $e) {
            return $this->render('auth/register', ['error' => json_decode($e->getMessage(), true)]);
        }

        if (!preg_match('/^[0-9]{10,15}$/', $data['phone'])) {
            return $this->render('auth/register', ['error' => 'Số điện thoại không hợp lệ']);
        }

        $userModel = new UserModel();
        if ($userModel->findByEmail($data['email'])) {
            return $this->render('auth/register', ['error' => 'Email đã tồn tại']);
        }
        if ($userModel->findByPhone($data['phone'])) {
            return $this->render('auth/register', ['error' => 'Số điện thoại đã tồn tại']);
        }

        $userModel->create($data);
        return $this->redirect('/login');
    }

    public function showLogin(): \App\Core\Response
    {
        return $this->render('auth/login');
    }

    public function login(): \App\Core\Response
    {
        $email = $this->input('email');
        $password = $this->input('password');
        $userModel = new UserModel();
        $user = $userModel->findByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            return $this->render('auth/login', ['error' => 'Email hoặc mật khẩu không đúng']);
        }

        $this->session->set('user_id', $user['id']);
        $this->session->set('user_name', $user['name']);
        $this->session->set('user_role', $user['role']);

        return $this->redirect('/');
    }

    public function logout(): \App\Core\Response
    {
        $this->session->remove('user_id');
        $this->session->remove('user_name');
        $this->session->remove('user_role');
        $this->session->destroy();
        return $this->redirect('/');
    }
}

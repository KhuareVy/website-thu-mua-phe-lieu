<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Response;
use App\Models\UserModel;
use App\Models\CustomerModel;

class AuthController extends Controller
{
    private function setLayout(): void
    {
        $this->view->setLayout('layouts/default/main');
    }

    public function showRegister(): Response
    {
        if ($this->session->get('user_id')) {
            $user = $this->session->get('user_data');
            if (isset($user['role']) && $user['role'] === 'admin') {
                return $this->redirect('/dashboard');
            }
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
        // Validate dữ liệu đầu vào
        $data = [
            'full_name' => trim($this->input('full_name')),
            'email' => trim($this->input('email')),
            'phone_number' => trim($this->input('phone_number')),
            'password' => $this->input('password'),
        ];
        $error = '';
        if ($data['full_name'] === '' || $data['email'] === '' || $data['phone_number'] === '' || $data['password'] === '') {
            $error = 'Vui lòng nhập đầy đủ thông tin.';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $error = 'Email không hợp lệ.';
        } elseif (!preg_match('/^[0-9]{10,15}$/', $data['phone_number'])) {
            $error = 'Số điện thoại không hợp lệ.';
        } elseif (strlen($data['password']) < 3) {
            $error = 'Mật khẩu phải từ 3 ký tự.';
        }
        if ($error) {
            return $this->render('auth/register', ['error' => $error, 'csrf_token' => $csrf_token]);
        }

        $userModel = new UserModel();
        if ($userModel->findByEmail($data['email'])) {
            return $this->render('auth/register', ['error' => 'Email đã tồn tại', 'csrf_token' => $csrf_token]);
        }
        $customerModel = new CustomerModel();
        if ($customerModel->findByPhone($data['phone_number'])) {
            return $this->render('auth/register', ['error' => 'Số điện thoại đã tồn tại', 'csrf_token' => $csrf_token]);
        }

        // Tạo user
        $user_id = $userModel->createUser([
            'email' => $data['email'],
            'password' => $data['password'],
        ]);
        if (!$user_id) {
            error_log('Đăng ký: Không tạo được user. Dữ liệu: ' . json_encode($data));
            return $this->render('auth/register', ['error' => 'Không thể tạo tài khoản, vui lòng thử lại.', 'csrf_token' => $csrf_token]);
        }

        // Tạo customer
        $customer_data = [
            'user_id' => (int)$user_id,
            'full_name' => $data['full_name'],
            'phone_number' => $data['phone_number'],
        ];
        $customer_id = $customerModel->createCustomer($customer_data);
        if (!$customer_id) {
            error_log('Đăng ký: Không tạo được customer. Dữ liệu: ' . json_encode($customer_data));
            $userModel->deleteUser($user_id);
            return $this->render('auth/register', ['error' => 'Không thể lưu thông tin khách hàng, vui lòng thử lại.', 'csrf_token' => $csrf_token]);
        }

        error_log('Đăng ký thành công: user_id=' . $user_id . ', customer_id=' . $customer_id);
        return $this->redirect('/login');
    }

    public function showLogin(): Response
    {
        if ($this->session->get('user_id')) {
            $user = $this->session->get('user_data');
            if (isset($user['role']) && $user['role'] === 'admin') {
                return $this->redirect('/dashboard');
            }
            return $this->redirect('/');
        }
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Pragma: no-cache');
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

    $this->session->login($user);

        if ($user['role'] === 'admin') {
            return $this->redirect('/dashboard');
        }
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
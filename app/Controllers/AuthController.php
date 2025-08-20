<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Response;
use App\Models\UserModel;

class AuthController extends Controller
{
    public function showRegister()
    {
        return $this->view('auth/register');
    }

    public function register(Request $request)
    {
        $data = $request->getBody();

        // Validation cơ bản
        if (empty($data['name']) || empty($data['email']) || empty($data['phone']) || empty($data['password'])) {
            return $this->view('auth/register', ['error' => 'Vui lòng điền đầy đủ thông tin']);
        }

        // Kiểm tra định dạng số điện thoại (chỉ số, 10-15 ký tự)
        if (!preg_match('/^[0-9]{10,15}$/', $data['phone'])) {
            return $this->view('auth/register', ['error' => 'Số điện thoại không hợp lệ']);
        }

        $userModel = new UserModel();
        if ($userModel->findByEmail($data['email'])) {
            return $this->view('auth/register', ['error' => 'Email đã tồn tại']);
        }
        if ($userModel->findByPhone($data['phone'])) {
            return $this->view('auth/register', ['error' => 'Số điện thoại đã tồn tại']);
        }

        $userModel->create($data);

        $this->redirect('/login');
    }

    public function showLogin()
    {
        return $this->view('auth/login');
    }

    public function login(Request $request)
    {
        $data = $request->getBody();
        $userModel = new UserModel();
        $user = $userModel->findByEmail($data['email']);

        if (!$user || !password_verify($data['password'], $user['password'])) {
            return $this->view('auth/login', ['error' => 'Email hoặc mật khẩu không đúng']);
        }

        // Lưu session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_role'] = $user['role'];

        $this->redirect('/');
    }

    public function logout()
    {
        session_destroy();
        $this->redirect('/login');
    }
}

<?php
namespace App\Service;

use App\Repository\UserRepository;

class AuthService
{
    private UserRepository $userRepo;

    public function __construct()
    {
        $this->userRepo = new UserRepository();
    }

    public function login(string $email, string $password): bool
    {
        $user = $this->userRepo->findByEmail($email);
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            return true;
        }
        return false;
    }

    public function register(string $name, string $email, string $password): bool
    {
        if ($this->userRepo->findByEmail($email)) {
            // Email đã tồn tại
            return false;
        }
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        return $this->userRepo->create([
            'name' => $name,
            'email' => $email,
            'password' => $hashedPassword,
        ]);
    }
}

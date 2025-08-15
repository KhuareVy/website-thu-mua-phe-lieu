<?php
namespace App\Models;

use App\Core\Model;

class UserModel extends Model
{
    protected $table = 'users';

    public function create(array $data)
    {
        $sql = "INSERT INTO {$this->table} (name, email, password, role) 
                VALUES (:name, :email, :password, :role)";
        $this->query($sql, [
            ':name' => $data['name'],
            ':email' => $data['email'],
            ':password' => password_hash($data['password'], PASSWORD_BCRYPT),
            ':role' => $data['role'] ?? 'user'
        ]);
        return $this->lastInsertId();
    }

    public function findByEmail(string $email): ?array
    {
        $sql = "SELECT * FROM {$this->table} WHERE email = :email";
        return $this->fetch($sql, [':email' => $email]);
    }
}

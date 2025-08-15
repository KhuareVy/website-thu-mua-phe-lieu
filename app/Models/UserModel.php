<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class UserModel {
    private $pdo;

    public function __construct($config) {
        $this->pdo = $config['connection']();
    }

    public function create($name, $password) {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->pdo->prepare("INSERT INTO users (name, password) VALUES (:name, :password)");
        return $stmt->execute(['name' => $name, 'password' => $hash]);
    }

    public function findByName($name) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE name = :name");
        $stmt->execute(['name' => $name]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

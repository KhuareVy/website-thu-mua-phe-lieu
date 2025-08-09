<?php
namespace App\Models;

use PDO;

class User {
    public $id;
    public $name;
    public $email;
    public $password;

    protected static function getDB() {
        static $db = null;
        if ($db === null) {
            $config = include __DIR__ . '/../../config/database.php';
            $db = new PDO($config['dsn'], $config['username'], $config['password']);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        return $db;
    }

    public static function findByEmail($email) {
        $db = static::getDB();
        $stmt = $db->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, self::class);
        return $stmt->fetch();
    }

    public function save() {
        $db = static::getDB();
        $stmt = $db->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
        return $stmt->execute([
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
        ]);
    }
}

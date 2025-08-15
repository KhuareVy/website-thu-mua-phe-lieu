<?php
namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private static ?Database $instance = null;
    private PDO $connection;
    private array $config;

    private function __construct(array $config)
    {
        $this->config = $config;
        $this->connect();
    }

    public static function getInstance(array $config = []): Database
    {
        if (self::$instance === null) {
            if (empty($config)) {
                $config = require dirname(__DIR__, 2) . '/configs/database.php';
            }
            self::$instance = new Database($config);
        }
        return self::$instance;
    }

    private function connect(): void
    {
        try {
            $dsn = "mysql:host={$this->config['host']};dbname={$this->config['dbname']};charset={$this->config['charset']}";
            $this->connection = new PDO(
                $dsn,
                $this->config['username'],
                $this->config['password'],
                $this->config['options'] ?? []
            );
        } catch (PDOException $e) {
            die("Database Connection Error: " . $e->getMessage());
        }
    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }

    public function query(string $sql, array $params = []): \PDOStatement
    {
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public function fetchAll(string $sql, array $params = []): array
    {
        return $this->query($sql, $params)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function fetch(string $sql, array $params = []): ?array
    {
        $result = $this->query($sql, $params)->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function lastInsertId(): string
    {
        return $this->connection->lastInsertId();
    }
}

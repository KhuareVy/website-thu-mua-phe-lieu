<?php
namespace App\Core;

use PDO;

class Model
{
    protected PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    protected function query(string $sql, array $params = [])
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    protected function fetchAll(string $sql, array $params = []): array
    {
        return $this->query($sql, $params)->fetchAll(PDO::FETCH_ASSOC);
    }

    protected function fetch(string $sql, array $params = []): ?array
    {
        $result = $this->query($sql, $params)->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    protected function lastInsertId(): string
    {
        return $this->db->lastInsertId();
    }
}

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
            self::$instance = new self($config);
        }
        return self::$instance;
    }

    private function connect(): void
    {
        $dsn = sprintf(
            'mysql:host=%s;dbname=%s;charset=%s',
            $this->config['host'],
            $this->config['dbname'],
            $this->config['charset'] ?? 'utf8mb4'
        );

        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        if (isset($this->config['options']) && is_array($this->config['options'])) {
            $options = $this->config['options'] + $options;
        }

        try {
            $this->connection = new PDO(
                $dsn,
                $this->config['username'],
                $this->config['password'],
                $options
            );
        } catch (PDOException $e) {
            throw new PDOException('Database connection failed: ' . $e->getMessage());
        }
    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }

    public function query(string $sql, array $params = []): \PDOStatement
    {
        $statement = $this->connection->prepare($sql);
        if ($statement === false) {
            throw new \PDOException('Failed to prepare SQL statement.');
        }
        $statement->execute($params);
        return $statement;
    }

    public function fetchAll(string $sql, array $params = []): array
    {
        return $this->query($sql, $params)->fetchAll();
    }

    public function fetch(string $sql, array $params = []): ?array
    {
        $result = $this->query($sql, $params)->fetch();
        return $result ?: null;
    }

    public function insert(string $table, array $data): int
    {
        $columns = implode(',', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        $sql = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})";
        $this->query($sql, $data);
        return (int) $this->connection->lastInsertId();
    }

    public function update(string $table, array $data, array $where): int
    {
        $set = [];
        foreach ($data as $key => $value) {
            $set[] = "{$key} = :{$key}";
        }
        $whereClause = [];
        foreach ($where as $key => $value) {
            $whereClause[] = "{$key} = :where_{$key}";
            $data["where_{$key}"] = $value;
        }
        $sql = "UPDATE {$table} SET " . implode(', ', $set) .
               " WHERE " . implode(' AND ', $whereClause);
        return $this->query($sql, $data)->rowCount();
    }

    public function delete(string $table, array $where): int
    {
        $whereClause = [];
        foreach ($where as $key => $value) {
            $whereClause[] = "{$key} = :{$key}";
        }
        $sql = "DELETE FROM {$table} WHERE " . implode(' AND ', $whereClause);
        return $this->query($sql, $where)->rowCount();
    }

    public function beginTransaction(): bool
    {
        return $this->connection->beginTransaction();
    }

    public function commit(): bool
    {
        return $this->connection->commit();
    }

    public function rollback(): bool
    {
        return $this->connection->rollBack();
    }
}

<?php
declare(strict_types=1);

namespace App\Core;

abstract class Model
{
    protected Database $db;
    protected string $table;
    protected string $primaryKey = 'id';
    protected array $fillable = [];
    protected array $attributes = [];

    public function __construct()
    {
        $this->db = Database::getInstance();

        // Tự động set table nếu chưa định nghĩa
        if (empty($this->table)) {
            $className = (new \ReflectionClass($this))->getShortName();
            $this->table = strtolower($className) . 's';
        }
    }

    /* =====================
       Query Methods
    ===================== */

    /**
     * Find record by ID
     */
    public function find(int $id): ?static
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id LIMIT 1";
        $data = $this->db->fetch($sql, ['id' => $id]);
        return $data ? $this->hydrate($data) : null;
    }

    /**
     * Get all records
     */
    public function all(): array
    {
        $sql = "SELECT * FROM {$this->table}";
        $results = $this->db->fetchAll($sql);
        return $this->hydrateCollection($results);
    }

    /**
     * Where conditions
     */
    public function where(array $conditions): array
    {
        $where = implode(' AND ', array_map(fn($k) => "{$k} = :{$k}", array_keys($conditions)));
        $sql = "SELECT * FROM {$this->table} WHERE {$where}";
        $results = $this->db->fetchAll($sql, $conditions);
        return $this->hydrateCollection($results);
    }

    /**
     * First record with condition
     */
    public function firstWhere(array $conditions): ?static
    {
        $where = implode(' AND ', array_map(fn($k) => "{$k} = :{$k}", array_keys($conditions)));
        $sql = "SELECT * FROM {$this->table} WHERE {$where} LIMIT 1";
        $data = $this->db->fetch($sql, $conditions);
        return $data ? $this->hydrate($data) : null;
    }

    /* =====================
       CRUD
    ===================== */

    public function save(): bool
    {
        $data = $this->getFillableData();

        if (empty($this->attributes[$this->primaryKey])) {
            // Insert
            $id = $this->db->insert($this->table, $data);
            $this->attributes[$this->primaryKey] = $id;
            return $id > 0;
        }

        // Update
        $where = [$this->primaryKey => $this->attributes[$this->primaryKey]];
        return $this->db->update($this->table, $data, $where) > 0;
    }

    public function delete(): bool
    {
        if (empty($this->attributes[$this->primaryKey])) {
            return false;
        }
        $where = [$this->primaryKey => $this->attributes[$this->primaryKey]];
        return $this->db->delete($this->table, $where) > 0;
    }

    public static function create(array $data): static
    {
        $instance = new static();
        $instance->fill($data);
        $instance->save();
        return $instance;
    }

    /* =====================
       Attribute Handling
    ===================== */

    public function fill(array $data): void
    {
        foreach ($data as $key => $value) {
            if (in_array($key, $this->fillable, true)) {
                $this->attributes[$key] = $value;
            }
        }
    }

    private function getFillableData(): array
    {
        return array_intersect_key($this->attributes, array_flip($this->fillable));
    }

    public function __get(string $name)
    {
        return $this->attributes[$name] ?? null;
    }

    public function __set(string $name, $value): void
    {
        if (in_array($name, $this->fillable, true)) {
            $this->attributes[$name] = $value;
        }
    }

    /* =====================
       Utilities
    ===================== */

    protected function hydrate(array $data): static
    {
        $instance = new static();
        $instance->attributes = $data;
        return $instance;
    }

    protected function hydrateCollection(array $rows): array
    {
        return array_map(fn($row) => $this->hydrate($row), $rows);
    }

    public function toArray(): array
    {
        return $this->attributes;
    }

    public function toJson(): string
    {
        return json_encode($this->attributes, JSON_UNESCAPED_UNICODE);
    }
}

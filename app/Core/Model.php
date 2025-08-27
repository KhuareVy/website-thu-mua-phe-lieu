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
        // Set table name based on class name if not defined
        if (empty($this->table)) {
            $className = (new \ReflectionClass($this))->getShortName();
            $this->table = strtolower($className) . 's';
        }
    }

    /**
     * Find record by ID
     */
    public function find(int $id): ?static
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id LIMIT 1";
        $data = $this->db->fetch($sql, ['id' => $id]);
        if ($data) {
            $instance = new static();
            $instance->attributes = $data;
            return $instance;
        }
        return null;
    }

    /**
     * Find all records
     */
    public function all(): array
    {
        $sql = "SELECT * FROM {$this->table}";
        $results = $this->db->fetchAll($sql);
        $models = [];
        foreach ($results as $data) {
            $instance = new static();
            $instance->attributes = $data;
            $models[] = $instance;
        }
        return $models;
    }

    /**
     * Find records with conditions
     */
    public function where(array $conditions): array
    {
        $whereClause = [];
        foreach ($conditions as $key => $value) {
            $whereClause[] = "{$key} = :{$key}";
        }
        $sql = "SELECT * FROM {$this->table} WHERE " . implode(' AND ', $whereClause);
        $results = $this->db->fetchAll($sql, $conditions);
        $models = [];
        foreach ($results as $data) {
            $instance = new static();
            $instance->attributes = $data;
            $models[] = $instance;
        }
        return $models;
    }

    /**
     * Save model (insert or update)
     */
    public function save(): bool
    {
        $data = $this->getFillableData();
        if (empty($this->attributes[$this->primaryKey])) {
            // Insert new record
            $id = $this->db->insert($this->table, $data);
            $this->attributes[$this->primaryKey] = $id;
            return $id > 0;
        } else {
            // Update existing record
            $where = [$this->primaryKey => $this->attributes[$this->primaryKey]];
            $affectedRows = $this->db->update($this->table, $data, $where);
            return $affectedRows > 0;
        }
    }

    /**
     * Delete model
     */
    public function delete(): bool
    {
        if (empty($this->attributes[$this->primaryKey])) {
            return false;
        }
        $where = [$this->primaryKey => $this->attributes[$this->primaryKey]];
        $affectedRows = $this->db->delete($this->table, $where);
        return $affectedRows > 0;
    }

    /**
     * Create new record
     */
    public static function create(array $data): static
    {
        $instance = new static();
        $instance->fill($data);
        $instance->save();
        return $instance;
    }

    /**
     * Fill model with data
     */
    public function fill(array $data): void
    {
        foreach ($data as $key => $value) {
            if (in_array($key, $this->fillable)) {
                $this->attributes[$key] = $value;
            }
        }
    }

    /**
     * Get fillable data only
     */
    private function getFillableData(): array
    {
        $data = [];
        foreach ($this->fillable as $field) {
            if (isset($this->attributes[$field])) {
                $data[$field] = $this->attributes[$field];
            }
        }
        return $data;
    }

    /**
     * Get attribute value
     */
    public function __get(string $name)
    {
        return $this->attributes[$name] ?? null;
    }

    /**
     * Set attribute value
     */
    public function __set(string $name, $value): void
    {
        if (in_array($name, $this->fillable)) {
            $this->attributes[$name] = $value;
        }
    }

    /**
     * Convert to array
     */
    public function toArray(): array
    {
        return $this->attributes;
    }

    /**
     * Convert to JSON
     */
    public function toJson(): string
    {
        return json_encode($this->attributes);
    }
}

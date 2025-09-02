<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

class UserModel extends Model
{
    protected string $table = 'users';
    protected string $primaryKey = 'id';
    protected array $fillable = [
        'id',
        'email',
        'password',
        'role',
        'is_active',
        'created_at',
        'updated_at',
    ];

    // Lấy tất cả user
    public function getAll(): array
    {
        $users = $this->all();
        return array_map(fn($u) => $u->toArray(), $users);
    }

    // Lấy user theo id
    public function getById($id): ?array
    {
        $user = $this->find((int)$id);
        return $user ? $user->toArray() : null;
    }

    // Tạo user mới
    public function createUser(array $data): int
    {
        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        $data['role'] = $data['role'] ?? 'customer';
        $user = self::create($data);
        return $user->{$this->primaryKey} ?? 0;
    }

    // Cập nhật user
    public function updateUser($id, array $data): bool
    {
        $user = $this->find((int)$id);
        if (!$user) return false;
        $user->fill($data);
        return $user->save();
    }

    // Xóa user
    public function deleteUser($id): bool
    {
        $user = $this->find((int)$id);
        if (!$user) return false;
        return $user->delete();
    }

    // Tìm user theo email
    public function findByEmail(string $email): ?array
    {
        $result = $this->where(['email' => $email]);
        if (empty($result) || !isset($result[0])) {
            return null;
        }
        return $result[0]->toArray();
    }

    // ...existing code...
}

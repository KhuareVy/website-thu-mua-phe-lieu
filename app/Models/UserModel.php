<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

class UserModel extends Model
{
    protected string $table = 'users';
    protected string $primaryKey = 'id';
    protected array $fillable = [
        'name', 'email', 'phone', 'password', 'role'
    ];

    /**
     * Tạo user mới (hash password tự động)
     */
    public static function createUser(array $data): static
    {
        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        $data['role'] = $data['role'] ?? 'user';
        return static::create($data);
    }

    /**
     * Tìm user theo email
     */
    public function findByEmail(string $email): ?array
    {
        $result = $this->where(['email' => $email]);
        return $result[0]->toArray() ?? null;
    }

    /**
     * Tìm user theo số điện thoại
     */
    public function findByPhone(string $phone): ?array
    {
        $result = $this->where(['phone' => $phone]);
        return $result[0]->toArray() ?? null;
    }
}

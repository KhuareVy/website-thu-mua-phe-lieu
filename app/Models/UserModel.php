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
        $data['role'] = $data['role'] ?? 'customer';
        // Đổi key phone_number -> phone nếu có
        if (isset($data['phone_number'])) {
            $data['phone'] = $data['phone_number'];
            unset($data['phone_number']);
        }
        return static::create($data);
    }

    /**
     * Tìm user theo email
     */
    public function findByEmail(string $email): ?array
    {
        $result = $this->where(['email' => $email]);
        if (empty($result) || !isset($result[0])) {
            return null;
        }
        return $result[0]->toArray();
    }

    /**
     * Tìm user theo số điện thoại
     */
    public function findByPhone(string $phone): ?array
    {
        $result = $this->where(['phone' => $phone]);
        if (empty($result) || !isset($result[0])) {
            return null;
        }
        return $result[0]->toArray();
    }
}

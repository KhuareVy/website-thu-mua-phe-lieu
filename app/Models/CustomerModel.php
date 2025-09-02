<?php
namespace App\Models;

use App\Core\Model;

class CustomerModel extends Model
{
    protected string $table = 'customers';
    protected string $primaryKey = 'user_id';
    protected array $fillable = [
        'user_id',
        'full_name',
        'phone_number',
        'created_at',
        'updated_at',
    ];

    // Lấy tất cả khách hàng
    public function getAll(): array
    {
        $customers = $this->all();
        return array_map(fn($c) => $c->toArray(), $customers);
    }

    // Lấy khách hàng theo user_id
    public function getById(int $user_id): ?array
    {
        $customer = $this->find($user_id);
        return $customer ? $customer->toArray() : null;
    }

    // Tạo khách hàng mới
    public function createCustomer(array $data): int
    {
        $data['user_id'] = (int)$data['user_id'];
        try {
            $customer = new static();
            $customer->fill($data);
            $customer->save();
            if ($customer && $customer->user_id) {
                return (int)$customer->user_id;
            }
        } catch (\Throwable $e) {
            // Có thể log lỗi ở nơi khác nếu cần
        }
        return 0;
    }

    // Cập nhật thông tin khách hàng
    public function updateCustomer(int $user_id, array $data): bool
    {
        $customer = $this->find($user_id);
        if (!$customer) return false;
        $customer->fill($data);
        return $customer->save();
    }

    // Xóa khách hàng
    public function deleteCustomer(int $user_id): bool
    {
        $customer = $this->find($user_id);
        if (!$customer) return false;
        return $customer->delete();
    }

    // Tìm khách hàng theo số điện thoại
    public function findByPhone(string $phone): ?array
    {
        $result = $this->where(['phone_number' => $phone]);
        if (empty($result) || !isset($result[0])) {
            return null;
        }
        return $result[0]->toArray();
    }

    // Kiểm tra khách hàng đã tồn tại theo user_id
    public function exists(int $user_id): bool
    {
        return $this->find($user_id) !== null;
    }
}

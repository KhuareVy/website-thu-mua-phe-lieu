<?php
namespace App\Models;

use App\Core\Model;

class StaffModel extends Model
{
    protected string $table = 'staffs';
    protected string $primaryKey = 'user_id';
    protected array $fillable = [
        'user_id',
        'employee_id',
        'full_name',
        'phone_number',
        'created_at',
        'updated_at',
    ];

    // Lấy tất cả nhân viên
    public function getAll(): array
    {
        $staffs = $this->all();
        return array_map(fn($s) => $s->toArray(), $staffs);
    }

    // Lấy nhân viên theo user_id
    public function getById(int $user_id): ?array
    {
        $staff = $this->find($user_id);
        return $staff ? $staff->toArray() : null;
    }

    // Tạo nhân viên mới
    public function createStaff(array $data): int
    {
        $data['user_id'] = (int)($data['user_id'] ?? 0);
        if (!isset($data['employee_id']) || empty($data['employee_id'])) {
            return 0; // employee_id is required
        }
        $staff = $this->create($data);
        return $staff ? $data['user_id'] : 0;
    }

    // Cập nhật thông tin nhân viên
    public function updateStaff(int $user_id, array $data): bool
    {
        $staff = $this->find($user_id);
        if (!$staff) return false;
        // Prevent changing user_id
        unset($data['user_id']);
        $staff->fill($data);
        return $staff->save();
    }

    // Xóa nhân viên
    public function deleteStaff(int $user_id): bool
    {
        $staff = $this->find($user_id);
        if (!$staff) return false;
        return $staff->delete();
    }

    // Tìm nhân viên theo số điện thoại
    public function findByPhone(string $phone): ?array
    {
        $result = $this->where(['phone_number' => $phone]);
        if (empty($result) || !isset($result[0])) {
            return null;
        }
        return $result[0]->toArray();
    }

    // Tìm nhân viên theo employee_id
    public function findByEmployeeId(string $employee_id): ?array
    {
        $result = $this->where(['employee_id' => $employee_id]);
        if (empty($result) || !isset($result[0])) {
            return null;
        }
        return $result[0]->toArray();
    }

    // Kiểm tra nhân viên đã tồn tại theo user_id
    public function exists(int $user_id): bool
    {
        return $this->find($user_id) !== null;
    }
}

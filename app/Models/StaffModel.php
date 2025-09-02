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
}

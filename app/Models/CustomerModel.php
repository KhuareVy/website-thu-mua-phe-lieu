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
}

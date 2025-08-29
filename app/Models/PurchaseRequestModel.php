<?php
namespace App\Models;

use App\Core\Model;

class PurchaseRequestModel extends Model
{
    protected string $table = 'purchase_requests';
    protected string $primaryKey = 'id';
    protected array $fillable = [
        'request_code',
        'user_id',
        'customer_name',
        'customer_phone',
        'customer_address',
        'notes',
        'status',
        'created_at',
        'processed_by_staff_id',
    ];
}

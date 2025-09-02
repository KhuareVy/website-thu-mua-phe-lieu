<?php
namespace App\Models;

use App\Core\Model;

class PurchaseRequestModel extends Model
{
    protected string $table = 'purchase_requests';
    protected string $primaryKey = 'id';
    protected array $fillable = [
        'id',
        'request_code',
        'customer_id',
        'pickup_address',
        'pickup_province_id',
        'notes',
        'status',
        'actual_total_weight',
        'actual_total_value',
        'created_at',
        'updated_at',
        'processed_by_staff_id',
        'completed_at',
    ];
}

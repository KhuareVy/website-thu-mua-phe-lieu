<?php
namespace App\Models;

use App\Core\Model;

class SaleOrderModel extends Model
{
    protected string $table = 'sale_orders';
    protected string $primaryKey = 'id';
    protected array $fillable = [
        'order_code',
        'user_id',
        'customer_name',
        'customer_phone',
        'customer_address',
        'total_amount',
        'status',
        'payment_method',
        'created_at',
    ];
}

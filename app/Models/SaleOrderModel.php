<?php
namespace App\Models;

use App\Core\Model;

class SaleOrderModel extends Model
{
    protected string $table = 'sale_orders';
    protected string $primaryKey = 'id';
    protected array $fillable = [
        'id',
        'order_code',
        'customer_id',
        'customer_address',
        'status',
        'payment_status',
        'payment_method',
        'subtotal',
        'total_amount',
        'notes',
        'shipping_date',
        'delivery_date',
        'created_at',
        'updated_at',
        'processed_by_staff_id',
    ];
}

<?php
namespace App\Models;

use App\Core\Model;

class SaleOrderDetailModel extends Model
{
    protected string $table = 'sale_order_details';
    protected string $primaryKey = 'id';
    protected array $fillable = [
        'sale_order_id',
        'scrap_id',
        'quantity',
        'id',
        'price_per_unit',
        'sub_total',
        'unit_price',
        'total_price',
        'created_at',
    ];
}

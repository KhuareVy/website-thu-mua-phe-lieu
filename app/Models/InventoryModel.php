<?php
namespace App\Models;

use App\Core\Model;

class InventoryModel extends Model
{
    protected string $table = 'inventory';
    protected string $primaryKey = 'id';
    protected array $fillable = [
        'id',
        'scrap_id',
        'quantity',
        'reserved_quantity',
        'min_stock_level',
        'last_updated',
    ];
}

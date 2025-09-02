<?php
namespace App\Models;

use App\Core\Model;

class PurchaseRequestDetailModel extends Model
{
    protected string $table = 'purchase_request_details';
    protected string $primaryKey = 'id';
    protected array $fillable = [
        'id',
        'request_id',
        'scrap_id',
        'actual_quantity',
        'agreed_price',
        'total_amount',
        'created_at',
    ];
}

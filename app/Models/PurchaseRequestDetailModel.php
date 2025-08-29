<?php
namespace App\Models;

use App\Core\Model;

class PurchaseRequestDetailModel extends Model
{
    protected string $table = 'purchase_request_details';
    protected string $primaryKey = 'id';
    protected array $fillable = [
        'request_id',
        'scrap_id',
        'quantity',
        'unit_price',
    ];
}

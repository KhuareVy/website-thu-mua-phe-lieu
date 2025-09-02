<?php
namespace App\Models;

use App\Core\Model;

class PurchaseRequestImageModel extends Model
{
    protected string $table = 'purchase_request_images';
    protected string $primaryKey = 'id';
    protected array $fillable = [
        'id',
        'request_id',
        'image_url',
        'uploaded_at',
    ];
}

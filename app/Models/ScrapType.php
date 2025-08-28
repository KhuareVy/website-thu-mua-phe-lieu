<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

class ScrapType extends Model
{
    protected string $table = 'scrap_types';
    protected string $primaryKey = 'id';
    protected array $fillable = [
        'scrap_category_id',
        'name',
        'description',
        'image_url',
        'unit',
        'is_buyable',
        'is_sellable',
        'price'
    ];

}

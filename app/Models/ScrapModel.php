<?php
namespace App\Models;

use App\Core\Model;

class ScrapModel extends Model
{
    protected string $table = 'scraps';
    protected string $primaryKey = 'id';
    protected array $fillable = [
        'id',
        'scrap_type_id',
        'name',
        'slug',
        'description',
        'image_url',
        'unit',
        'is_sellable',
        'is_buyable',
        'is_active',
        'created_at',
        'updated_at',
    ];
}

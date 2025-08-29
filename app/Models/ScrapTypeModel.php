<?php
namespace App\Models;

use App\Core\Model;

class ScrapTypeModel extends Model
{
    protected string $table = 'scrap_types';
    protected string $primaryKey = 'id';
    protected array $fillable = [
        'name',
    ];
    // Add custom methods if needed
}

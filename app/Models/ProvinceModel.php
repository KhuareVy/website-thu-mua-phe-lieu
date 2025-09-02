<?php
namespace App\Models;

use App\Core\Model;

class ProvinceModel extends Model
{
    protected string $table = 'provinces';
    protected string $primaryKey = 'id';
    protected array $fillable = [
        'id',
        'name',
    ];
}

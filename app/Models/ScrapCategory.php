<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

class ScrapCategory extends Model
{
    protected string $table = 'scrap_categories';
    protected string $primaryKey = 'id';
    protected array $fillable = [
        'name', 'description'
    ];

    // Thêm các phương thức truy vấn nếu cần
}

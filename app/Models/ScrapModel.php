<?php
namespace App\Models;

use App\Core\Model;

class ScrapModel extends Model
{
    protected string $table = 'scraps';
    protected string $primaryKey = 'id';
    protected array $fillable = [
        'scrap_type_id',
        'name',
        'description',
        'image_url',
        'unit',
        'is_buyable',
        'is_sellable',
        'buy_price',
        'sell_price',
    ];
    /**
     * Lấy tất cả loại phế liệu (dạng mảng cho view card)
     */
    public function getAllForCards(): array
    {
        // Sử dụng all() từ Model, trả về mảng
        return array_map(fn($item) => $item->toArray(), $this->all());
    }

    /**
     * Lấy loại phế liệu theo danh mục (dạng mảng cho view card)
     */
    public function getByCategoryForCards(int $categoryId): array
    {
        $rows = $this->where(['scrap_category_id' => $categoryId]);
        return array_map(fn($item) => $item->toArray(), $rows);
    }
}

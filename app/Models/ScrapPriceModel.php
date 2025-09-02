<?php
namespace App\Models;

use App\Core\Model;

class ScrapPriceModel extends Model
{
    protected string $table = 'scrap_prices';
    protected string $primaryKey = 'id';
    protected array $fillable = [
        'id',
        'scrap_id',
        'buy_price_min',
        'buy_price_max',
        'sell_price_min',
        'sell_price_max',
        'effective_from',
        'effective_to',
        'created_at',
    ];
}

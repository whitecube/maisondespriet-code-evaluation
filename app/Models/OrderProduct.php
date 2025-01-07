<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;
use App\Casts\Money;
use App\Models\Traits\FormatsPrices;

class OrderProduct extends Model implements Sortable
{
    use SortableTrait, FormatsPrices;

    public $sortable = [
        'order_column_name' => 'order',
        'sort_when_creating' => true,
    ];
    
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'price_unit' => Money::class,
            'price_final' => Money::class,
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}

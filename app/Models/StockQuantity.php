<?php

namespace App\Models;

use App\Scopes\DescendingTimeScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockQuantity extends Model
{
    use HasFactory;

    public const AUTOMATED_CODES = [
        1 => 'Stock split',
    ];

    public $timestamps = true;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    protected static function booted(): void
    {
        static::addGlobalScope(new DescendingTimeScope);
    }

    public function getStockIdAttribute(): int
    {
        return intval($this->attributes['stock_id']);
    }

    public function getQuantityAttribute(): float
    {
        return floatval($this->attributes['quantity']);
    }

    public function getTimeAttribute(): Carbon
    {
        return new Carbon($this->attributes['time']);
    }

    public function getAutomatedCodeAttribute(): int
    {
        return intval($this->attributes['automated_code']);
    }

    // Relationships

    public function stock(): BelongsTo
    {
        return $this->belongsTo(Stock::class);
    }

    public function getStock(): Stock
    {
        return $this->stock()->first();
    }
}

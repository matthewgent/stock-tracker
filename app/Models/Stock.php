<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Stock extends Model
{
    use HasFactory;

    public $timestamps = true;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    public function getItemIdAttribute(): int
    {
        return intval($this->attributes['item_id']);
    }

    public function getTickerIdAttribute(): int
    {
        return intval($this->attributes['ticker_id']);
    }

    // Relationships

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function ticker(): BelongsTo
    {
        return $this->belongsTo(Ticker::class);
    }

    public function stockQuantities(): HasMany
    {
        return $this->hasMany(StockQuantity::class);
    }

    public function getTicker(): Ticker
    {
        return $this->ticker()->first();
    }

    public function getItem(): Item
    {
        return $this->item()->first();
    }
}

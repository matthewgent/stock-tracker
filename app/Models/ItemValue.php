<?php

namespace App\Models;

use App\Scopes\DescendingTimeScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'currency_id',
        'value',
        'time',
    ];

    public $timestamps = true;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->makeHidden(['item_id']);
    }

    protected static function booted(): void
    {
        static::addGlobalScope(new DescendingTimeScope);
    }

    public function getItemIdAttribute(): int
    {
        return intval($this->attributes['item_id']);
    }

    public function getCurrencyIdAttribute(): int
    {
        return intval($this->attributes['currency_id']);
    }

    public function getValueAttribute(): float
    {
        return floatval($this->attributes['value']);
    }

    public function getTimeAttribute(): Carbon
    {
        return new Carbon($this->attributes['time']);
    }

    // Relationships

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    // Business Logic

    public function getItem(): Item
    {
        return $this->item()->first();
    }
}

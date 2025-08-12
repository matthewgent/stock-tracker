<?php

namespace App\Models;

use App\Scopes\DescendingTimeScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CurrencyRate extends Model
{
    protected $fillable = [
        'currency_id',
        'usd_rate',
        'time',
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

    public function getCurrencyIdAttribute(): int
    {
        return intval($this->attributes['currency_id']);
    }

    public function getUsdRateAttribute(): float
    {
        return floatval($this->attributes['usd_rate']);
    }

    public function getTimeAttribute(): Carbon
    {
        return new Carbon($this->attributes['time']);
    }

    // Relationships

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }
}

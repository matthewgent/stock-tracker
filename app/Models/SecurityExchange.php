<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SecurityExchange extends Model
{
    use HasFactory;

    public $timestamps = true;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    public function getSovereignStateIdAttribute(): int
    {
        return intval($this->attributes['sovereign_state_id']);
    }

    public function getCurrencyIdAttribute(): int
    {
        return intval($this->attributes['currency_id']);
    }

    public function getMicAttribute(): string
    {
        return $this->attributes['mic'];
    }

    public function getShortNameAttribute(): string
    {
        return $this->attributes['short_name'];
    }

    public function getNameAttribute(): string
    {
        return $this->attributes['name'];
    }

    public function getPriceCoefficientAttribute(): float
    {
        return $this->attributes['price_coefficient'];
    }

    // Relationships

    public function sovereignState(): BelongsTo
    {
        return $this->belongsTo(SovereignState::class);
    }

    public function tickers(): HasMany
    {
        return $this->hasMany(Ticker::class);
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    public function getCurrency(): Currency
    {
        return $this->currency()->first();
    }

    public function getSovereignState(): SovereignState
    {
        return $this->sovereignState()->first();
    }
}

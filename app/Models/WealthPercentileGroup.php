<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WealthPercentileGroup extends Model
{
    public $timestamps = true;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    public function getVersionAttribute(): int
    {
        return $this->attributes['version'];
    }

    public function getPublishTimeAttribute(): Carbon
    {
        return new Carbon($this->attributes['publish_time']);
    }

    public function getSourceAttribute(): string
    {
        return $this->attributes['source'];
    }

    public function getSourceDateAttribute(): Carbon
    {
        return new Carbon($this->attributes['source_date']);
    }

    public function getCurrencyIdAttribute(): int
    {
        return intval($this->attributes['currency_id']);
    }

    // Relationships

    public function sovereignState(): BelongsTo
    {
        return $this->belongsTo(SovereignState::class);
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    public function wealthPercentiles(): HasMany
    {
        return $this->hasMany(WealthPercentile::class);
    }
}

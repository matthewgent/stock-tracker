<?php

namespace App\Models;

use App\Utilities\Money;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WealthPercentile extends Model
{
    public $timestamps = true;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    public function getPercentileAttribute(): int
    {
        return $this->attributes['percentile'];
    }

    public function getValueAttribute(): float
    {
        return floatval($this->attributes['value']);
    }

    // Relationships

    public function wealthPercentileGroup(): BelongsTo
    {
        return $this->belongsTo(WealthPercentileGroup::class);
    }
}

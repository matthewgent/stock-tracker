<?php

namespace App\Models;

use App\Scopes\DescendingTimeScope;
use App\Utilities\Money;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TickerPrice extends Model
{
    use HasFactory;

    public $timestamps = true;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->fillable([
            'ticker_id',
            'price',
            'adjusted_price',
            'volume',
            'split_factor',
            'dividend',
            'time',
        ]);

        $this->append('days_ago');

        $this->makeHidden([
            'adjusted_price',
            'dividend',
            'id',
            'split_factor',
            'ticker_id',
            'volume',
        ]);
    }

    protected static function booted(): void
    {
        static::addGlobalScope(new DescendingTimeScope);
    }

    public function getDaysAgoAttribute(): int
    {
        $now = new Carbon();
        $then = $this->getTimeAttribute();
        return $now->diffInDays($then);
    }

    public function getTickerIdAttribute(): int
    {
        return intval($this->attributes['ticker_id']);
    }

    public function getTimeAttribute(): Carbon
    {
        return new Carbon($this->attributes['time']);
    }

    public function setTimeAttribute($value): void
    {
        $date = new Carbon($value);
        $this->attributes['first_name'] = $date->getTimestamp();
    }

    public function getPriceAttribute(): float
    {
        return floatval($this->attributes['price']);
    }

    public function getVolumeAttribute(): int
    {
        return intval($this->attributes['volume']);
    }

    public function getSplitFactorAttribute(): float
    {
        return floatval($this->attributes['split_factor']);
    }

    public function getDividendAttribute(): float
    {
        return floatval($this->attributes['dividend']);
    }

    // Relationships

    public function ticker(): BelongsTo
    {
        return $this->belongsTo(Ticker::class);
    }

    public function getTicker(): Ticker
    {
        return $this->ticker()->first();
    }
}

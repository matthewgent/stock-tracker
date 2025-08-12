<?php

namespace App\Models;

use App\Exceptions\MissingDataException;
use App\Utilities\TickerChartPeriods;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Currency extends Model
{
    protected $fillable = [
        'crypto',
        'name',
        'code',
        'symbol'
    ];

    public $timestamps = true;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    public function getCryptoAttribute(): bool
    {
        return boolval($this->attributes['name']);
    }

    public function getNameAttribute(): string
    {
        return $this->attributes['name'];
    }

    public function getCodeAttribute(): string
    {
        return $this->attributes['code'];
    }

    public function getSymbolAttribute(): string
    {
        return $this->attributes['symbol'];
    }

    // Relationships

    public function itemValues(): HasMany
    {
        return $this->hasMany(ItemValue::class);
    }

    public function rates(): HasMany
    {
        return $this->hasMany(CurrencyRate::class);
    }

    public function chartRates(): HasMany
    {
        return $this->rates()->whereIntegerInRaw('time', TickerChartPeriods::getAllDates());
    }

    public function latestRate(): HasOne
    {
        return $this->hasOne(CurrencyRate::class)->orderByDesc('time');
    }

    public function members(): HasMany
    {
        return $this->hasMany(Member::class);
    }

    public function wealthPercentileVersions(): HasMany
    {
        return $this->hasMany(WealthPercentileGroup::class);
    }

    public static function getByCode(string $code): ?self
    {
        return self::query()->firstWhere('code', $code);
    }
}

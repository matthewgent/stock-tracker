<?php

namespace App\Models;

use App\Services\MarketStack;
use App\Utilities\TickerChartPeriods;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Log;

class Ticker extends Model
{
    use HasFactory;

    public $timestamps = true;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    public function getSecurityExchangeIdAttribute(): int
    {
        return intval($this->attributes['security_exchange_id']);
    }

    public function getCurrencyIdAttribute(): int
    {
        return intval($this->attributes['currency_id']);
    }

    public function getSymbolAttribute(): string
    {
        return $this->attributes['symbol'];
    }

    public function getNameAttribute(): string
    {
        return $this->attributes['name'];
    }

    // Relationships

    public function securityExchange(): BelongsTo
    {
        return $this->belongsTo(SecurityExchange::class);
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    public function stocks(): HasMany
    {
        return $this->hasMany(Stock::class);
    }

    public function prices(): HasMany
    {
        return $this->hasMany(TickerPrice::class);
    }

    public function chartPrices(): HasMany
    {
        return $this->prices()->whereIntegerInRaw('time', TickerChartPeriods::getAllDates());
    }

    public function latestPrice(): HasOne
    {
        return $this->hasOne(TickerPrice::class)->orderByDesc('time');
    }

    public function getSecurityExchange(): SecurityExchange
    {
        return $this->securityExchange()->first();
    }

    // Business Logic

    public function getMarketStackSymbol(): string
    {
        $exchange = $this->getSecurityExchange();
        $sovereignState = $exchange->getSovereignState();
        $marketStackSymbol = $this->getSymbolAttribute();
        if ($sovereignState->getIdAttribute() !== 239) {
            $mic = $exchange->getMicAttribute();
            $marketStackSymbol .= '.' . $mic;
        }

        return $marketStackSymbol;
    }

    public function pricesExist(): bool
    {
        $fortnightAgo = now()->subDays(14);
        $pricesArray = MarketStack::fetchPrices($this, $fortnightAgo);

        return !empty($pricesArray);
    }

    public function getLatestPrice(): ?TickerPrice
    {
        return $this->prices()->first();
    }

    public function updatePrices(): void
    {
        $nextWeekday = new Carbon('1970-01-01');
        $latestPrice = $this->getLatestPrice();

        if ($latestPrice !== null) {
            $nextWeekday = $latestPrice->getTimeAttribute();
            $nextWeekday->setTime(0, 0);
            $nextWeekday->nextWeekday();
        }

        $tickerId = $this->getIdAttribute();

        $previousWeekday = new Carbon;
        $previousWeekday->setTime(0, 0);
        $previousWeekday->previousWeekday();

        if ($previousWeekday->getTimestamp() >= $nextWeekday->getTimestamp()) {
            $offset = 0;
            do {
                $pricesArray = MarketStack::fetchPrices($this, $nextWeekday, $offset);

                $sanitizedArray = array();

                foreach ($pricesArray as $priceArray) {
                    if (
                        $priceArray['close'] !== null
                        and $priceArray['close'] !== ''
                        and $priceArray['adj_close'] !== null
                        and $priceArray['adj_close'] !== ''
                        and $priceArray['volume'] !== null
                        and $priceArray['volume'] !== ''
                        and $priceArray['split_factor'] !== null
                        and $priceArray['split_factor'] !== ''
                        and $priceArray['dividend'] !== null
                        and $priceArray['dividend'] !== ''
                        and $priceArray['date'] !== null
                        and $priceArray['date'] !== ''
                    ) {
                        $time = new Carbon($priceArray['date']);
                        $time->setTime(0, 0);
                        $sanitizedArray[] = [
                            'ticker_id' => $tickerId,
                            'price' => $priceArray['close'],
                            'adjusted_price' => $priceArray['adj_close'],
                            'volume' => $priceArray['volume'],
                            'split_factor' => $priceArray['split_factor'],
                            'dividend' => $priceArray['dividend'],
                            'time' => $time->getTimestamp(),
                        ];
                    }
                }

                TickerPrice::query()->insert($sanitizedArray);

                $offset += MarketStack::LIMIT;
            } while (count($pricesArray) !== 0);
        }
    }
}

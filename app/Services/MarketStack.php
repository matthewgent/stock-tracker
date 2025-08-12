<?php

namespace App\Services;

use App\Models\Ticker;
use App\Models\TickerPrice;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class MarketStack
{
    private const SERVICE_URL = 'https://api.marketstack.com/v1/';
    public const LIMIT = 1000;

    public static function fetchExchanges(): array
    {
        $response = Http::get(
            self::SERVICE_URL.'exchanges',
            [
                'access_key' => config('services.marketstack.key')
            ]
        );

        $array = $response->json();

        return $array['data'];
    }

    public static function fetchTickers(int $page = 0): array
    {
        $response = Http::get(
            self::SERVICE_URL.'tickers',
            [
                'access_key' => config('services.marketstack.key'),
                'limit' => self::LIMIT,
                'offset' => $page,
            ]
        );

        $array = $response->json();

        return $array['data'];
    }

    public static function fetchPrices(Ticker $ticker, Carbon $from, int $page = 0): array
    {
        $response = Http::get(
            self::SERVICE_URL.'eod',
            [
                'access_key' => config('services.marketstack.key'),
                'limit' => self::LIMIT,
                'date_from' => $from->toDateString(),
                'offset' => $page,
                'symbols' => $ticker->getMarketStackSymbol(),
                'exchange' => $ticker->getSecurityExchange()->getMicAttribute(),
            ]
        );

        $array = $response->json();

        return array_key_exists('data', $array) ? $array['data'] : [];
    }
}

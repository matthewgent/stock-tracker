<?php

namespace App\Services;

use App\Models\Currency;
use App\Models\CurrencyRate;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Support\Facades\Http;
use Exception;
use Illuminate\Support\Facades\Log;

class OpenExchangeRates
{
    private const SERVICE_URL = 'https://openexchangerates.org/api/';

    public static function downloadLatest()
    {
        $response = Http::get(
            self::SERVICE_URL.'latest.json',
            [
                'app_id' => config('services.open_exchange_rates.app_id'),
                'show_alternative' => true
            ]
        );

        $array = $response->json();
        self::storeRates($array);
    }

    public static function downloadHistoric(Carbon $date): void
    {
        $dateString = $date->toDateString();

        $response = Http::get(
            self::SERVICE_URL.'historical/'.$dateString.'.json',
            [
                'app_id' => config('services.open_exchange_rates.app_id'),
                'show_alternative' => true
            ]
        );

        $array = $response->json();
        self::storeRates($array, false);
    }

    public static function downloadRange(Carbon $start, Carbon $end): void
    {
        $dynamicDate = $start->clone();
        while ($dynamicDate->lessThanOrEqualTo($end)) {
            self::downloadHistoric($dynamicDate);
            $dynamicDate->addDay();
        }
    }

    public static function storeRates(array $array, bool $reporting = true): void
    {
        if (array_key_exists('rates', $array)) {
            $currencies = Currency::query()->get();
            $rates = $array['rates'];
            $ratesArray = array();
            $time = new Carbon($array['timestamp']);
            foreach ($currencies as $currency) {
                $code = $currency->code;
                if (array_key_exists($code, $rates)) {
                    $time = new Carbon($array['timestamp']);
                    $time->setTime(0, 0);
                    $timestamp = $time->getTimestamp();
                    $exists = CurrencyRate::query()
                        ->where('currency_id', $currency->id)
                        ->where('time', $timestamp)
                        ->exists();
                    if ($exists === false) {
                        $ratesArray[] = [
                            'currency_id' => $currency->getIdAttribute(),
                            'usd_rate' => $rates[$code],
                            'time' => $timestamp,
                        ];
                    }
                } else if ($reporting === true) {
                    Log::alert(
                        'Unknown currency code found in latest exchange rate download.',
                        ['code' => $code]
                    );
                }
            }

            echo 'Inserting currencies for '.$time->toDateString().'.'.PHP_EOL;
            CurrencyRate::query()->insert($ratesArray);

        } else if ($reporting === true) {
            Log::alert(
                'Rates key not present in response.',
                ['response' => $array]
            );
        }
    }
}

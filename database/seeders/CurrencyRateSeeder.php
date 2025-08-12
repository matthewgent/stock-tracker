<?php

namespace Database\Seeders;

use App\Models\Currency;
use App\Models\CurrencyRate;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class CurrencyRateSeeder extends Seeder
{
    public function run(): void
    {
        echo 'Starting currency rate seeder'.PHP_EOL;
        $currencies = Currency::all();
        $valueScales = [0.0001, 0.01, 0.1, 1, 10, 100, 1000];

        foreach ($currencies as $currency) {
            echo 'Seeding '.$currency->getNameAttribute().' rates'.PHP_EOL;
            $dynamicDate = new Carbon();
            $dynamicDate->setTime(0, 0);
            $end = new Carbon('6 years ago');
            $end->setTime(0, 0);
            $value = $valueScales[array_rand($valueScales)];
            $value *= (random_int(100, 900) / 100);
            $array = [];
            while ($dynamicDate->getTimestamp() > $end->getTimestamp()) {
                $value = $value * (random_int(9900, 10100)) / 10000; // 0.99 to 1.01
                $now = now();
                $rateArray = [
                    'currency_id' => $currency->getIdAttribute(),
                    'usd_rate' => $currency->getCodeAttribute() === 'USD' ? 1 : $value,
                    'time' => $dynamicDate->getTimestamp(),
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
                $array[] = $rateArray;
                if (count($array) === 300) {
                    CurrencyRate::query()->insert($array);
                    $array = [];
                }
                $dynamicDate->subDay();
            }
        }
    }
}

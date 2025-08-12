<?php

namespace Database\Seeders;

use App\Models\Currency;
use App\Models\SecurityExchange;
use App\Models\SovereignState;
use App\Services\MarketStack;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class SecurityExchangeSeeder extends Seeder
{
    public function run(): void
    {
        $exchangesArray = MarketStack::fetchExchanges();

        foreach ($exchangesArray as $exchangeArray) {
            if ($exchangeArray['country_code'] !== 'INDX') {
                $sovereignState = SovereignState::query()
                    ->where('code_2',
                        '=',
                        $exchangeArray['country_code'])
                    ->first();

                if ($sovereignState === null) {
                    Log::error(
                        'Unknown sovereign state.',
                        ['country_code' => $exchangeArray['country_code']]
                    );
                    throw new \Exception('Unknown sovereign state.');
                }

                $currencyCode = $exchangeArray['currency']['code'];

                $currency = Currency::query()
                    ->where('code', '=', $currencyCode)
                    ->first();

                if ($currency === null) {
                    Log::error(
                        'Unknown currency code.',
                        ['currency_code' => $currencyCode]
                    );
                    throw new \Exception('Unknown currency code.');
                }

                $exchangeExists = SecurityExchange::query()
                    ->where('mic', '=', $exchangeArray['mic'])
                    ->exists();

                if ($exchangeExists === false) {
                    $acronym = $exchangeArray['acronym'] ? $exchangeArray['acronym'] : $exchangeArray['mic'];
                    $exchange = new SecurityExchange;
                    $exchange->sovereign_state_id = $sovereignState->getIdAttribute();
                    $exchange->currency_id = $currency->getIdAttribute();
                    $exchange->mic = $exchangeArray['mic'];
                    $exchange->short_name = $acronym;
                    $exchange->name = $exchangeArray['name'];
                    $exchange->save();
                }
            }
        }
    }
}

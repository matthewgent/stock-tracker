<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Seeder;
use const DIRECTORY_SEPARATOR as DS;

class CurrencySeeder extends Seeder
{
    public function run(): void
    {
        $file = fopen(database_path('data'.DS.'currencies.csv'), 'r');
        while ($fields = fgetcsv($file)) {
            $exists = Currency::query()->where('code', $fields[0])->exists();
            if ($exists === false) {
                $currency = new Currency;
                $currency->code = $fields[0];
                $currency->symbol = $fields[1];
                $currency->name = $fields[2];
                $currency->crypto = boolval($fields[3]);
                $currency->save();
            }
        }
    }
}

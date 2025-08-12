<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            CurrencySeeder::class,
            CurrencyRateSeeder::class,
            SovereignStateSeeder::class,
            AdministratorSeeder::class,
            ItemCategorySeeder::class,
            ItemTypeSeeder::class,
            WealthPercentileVersionSeeder::class,
            WealthPercentileSeeder::class,
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\WealthPercentile;
use App\Models\WealthPercentileGroup;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class WealthPercentileSeeder extends Seeder
{
    private static $uk = [
        90 => 3,
        80 => 1200,
        70 => 13700,
        60 => 51200,
        50 => 105000,
        40 => 172500,
        30 => 264000,
        20 => 401700,
        10 => 671200,
    ];

    public function run(): void
    {
        foreach (self::$uk as $key => $value) {
            $percentile = new WealthPercentile();
            $percentile->wealth_percentile_group_id = 1;
            $percentile->percentile = $key;
            $percentile->value = $value;
            $percentile->save();
        }
    }
}

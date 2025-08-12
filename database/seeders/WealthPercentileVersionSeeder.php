<?php

namespace Database\Seeders;

use App\Models\WealthPercentileGroup;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class WealthPercentileVersionSeeder extends Seeder
{
    public function run(): void
    {
        $version = new WealthPercentileGroup();
        $version->sovereign_state_id = 237;
        $version->currency_id = 4;
        $version->version = 1;
        $version->publish_time = new Carbon('2018-03-01');
        $version->source = 'The UK’s wealth distribution and characteristics of highwealth households by the Resolution Foundation';
        $version->source_date = '2020-12-01';
        $version->save();
    }
}

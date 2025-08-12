<?php

namespace Database\Seeders;

use App\Models\SovereignState;
use Illuminate\Database\Seeder;
use const DIRECTORY_SEPARATOR as DS;

class SovereignStateSeeder extends Seeder
{
    public function run(): void
    {
        $file = fopen(database_path('data'.DS.'sovereign-states.csv'), 'r');
        while ($fields = fgetcsv($file)) {
            $sovereignState = new SovereignState;
            $sovereignState->code_2 = $fields[0];
            $sovereignState->code_3 = $fields[1];
            $sovereignState->number = $fields[2];
            $sovereignState->name = $fields[3];
            $sovereignState->short_name = $fields[4];
            $sovereignState->save();
        }
    }
}

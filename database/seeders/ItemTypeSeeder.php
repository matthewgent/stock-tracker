<?php

namespace Database\Seeders;

use App\Models\ItemType;
use Illuminate\Database\Seeder;

class ItemTypeSeeder extends Seeder
{
    private static array $assets = [
        'bankAccount',
        'bond',
        'car',
        'cash',
        'commodity',
        'cryptoCurrency',
        'other',
        'pension',
        'property',
        'stock',
    ];

    private static array $debts = [
        'creditCard',
        'loan',
        'mortgage',
        'tax',
    ];

    public function run(): void
    {
        foreach (self::$assets as $name) {
            $type = new ItemType;
            $type->item_category_id = 1;
            $type->name = $name;
            $type->save();
        }

        foreach (self::$debts as $name) {
            $type = new ItemType;
            $type->item_category_id = 2;
            $type->name = $name;
            $type->save();
        }
    }
}

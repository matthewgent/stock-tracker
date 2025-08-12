<?php

namespace Database\Seeders;

use App\Models\ItemCategory;
use Illuminate\Database\Seeder;

class ItemCategorySeeder extends Seeder
{
    private static array $data = [
        1 => 'asset',
        2 => 'debt',
    ];

    public function run(): void
    {
        foreach (self::$data as $name) {
            $category = new ItemCategory;
            $category->name = $name;
            $category->save();
        }
    }
}

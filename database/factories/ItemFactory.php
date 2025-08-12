<?php

namespace Database\Factories;

use App\Models\Item;
use App\Models\ItemType;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFactory extends Factory
{
    /** @var string */
    protected $model = Item::class;

    public function definition()
    {
        $active = $this->faker->boolean(70);

        return [
            'name' => $this->faker->colorName(),
            'active' => $active,
        ];
    }
}

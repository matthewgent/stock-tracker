<?php

namespace Database\Factories;

use App\Models\Currency;
use App\Models\ItemValue;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemValueFactory extends Factory
{
    /** @var string */
    protected $model = ItemValue::class;

    public function definition()
    {
        return [
            'value' => $this->faker->numberBetween(100000, 1000000) / 100,
            'time' => $this->faker->dateTime(),
        ];
    }
}

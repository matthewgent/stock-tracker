<?php

namespace App\Utilities;

use App\Models\Currency;

class Subscription
{
    public function __construct(
        public float $amount,
        public Currency $currency,
        public string $period,
    ) {}
}

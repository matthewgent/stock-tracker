<?php

namespace App\Services;

use App\Models\Currency;
use App\Utilities\Money;
use App\Utilities\Subscription;
use Exception;
use Illuminate\Support\Facades\Log;

class StripeClient extends \Stripe\StripeClient
{
    private array $config;

    public function __construct(array $config = [])
    {
        $this->config = config('services.stripe');

        $config['api_key'] = $this->config['secret'];
        parent::__construct($config);
    }

    public function getPremiumProduct()
    {
        return $this->products->retrieve($this->config['product_id']);
    }

    public function getPremiumPrice(string $priceId = ''): Subscription
    {
        if ($priceId === '') {
            $priceId = $this->config['price_id'];
        }
        $stripePrice = $this->prices->retrieve($priceId);

        $currency = Currency::getByCode($stripePrice->currency);
        if ($currency === null) {
            $message = 'Stripe returned an unknown price currency code.';
            Log::error($message, [
                'currency_code' => $stripePrice->currency,
            ]);
            throw new Exception($message);
        }

        $amount = $stripePrice->unit_amount;
        if (gettype($amount) !== 'integer') {
            $message = 'Stripe returned an non-integer price amount.';
            Log::error($message, [
                'unit_amount' => $stripePrice->unit_amount,
            ]);
            throw new Exception($message);
        }
        $amount = floatval($amount / 100);

        $period = $stripePrice->recurring->interval;
        if (gettype($period) !== 'string') {
            $message = 'Stripe returned an non-string recurring interval.';
            Log::error($message, [
                'unit_amount' => $stripePrice->recurring->interval,
            ]);
            throw new Exception($message);
        }

        return new Subscription($amount, $currency, $period);
    }
}

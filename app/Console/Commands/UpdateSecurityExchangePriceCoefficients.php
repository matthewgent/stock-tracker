<?php

namespace App\Console\Commands;

use App\Models\SecurityExchange;
use Illuminate\Console\Command;
use const DIRECTORY_SEPARATOR as DS;

class UpdateSecurityExchangePriceCoefficients extends Command
{
    /** @var string */
    protected $signature = 'securities:update-price-coefficients';

    /** @var string */
    protected $description = 'Update price coefficients of security exchanges from CSV file.';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $this->line('Updating price coefficients');
        $file = fopen(database_path('data'.DS.'security-exchange-price-coefficients.csv'), 'r');
        while ($fields = fgetcsv($file)) {
            $mic = $fields[0];
            $priceCoefficient = $fields[1];
            $exchange = SecurityExchange::query()->where('mic', $mic)->first();
            if ($exchange !== null) {
                $exchange->update([
                    'price_coefficient' => $priceCoefficient,
                ]);
                $this->info('Updated exchange '.$mic.' with price coefficient of '.$priceCoefficient.'.');
            }
        }

        return 0;
    }
}

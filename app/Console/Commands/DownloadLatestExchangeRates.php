<?php

namespace App\Console\Commands;

use App\Services\OpenExchangeRates;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DownloadLatestExchangeRates extends Command
{
    /** @var string */
    protected $signature = 'exchange-rates:download-latest';

    /** @var string */
    protected $description = 'Download latest exchange rates and store in the database.';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $this->info('Downloading latest exchange rates.');
        OpenExchangeRates::downloadLatest();

        return 0;
    }
}

<?php

namespace App\Console\Commands;

use App\Services\OpenExchangeRates;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DownloadHistoricExchangeRates extends Command
{
    /** @var string */
    protected $signature = 'exchange-rates:download-historic {date}';

    /** @var string */
    protected $description = 'Download exchange rates for past day and store in the database.';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $date = new Carbon($this->argument('date'));
        $this->info('Downloading exchange rates for '.$date->toDateString().'.');
        OpenExchangeRates::downloadHistoric($date);

        return 0;
    }
}

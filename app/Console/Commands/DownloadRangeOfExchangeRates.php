<?php

namespace App\Console\Commands;

use App\Services\OpenExchangeRates;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Console\Command;

class DownloadRangeOfExchangeRates extends Command
{
    /** @var string */
    protected $signature = 'exchange-rates:download-range {start} {end}';

    /** @var string */
    protected $description = 'Download exchange rates for a range of past dates and store in the database.';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $start = new Carbon($this->argument('start'));
        $end = new Carbon($this->argument('end'));
        $this->info(sprintf(
                'Downloading exchange rates from %s to %s.',
                $start->toDateString(),
                $end->toDateString()
        ));
        OpenExchangeRates::downloadRange($start, $end);

        return 0;
    }
}

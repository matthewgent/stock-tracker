<?php

namespace App\Console;

use App\Console\Commands\DownloadLatestExchangeRates;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /** @var array */
    protected $commands = [
        DownloadLatestExchangeRates::class
    ];

    protected function schedule(Schedule $schedule): void
    {
        $schedule->command(DownloadLatestExchangeRates::class)->dailyAt('02:00');
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}

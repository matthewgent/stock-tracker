<?php

namespace App\Console\Commands;

use Database\Seeders\SecurityExchangeSeeder;
use Illuminate\Console\Command;

class DownloadSecurityExchanges extends Command
{
    protected $signature = 'securities:download-exchanges';

    protected $description = 'Download all the security exchanges and store in the database.';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $seeder = app(SecurityExchangeSeeder::class);
        $seeder->run();

        return 0;
    }
}

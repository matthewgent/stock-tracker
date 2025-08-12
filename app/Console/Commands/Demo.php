<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Demo extends Command
{
    protected $signature = 'demo';

    protected $description = '';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->call('migrate:fresh', ['--seed' => true]);
        $this->call('exchange-rates:download-latest');
        $this->call('securities:download-exchanges');
        $this->call('securities:download-tickers', ['offset' => 0, 'fetches' => 1]);

        return 0;
    }
}

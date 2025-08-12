<?php

namespace App\Console\Commands;

use App\Models\SecurityExchange;
use App\Models\Ticker;
use App\Services\MarketStack;
use Illuminate\Console\Command;

class DownloadTickers extends Command
{
    protected $signature = 'securities:download-tickers {offset=0} {fetches=5}';

    protected $description = 'Download all the tickers and store in the database.';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $offset = intval($this->argument('offset'));
        $fetches = intval($this->argument('fetches'));

        for ($fetch = 0; $fetch < $fetches; $fetch ++) {
            $this->info('Processing tickers '.$offset.' to '.($offset + MarketStack::LIMIT));
            $count = 0;

            $tickersArray = MarketStack::fetchTickers($offset);

            foreach ($tickersArray as $tickerArray) {
                $mic = $tickerArray['stock_exchange']['mic'];
                $symbol = $tickerArray['symbol'];
                $name = $tickerArray['name'];

                $exchange = SecurityExchange::query()
                    ->where('mic', '=', $mic)
                    ->first();

                if ($exchange !== null) {
                    $exchangeId = $exchange->getIdAttribute();

                    $micWithDot = '.'.$mic;
                    $micWithDotLength = strlen($micWithDot);

                    if (substr($symbol, -$micWithDotLength) === $micWithDot) {
                        $symbol = substr($symbol, 0, -$micWithDotLength);
                    }

                    $tickerExists = Ticker::query()
                        ->where('security_exchange_id', '=', $exchangeId)
                        ->where('symbol', '=', $symbol)
                        ->exists();

                    if (
                        $tickerExists === false
                        and $name !== null
                        and $name !== ''
                        and $symbol !== null
                        and $symbol !== ''
                    ) {
                        $ticker = new Ticker;
                        $ticker->security_exchange_id = $exchangeId;
                        $ticker->name = $name;
                        $ticker->symbol = $symbol;
                        $ticker->save();

                        $count ++;
                    }
                }
            }

            $this->info($count.' tickers added.');

            $offset += MarketStack::LIMIT;
        }

        return 0;
    }
}

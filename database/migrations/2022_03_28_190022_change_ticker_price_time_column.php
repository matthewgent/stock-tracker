<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\TickerPrice;

class ChangeTickerPriceTimeColumn extends Migration
{
    public function up()
    {
        TickerPrice::query()->truncate();

        Schema::table('ticker_prices', function (Blueprint $table) {
            $table->dropColumn('time');
        });

        Schema::table('ticker_prices', function (Blueprint $table) {
            $table->bigInteger('time', false, true)->after('ticker_id')->index('time_index');
        });
    }

    public function down()
    {
        Schema::table('ticker_prices', function (Blueprint $table) {
            $table->dropColumn('time');
        });

        Schema::table('ticker_prices', function (Blueprint $table) {
            $table->timestamp('time')->after('ticker_id')->index('time_index');
        });
    }
}

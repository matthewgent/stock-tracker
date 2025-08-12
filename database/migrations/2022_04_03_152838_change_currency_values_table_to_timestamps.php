<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\CurrencyRate;

class ChangeCurrencyValuesTableToTimestamps extends Migration
{
    public function up()
    {
        CurrencyRate::query()->truncate();

        Schema::table('currency_rates', function (Blueprint $table) {
            $table->dropColumn('time');
        });

        Schema::table('currency_rates', function (Blueprint $table) {
            $table->bigInteger('time', false, true)->after('usd_rate')->index('time_index');
        });
    }

    public function down()
    {
        Schema::table('currency_rates', function (Blueprint $table) {
            $table->dropColumn('time');
        });

        Schema::table('currency_rates', function (Blueprint $table) {
            $table->timestamp('time')->after('usd_rate')->index('time_index');
        });
    }
}

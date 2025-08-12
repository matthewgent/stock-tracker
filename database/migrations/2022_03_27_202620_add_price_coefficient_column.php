<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPriceCoefficientColumn extends Migration
{
    public function up()
    {
        Schema::table('security_exchanges', function (Blueprint $table) {
            $table->decimal('price_coefficient', 12, 6)->default(1)->after('name');
        });
    }

    public function down()
    {
        Schema::table('security_exchanges', function (Blueprint $table) {
            $table->dropColumn('price_coefficient');
        });
    }
}

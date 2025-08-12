<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTickerPricesTable extends Migration
{
    public function up(): void
    {
        Schema::create('ticker_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticker_id')->constrained();
            $table->timestamp('time')->index('time_index');
            $table->decimal('price', 12, 4);
            $table->decimal('adjusted_price', 12, 4);
            $table->bigInteger('volume');
            $table->decimal('split_factor', 8, 4);
            $table->decimal('dividend', 12, 4);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ticker_prices');
    }
}

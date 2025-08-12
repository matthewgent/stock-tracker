<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSecurityExchangesTable extends Migration
{
    public function up(): void
    {
        Schema::create('security_exchanges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sovereign_state_id')->constrained();
            $table->foreignId('currency_id')->constrained();
            $table->string('mic', 5);
            $table->string('short_name', 255);
            $table->string('name', 255);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('security_exchanges');
    }
}

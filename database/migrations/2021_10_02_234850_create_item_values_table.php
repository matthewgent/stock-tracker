<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemValuesTable extends Migration
{
    public function up(): void
    {
        Schema::create('item_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained()->onDelete('cascade');
            $table->foreignId('currency_id')->constrained();
            $table->decimal('value', 25, 15);
            $table->timestamp('time');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('item_values');
    }
}

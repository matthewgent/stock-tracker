<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockQuantitiesTable extends Migration
{
    public function up(): void
    {
        Schema::create('stock_quantities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_id')->constrained()->onDelete('cascade');
            $table->decimal('quantity', 12, 2);
            $table->smallInteger('automated_code')->default(0)->unsigned();
            $table->timestamp('time');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_quantities');
    }
}

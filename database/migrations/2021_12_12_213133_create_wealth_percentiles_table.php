<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWealthPercentilesTable extends Migration
{
    public function up(): void
    {
        Schema::create('wealth_percentiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wealth_percentile_group_id')->constrained();
            $table->tinyInteger('percentile');
            $table->decimal('value', 15, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wealth_percentiles');
    }
}

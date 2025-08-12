<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSovereignStatesTable extends Migration
{
    public function up(): void
    {
        Schema::create('sovereign_states', function (Blueprint $table) {
            $table->id();
            $table->char('code_2', 2);
            $table->char('code_3', 3);
            $table->mediumInteger('number');
            $table->string('name', 255);
            $table->string('short_name', 255);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sovereign_states');
    }
}

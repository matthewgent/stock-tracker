<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWealthPercentileGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wealth_percentile_groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sovereign_state_id')->constrained();
            $table->foreignId('currency_id')->constrained();
            $table->tinyInteger('version');
            $table->timestamp('publish_time');
            $table->string('source', 1024);
            $table->date('source_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wealth_percentile_groups');
    }
}

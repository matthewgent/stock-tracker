<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveActiveColumn extends Migration
{
    public function up(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn('active');
        });
    }

    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->boolean('active')->default(true);
        });
    }
}

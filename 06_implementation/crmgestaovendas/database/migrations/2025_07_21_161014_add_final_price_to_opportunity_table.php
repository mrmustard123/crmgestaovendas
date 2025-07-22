<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('opportunity', function (Blueprint $table) {
            $table->decimal('final_price', 12, 2)->default('0.00');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('opportunity', function (Blueprint $table) {
            $table->dropColumn('final_price');
        });
    }
};
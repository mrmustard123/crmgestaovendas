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
        Schema::create('stage', function (Blueprint $table) {
            // `stage_id` tinyint(4) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY
            $table->tinyIncrements('stage_id');

            // `stage_name` varchar(100) NOT NULL
            $table->string('stage_name', 100);

            // `description` varchar(255) DEFAULT NULL
            $table->string('description', 255)->nullable();

            // `stage_order` tinyint(4) DEFAULT '0'
            $table->tinyInteger('stage_order')->default(0);

            // `active` tinyint(4) DEFAULT '1'
            $table->tinyInteger('active')->default(1);

            // `color_hex` varchar(7) DEFAULT '#007bff'
            $table->string('color_hex', 7)->default('#007bff');

            // `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
            // `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stage');
    }
};

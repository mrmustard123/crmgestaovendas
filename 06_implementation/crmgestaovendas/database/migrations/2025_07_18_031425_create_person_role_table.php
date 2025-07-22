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
        Schema::create('person_role', function (Blueprint $table) {
            // `person_role_id` tinyint(4) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY
            $table->tinyIncrements('person_role_id');

            // `role_name` varchar(15) NOT NULL
            $table->string('role_name', 15);

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
        Schema::dropIfExists('person_role');
    }
};

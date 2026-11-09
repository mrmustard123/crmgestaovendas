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

        Schema::create('users_group', function (Blueprint $table) {
            // `user_group_id` tinyint(4) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY
            // tinyIncrements() crea un TINYINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
            $table->tinyIncrements('user_group_id');

            // `group_name` varchar(255) NOT NULL
            $table->string('group_name', 255);

            // `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
            // `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            // Laravel's timestamps() helper maneja esto automáticamente con los valores por defecto de MySQL 8
            $table->timestamps();
        });        
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_group');
    }
};

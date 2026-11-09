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
        
        
        Schema::create('functionality', function (Blueprint $table) {
            // `functionality_id` tinyint(4) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY
            $table->tinyIncrements('functionality_id');

            // `func_name` varchar(255) NOT NULL
            $table->string('func_name', 255);

            // `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
            // `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            // Usamos timestamps() para created_at y updated_at, siguiendo la convención de Laravel.
            // Si el DDL original tenía 'update_at', se ha corregido a 'updated_at' para consistencia.
            $table->timestamps();
        });        
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('functionality');
    }
};

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
        Schema::create('directive', function (Blueprint $table) {
            // `directive_id` tinyint(4) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY
            $table->tinyIncrements('directive_id');

            // `directive` enum('ALLOW','DENY') NOT NULL
            $table->enum('directive', ['ALLOW', 'DENY']);

            // `id_users_group` tinyint(4) unsigned DEFAULT NULL
            $table->tinyInteger('id_users_group')->unsigned()->nullable();

            // `id_functionality` tinyint(4) unsigned DEFAULT NULL
            $table->tinyInteger('id_functionality')->unsigned()->nullable();

            // `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
            // `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            $table->timestamps();

            // Foreign Key Constraints
            // CONSTRAINT `fk_functionality1` FOREIGN KEY (`id_functionality`) REFERENCES `functionality` (`functionality_id`) ON UPDATE CASCADE
            $table->foreign('id_functionality')
                  ->references('functionality_id')
                  ->on('functionality')
                  ->onUpdate('cascade');

            // CONSTRAINT `fk_users_group2` FOREIGN KEY (`id_users_group`) REFERENCES `users_group` (`user_group_id`) ON UPDATE CASCADE
            $table->foreign('id_users_group')
                  ->references('user_group_id')
                  ->on('users_group')
                  ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('directive');
    }
};

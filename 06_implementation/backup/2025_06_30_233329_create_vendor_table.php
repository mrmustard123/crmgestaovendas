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
        Schema::create('vendor', function (Blueprint $table) {
            // `vendor_id` int(11) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY
            $table->increments('vendor_id'); // increments() crea un INT UNSIGNED AUTO_INCREMENT PRIMARY KEY

            // `vendor_name` varchar(200) NOT NULL
            $table->string('vendor_name', 200);

            // `main_phone` varchar(20) DEFAULT NULL
            $table->string('main_phone', 20)->nullable();

            // `main_email` varchar(255) DEFAULT NULL
            $table->string('main_email', 255)->nullable();

            // `address` varchar(100) DEFAULT NULL
            $table->string('address', 100)->nullable();

            // `complement` varchar(255) DEFAULT NULL
            $table->string('complement', 255)->nullable();

            // `neighborhood` varchar(100) DEFAULT NULL
            $table->string('neighborhood', 100)->nullable();

            // `city` varchar(100) DEFAULT NULL
            $table->string('city', 100)->nullable();

            // `state` varchar(2) DEFAULT NULL
            $table->string('state', 2)->nullable();

            // `country` varchar(50) DEFAULT 'Brasil'
            $table->string('country', 50)->default('Brasil');

            // `fk_user` int(11) unsigned DEFAULT NULL
            $table->integer('fk_user')->unsigned()->nullable();

            // `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
            // `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            $table->timestamps();

            // Foreign Key Constraint
            // CONSTRAINT `fk_user1` FOREIGN KEY (`fk_user`) REFERENCES `users` (`user_id`) ON UPDATE CASCADE
            $table->foreign('fk_user')
                  ->references('user_id')
                  ->on('users')
                  ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor');
    }
};

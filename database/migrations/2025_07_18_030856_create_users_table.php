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
       Schema::create('users', function (Blueprint $table) {
            // `user_id` int(11) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY
            $table->increments('user_id'); // increments() crea un INT UNSIGNED AUTO_INCREMENT PRIMARY KEY

            // `username` varchar(191) NOT NULL UNIQUE
            $table->string('username', 191)->unique();

            // `password` varchar(191) NOT NULL
            $table->string('password', 191);

            // `email` varchar(191) DEFAULT NULL UNIQUE
            $table->string('email', 191)->nullable()->unique();

            // `email_verified_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
            // Laravel's timestamp() method for specific timestamp columns
            // Note: ON UPDATE CURRENT_TIMESTAMP is a MySQL specific default,
            // Laravel's ORM handles this via model events usually.
            $table->timestamp('email_verified_at')->nullable();

            // `fk_users_group` tinyint(4) unsigned DEFAULT NULL
            $table->tinyInteger('fk_users_group')->unsigned()->nullable();

            // `remember_token` varchar(100) DEFAULT NULL
            $table->string('remember_token', 100)->nullable();

            // `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            // `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            // timestamps() helper handles these two columns
            $table->timestamps();

            // Foreign Key Constraint
            // CONSTRAINT `fk_users_group` FOREIGN KEY (`fk_users_group`) REFERENCES `users_group` (`user_group_id`) ON UPDATE CASCADE
            $table->foreign('fk_users_group')
                  ->references('user_group_id')
                  ->on('users_group')
                  ->onUpdate('cascade'); // onDelete('set null') si no quieres borrar usuarios al borrar un grupo
        });
        
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

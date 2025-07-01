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
        Schema::create('company', function (Blueprint $table) {
            // `company_id` int(11) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY
            $table->increments('company_id'); // increments() crea un INT UNSIGNED AUTO_INCREMENT PRIMARY KEY

            // `social_reason` varchar(200) NOT NULL
            $table->string('social_reason', 200);

            // `fantasy_name` varchar(200) DEFAULT NULL
            $table->string('fantasy_name', 200)->nullable();

            // `cnpj` varchar(18) DEFAULT NULL
            $table->string('cnpj', 18)->nullable();

            // `inscricao_estadual` varchar(20) DEFAULT NULL
            $table->string('inscricao_estadual', 20)->nullable();

            // `inscricao_municipal` varchar(20) DEFAULT NULL
            $table->string('inscricao_municipal', 20)->nullable();

            // `cep` varchar(9) DEFAULT NULL
            $table->string('cep', 9)->nullable();

            // `address` varchar(255) DEFAULT NULL
            $table->string('address', 255)->nullable();

            // `complement` varchar(100) DEFAULT NULL
            $table->string('complement', 100)->nullable();

            // `neighborhood` varchar(100) DEFAULT NULL
            $table->string('neighborhood', 100)->nullable();

            // `city` varchar(100) DEFAULT NULL
            $table->string('city', 100)->nullable();

            // `state` varchar(2) DEFAULT NULL
            $table->string('state', 2)->nullable();

            // `country` varchar(50) DEFAULT 'Brasil'
            $table->string('country', 50)->default('Brasil');

            // `main_phone` varchar(20) DEFAULT NULL
            $table->string('main_phone', 20)->nullable();

            // `main_email` varchar(100) DEFAULT NULL
            $table->string('main_email', 100)->nullable();

            // `website` varchar(255) DEFAULT NULL
            $table->string('website', 255)->nullable();

            // `company_size` enum('Big','Medium','Small','Tiny') DEFAULT NULL
            $table->enum('company_size', ['Big', 'Medium', 'Small', 'Tiny'])->nullable();

            // `status` enum('active','unactive') DEFAULT NULL
            $table->enum('status', ['active', 'unactive'])->nullable();

            // `comments` varchar(255) DEFAULT NULL
            $table->string('comments', 255)->nullable();

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
        Schema::dropIfExists('company');
    }
};

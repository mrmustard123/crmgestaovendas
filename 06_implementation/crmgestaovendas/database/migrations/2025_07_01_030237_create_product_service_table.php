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
        Schema::create('product_service', function (Blueprint $table) {
            // `product_service_id` int(11) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY
            $table->increments('product_service_id'); // increments() crea un INT UNSIGNED AUTO_INCREMENT PRIMARY KEY

            // `product_name` varchar(255) NOT NULL
            $table->string('product_name', 255);

            // `description` text DEFAULT NULL
            $table->text('description')->nullable();

            // `type` enum('product','service') DEFAULT NULL
            $table->enum('type', ['product', 'service'])->nullable();

            // `category` varchar(100) DEFAULT NULL
            $table->string('category', 100)->nullable();

            // `unit_price` decimal(15,2) DEFAULT NULL
            $table->decimal('unit_price', 15, 2)->nullable();

            // `unit` varchar(50) DEFAULT NULL
            $table->string('unit', 50)->nullable();

            // `tax_rate` decimal(15,2) DEFAULT NULL
            $table->decimal('tax_rate', 15, 2)->nullable();

            // `is_active` tinyint(4) NOT NULL DEFAULT '1'
            $table->tinyInteger('is_active')->default(1); // tinyInteger() por defecto es signed, si necesitas unsigned añade ->unsigned()

            // `sku` varchar(12) DEFAULT NULL
            $table->string('sku', 12)->nullable();

            // `is_tangible` tinyint(4) NOT NULL DEFAULT '1'
            $table->tinyInteger('is_tangible')->default(1); // tinyInteger() por defecto es signed, si necesitas unsigned añade ->unsigned()

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
        Schema::dropIfExists('product_service');
    }
};

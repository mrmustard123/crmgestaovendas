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
        Schema::create('system_config', function (Blueprint $table) {
            // `config_id` int(11) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY
            $table->increments('config_id'); // increments() crea un INT UNSIGNED AUTO_INCREMENT PRIMARY KEY

            // `config_key` varchar(100) NOT NULL UNIQUE
            $table->string('config_key', 100)->unique();

            // `config_value` text DEFAULT NULL
            $table->text('config_value')->nullable();

            // `description` text DEFAULT NULL
            $table->text('description')->nullable();

            // `type` enum('string','number','boolean','json') DEFAULT 'string'
            $table->enum('type', ['string', 'number', 'boolean', 'json'])->default('string');

            // `category` varchar(50) DEFAULT NULL
            $table->string('category', 50)->nullable();

            // `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            // Nota: Esta tabla no tiene `created_at` en tu DDL. Solo `updated_at`.
            // Laravel's `timestamps()` crea ambos. Si solo quieres `updated_at`,
            // debes definirlo manualmente.
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            // `KEY idx_key` (`config_key`)
            // El índice único en `config_key` ya cubre esto, no es necesario un KEY adicional.

            // `KEY idx_category` (`category`)
            $table->index('category', 'idx_category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_config');
    }
};

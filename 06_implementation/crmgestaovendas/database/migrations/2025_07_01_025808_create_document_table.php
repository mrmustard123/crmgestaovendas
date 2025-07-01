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
        Schema::create('document', function (Blueprint $table) {
            // `document_id` int(11) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY
            $table->increments('document_id'); // increments() crea un INT UNSIGNED AUTO_INCREMENT PRIMARY KEY

            // `doc_name` varchar(200) NOT NULL
            $table->string('doc_name', 200);

            // `filename` varchar(255) NOT NULL
            $table->string('filename', 255);

            // `file_type` varchar(10) DEFAULT NULL
            $table->string('file_type', 10)->nullable();

            // `size_bytes` bigint(20) DEFAULT NULL
            $table->bigInteger('size_bytes')->nullable();

            // `file_path` varchar(500) DEFAULT NULL
            $table->string('file_path', 500)->nullable();

            // `description` text
            $table->text('description')->nullable();

            // `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
            // `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            $table->timestamps();

            // `fk_opportunity` int(11) unsigned DEFAULT NULL
            $table->integer('fk_opportunity')->unsigned()->nullable();

            // Foreign Key Constraint
            // CONSTRAINT `fk_opportunity1` FOREIGN KEY (`fk_opportunity`) REFERENCES `opportunity` (`opportunity_id`) ON UPDATE CASCADE
            $table->foreign('fk_opportunity')
                  ->references('opportunity_id')
                  ->on('opportunity')
                  ->onUpdate('cascade');
                  // Consider onDelete('set null') si una oportunidad puede ser eliminada sin eliminar el documento.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document');
    }
};

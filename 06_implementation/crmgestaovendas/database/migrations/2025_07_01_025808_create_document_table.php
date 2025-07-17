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
           // `file_name` varchar(255) NOT NULL
            $table->string('file_name', 255);

            $table->string('mime_type', 100)->nullable();
            $table->unsignedBigInteger('file_size')->nullable();
            $table->string('file_path', 255);
            // `description` text
            $table->text('description')->nullable();
            $table->dateTime('uploaded_at')->nullable();

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
                  //->onDelete('cascade');            
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

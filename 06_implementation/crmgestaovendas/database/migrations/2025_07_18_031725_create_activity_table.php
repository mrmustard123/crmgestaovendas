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
       Schema::create('activity', function (Blueprint $table) {
            // `activity_id` int(11) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY
            $table->increments('activity_id'); // increments() crea un INT UNSIGNED AUTO_INCREMENT PRIMARY KEY

            // `titulo` varchar(200) NOT NULL
            $table->string('titulo', 200);

            // `description` text DEFAULT NULL
            $table->text('description')->nullable();

            // `activity_date` date NOT NULL
            $table->date('activity_date');

            // `duration_min` tinyint(4) DEFAULT NULL
            $table->tinyInteger('duration_min')->nullable();

            // `status` enum('scheduled','performed','canceled','resheduled') DEFAULT 'scheduled'
            $table->enum('status', ['scheduled', 'performed', 'canceled', 'resheduled'])->default('scheduled');

            // `result` enum('positive','negative','neutral') DEFAULT NULL
            $table->enum('result', ['positive', 'negative', 'neutral'])->nullable();

            // `comments` text DEFAULT NULL
            $table->text('comments')->nullable();

            // `fk_opportunity` int(11) unsigned DEFAULT NULL
            $table->integer('fk_opportunity')->unsigned()->nullable();

            // `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
            // `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            $table->timestamps();

            // Foreign Key Constraint
            // CONSTRAINT `fk_opportunity` FOREIGN KEY (`fk_opportunity`) REFERENCES `opportunity` (`opportunity_id`) ON UPDATE CASCADE
            $table->foreign('fk_opportunity')
                  ->references('opportunity_id')
                  ->on('opportunity')
                  ->onUpdate('cascade');
                  // Consider onDelete('set null') si una oportunidad puede ser eliminada sin eliminar sus actividades.
                  // O onDelete('cascade') si la actividad no tiene sentido sin la oportunidad.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity');
    }
};

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
        Schema::create('stage_history', function (Blueprint $table) {
            // `won_lost` enum('won','lost') DEFAULT NULL
            $table->enum('won_lost', ['won', 'lost'])->nullable();

            // `stage_hist_date` date NOT NULL
            $table->date('stage_hist_date');

            // `comments` varchar(255) DEFAULT NULL
            $table->string('comments', 255)->nullable();

            // `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
            // `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            $table->timestamps();

            // `fk_opportunity` int(11) unsigned NOT NULL DEFAULT '0'
            // Nota: DEFAULT '0' en FKs no es común y puede causar problemas si no hay un ID 0 en la tabla padre.
            // Si es una FK, generalmente no tiene un DEFAULT y su valor es NULL si es opcional, o NOT NULL si es mandatorio.
            // Lo he dejado como NOT NULL sin default '0' para seguir la convención de FKs en Laravel.
            $table->integer('fk_opportunity')->unsigned();

            // `fk_stage` tinyint(4) unsigned NOT NULL DEFAULT '0'
            // Nota: Similar a fk_opportunity, lo he dejado como NOT NULL sin default '0'.
            $table->tinyInteger('fk_stage')->unsigned();

            // PRIMARY KEY (`fk_opportunity`,`fk_stage`)
            $table->primary(['fk_opportunity', 'fk_stage']);

            // UNIQUE KEY `idx_stage_hist1` (`fk_opportunity`,`fk_stage`) USING BTREE
            // La clave primaria ya es única, no se necesita un UNIQUE KEY separado para las mismas columnas.

            // Foreign Key Constraints
            // CONSTRAINT `fk_oportunity4` FOREIGN KEY (`fk_opportunity`) REFERENCES `opportunity` (`opportunity_id`) ON UPDATE CASCADE
            // Nota: Corregido el typo en 'oportunity' a 'opportunity' para la referencia.
            $table->foreign('fk_opportunity')
                  ->references('opportunity_id')
                  ->on('opportunity')
                  ->onUpdate('cascade')
                  ->onDelete('cascade'); // Consider onDelete('cascade') ya que es una tabla de historial.

            // CONSTRAINT `fk_stage1` FOREIGN KEY (`fk_stage`) REFERENCES `stage` (`stage_id`) ON UPDATE CASCADE
            $table->foreign('fk_stage')
                  ->references('stage_id')
                  ->on('stage')
                  ->onUpdate('cascade')
                  ->onDelete('cascade'); // Consider onDelete('cascade') si la etapa es eliminada.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stage_history');
    }
};

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
                   // Columna autoincremental como primary key
                   $table->unsignedInteger('stage_hist_id')->autoIncrement();

                   /// `won_lost` enum('won','lost') DEFAULT NULL
                   $table->enum('won_lost', ['won', 'lost'])->nullable();
                   // `stage_hist_date` date NOT NULL
                   $table->date('stage_hist_date');
                    // `comments` varchar(255) DEFAULT NULL
                   $table->string('comments', 255)->nullable();

                   // `created_at` timestamp NULL 
                   // `updated_at` timestamp NULL
                   $table->timestamps();


                   // Claves foráneas
                   $table->unsignedInteger('fk_opportunity');
                   $table->unsignedTinyInteger('fk_stage');

                   // Índices y restricciones
                   $table->unique(['fk_opportunity', 'fk_stage'], 'stage_history_unique');
                   $table->index('fk_stage', 'fk_stage4');

                   // Foreign keys
                   $table->foreign('fk_opportunity', 'fk_opportunity4')
                         ->references('opportunity_id')
                         ->on('opportunity')
                         ->onDelete('restrict')
                         ->onUpdate('cascade');

                   $table->foreign('fk_stage', 'fk_stage4')
                         ->references('stage_id')
                         ->on('stage')
                         ->onDelete('restrict')
                         ->onUpdate('cascade');

                   /*/ Configuración del motor y collation
                   $table->engine = 'InnoDB';
                   $table->charset = 'utf8mb4';
                   $table->collation = 'utf8mb4_0900_ai_ci';*/
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

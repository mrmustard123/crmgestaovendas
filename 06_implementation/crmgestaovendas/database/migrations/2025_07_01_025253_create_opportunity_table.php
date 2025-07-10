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
        Schema::create('opportunity', function (Blueprint $table) {
            // `opportunity_id` int(11) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY
            $table->increments('opportunity_id'); // increments() crea un INT UNSIGNED AUTO_INCREMENT PRIMARY KEY

            // `opportunity_name` varchar(200) NOT NULL
            $table->string('opportunity_name', 200);

            // `description` text
            $table->text('description')->nullable();

            // `estimated_sale` decimal(12,2) DEFAULT '0.00'
            $table->decimal('estimated_sale', 12, 2)->default(0.00);

            // `expected_closing_date` date DEFAULT NULL
            $table->date('expected_closing_date')->nullable();

            // `currency` varchar(3) DEFAULT NULL
            $table->string('currency', 3)->nullable();

            // `open_date` date DEFAULT NULL
            $table->date('open_date')->nullable();

            // `lead_origin_id` tinyint(4) unsigned DEFAULT NULL (FK to lead_origin)
            $table->tinyInteger('lead_origin_id')->unsigned()->nullable();

            // `priority` enum('Low','Medium','High','Critical') NOT NULL DEFAULT 'Low'
            $table->enum('priority', ['Low', 'Medium', 'High', 'Critical'])->default('Low');

            // `fk_op_status_id` tinyint(4) unsigned DEFAULT NULL (FK to opportunity_status)
            $table->tinyInteger('fk_op_status_id')->unsigned()->nullable();
            
            // `fk_stage_id` tinyint(4) unsigned DEFAULT NULL (FK to opportunity_stages)
            $table->tinyInteger('fk_stage_id')->unsigned()->nullable();            

            // `fk_vendor` int(11) unsigned DEFAULT NULL (FK to vendor)
            $table->integer('fk_vendor')->unsigned()->nullable();

            // `fk_person` int(11) unsigned DEFAULT NULL (FK to person)
            $table->integer('fk_person')->unsigned()->nullable();

            // `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            // `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            $table->timestamps();

            // Foreign Key Constraints
            // CONSTRAINT `fk_lead_origin1` FOREIGN KEY (`lead_origin_id`) REFERENCES `lead_origin` (`lead_origin_id`) ON UPDATE CASCADE
            $table->foreign('lead_origin_id')
                  ->references('lead_origin_id')
                  ->on('lead_origin')
                  ->onUpdate('cascade');
                  // Consider onDelete('set null') si un origen de lead puede ser eliminado sin afectar las oportunidades.

            // CONSTRAINT `fk_op_status1` FOREIGN KEY (`fk_op_status_id`) REFERENCES `opportunity_status` (`opportunity_status_id`) ON UPDATE CASCADE
            $table->foreign('fk_op_status_id')
                  ->references('opportunity_status_id')
                  ->on('opportunity_status')
                  ->onUpdate('cascade');
                  // Consider onDelete('restrict') o 'set null' dependiendo de la lógica de negocio si un estado de oportunidad se elimina.

            
            $table->foreign('fk_stage_id')
                  ->references('stage_id')
                  ->on('stage')
                  ->onUpdate('cascade');
                  // Consider onDelete('restrict') o 'set null' dependiendo de la lógica de negocio si un estado de oportunidad se elimina.
            
            
            
            // CONSTRAINT `fk_person1` FOREIGN KEY (`fk_person`) REFERENCES `person` (`person_id`) ON UPDATE CASCADE
            $table->foreign('fk_person')
                  ->references('person_id')
                  ->on('person')
                  ->onUpdate('cascade');
                  // Consider onDelete('set null') si una persona puede ser eliminada sin eliminar la oportunidad.

            // CONSTRAINT `fk_vendor1` FOREIGN KEY (`fk_vendor`) REFERENCES `vendor` (`vendor_id`) ON UPDATE CASCADE
            $table->foreign('fk_vendor')
                  ->references('vendor_id')
                  ->on('vendor')
                  ->onUpdate('cascade');
                  // Consider onDelete('set null') si un vendedor puede ser eliminado sin eliminar la oportunidad.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('opportunity');
    }
};

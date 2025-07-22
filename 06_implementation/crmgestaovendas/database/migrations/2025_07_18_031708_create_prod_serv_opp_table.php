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
        Schema::create('prod_serv_opp', function (Blueprint $table) {
            // `opportunity_id` int(11) unsigned NOT NULL (corrected from 'oportunity_id')
            $table->integer('opportunity_id')->unsigned();

            // `product_service_id` int(11) unsigned NOT NULL
            $table->integer('product_service_id')->unsigned();

            // PRIMARY KEY (`opportunity_id`,`product_service_id`)
            $table->primary(['opportunity_id', 'product_service_id']);

            // `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
            // `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            $table->timestamps();

            // Foreign Key Constraints
            // CONSTRAINT `fk:_opportunity5` FOREIGN KEY (`opportunity_id`) REFERENCES `opportunity` (`opportunity_id`) ON UPDATE CASCADE
            $table->foreign('opportunity_id')
                  ->references('opportunity_id')
                  ->on('opportunity')
                  ->onUpdate('cascade')
                  ->onDelete('cascade'); // Generalmente para tablas pivote, un delete en el padre borra la relación

            // CONSTRAINT `fk_prod_serv1` FOREIGN KEY (`product_service_id`) REFERENCES `product_service` (`product_service_id`) ON UPDATE CASCADE
            $table->foreign('product_service_id')
                  ->references('product_service_id')
                  ->on('product_service')
                  ->onUpdate('cascade')
                  ->onDelete('cascade'); // Generalmente para tablas pivote, un delete en el padre borra la relación
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prod_serv_opp');
    }
};

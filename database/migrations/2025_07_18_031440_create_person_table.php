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

        Schema::create('person', function (Blueprint $table) {
            // `person_id` int(11) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY
            $table->increments('person_id'); // increments() crea un INT UNSIGNED AUTO_INCREMENT PRIMARY KEY

            // `person_name` varchar(255) NOT NULL
            $table->string('person_name', 255);

            // `address` varchar(255) DEFAULT NULL
            $table->string('address', 255)->nullable();

            // `complement` varchar(100) DEFAULT NULL
            $table->string('complement', 100)->nullable();

            // `main_phone` varchar(20) DEFAULT NULL
            $table->string('main_phone', 20)->nullable();

            // `main_email` varchar(255) DEFAULT NULL
            $table->string('main_email', 255)->nullable();

            // `rg` varchar(20) DEFAULT NULL
            $table->string('rg', 20)->nullable();

            // `cpf` varchar(14) DEFAULT NULL
            $table->string('cpf', 14)->nullable();

            // `cep` varchar(9) DEFAULT NULL
            $table->string('cep', 9)->nullable();

            // `neighborhood` varchar(100) DEFAULT NULL
            $table->string('neighborhood', 100)->nullable();

            // `city` varchar(100) DEFAULT NULL
            $table->string('city', 100)->nullable();

            // `state` varchar(2) DEFAULT NULL
            $table->string('state', 2)->nullable();

            // `country` varchar(50) DEFAULT 'Brasil'
            $table->string('country', 50)->default('Brasil');

            // `birthdate` date DEFAULT NULL
            $table->date('birthdate')->nullable();

            // `sex` enum('MALE','FEMALE','OTHER') DEFAULT NULL
            $table->enum('sex', ['MALE', 'FEMALE', 'OTHER'])->nullable();

            // `marital_status` enum('single','married','divorced','widow','stable union') DEFAULT NULL
            $table->enum('marital_status', ['single', 'married', 'divorced', 'widow', 'stable union'])->nullable();

            // `company_dept` varchar(100) DEFAULT NULL
            $table->string('company_dept', 100)->nullable();

            // `job_position` varchar(100) DEFAULT NULL
            $table->string('job_position', 100)->nullable();

            // `fk_person_role` tinyint(4) unsigned DEFAULT NULL
            $table->tinyInteger('fk_person_role')->unsigned()->nullable();

            // `fk_company` int(11) unsigned DEFAULT NULL
            $table->integer('fk_company')->unsigned()->nullable();

            // `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
            // `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            $table->timestamps();

            // Foreign Key Constraints
            // CONSTRAINT `fk_company1` FOREIGN KEY (`fk_company`) REFERENCES `company` (`company_id`) ON UPDATE CASCADE
            $table->foreign('fk_company')
                  ->references('company_id')
                  ->on('company')
                  ->onUpdate('cascade');
                  // Consider onDelete('set null') si una compañía puede ser eliminada sin eliminar a la persona.

            // CONSTRAINT `fk_person_role1` FOREIGN KEY (`fk_person_role`) REFERENCES `person_role` (`person_role_id`) ON UPDATE CASCADE
            $table->foreign('fk_person_role')
                  ->references('person_role_id')
                  ->on('person_role')
                  ->onUpdate('cascade');
                  // Consider onDelete('set null') si un rol de persona puede ser eliminado sin eliminar a la persona.
        });        
                        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('person');
    }
};

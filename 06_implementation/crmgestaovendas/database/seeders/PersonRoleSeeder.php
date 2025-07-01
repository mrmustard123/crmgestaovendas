<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PersonRole; // Asegúrate de importar tu modelo PersonRol

class PersonRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       // Puedes borrar los datos existentes antes de insertar si lo deseas (útil en desarrollo)
        // PersonRole::truncate(); // Esto eliminará todos los registros antes de insertar
/*
        PersonRole::create([
            'person_role_id' => 1,
            'role_name' => 'Lead',
            // created_at y updated_at se gestionan automáticamente por Eloquent si no se especifican
        ]);

        PersonRole::create([
            'person_role_id' => 2,
            'role_name' => 'Cliente',
        ]);

        PersonRole::create([
            'person_role_id' => 3,
            'role_name' => 'Contato',
        ]);
*/
        // O una forma más compacta con insert (no gestiona timestamps automáticamente):
        // DB::table('person_role')->insert([
        //     ['person_role_id' => 1, 'role_name' => 'Lead', 'created_at' => now(), 'updated_at' => now()],
        //     ['person_role_id' => 2, 'role_name' => 'Cliente', 'created_at' => now(), 'updated_at' => now()],
        //     ['person_role_id' => 3, 'role_name' => 'Contato', 'created_at' => now(), 'updated_at' => now()],
        // ]);
        
        
        // No pasamos 'person_role_id' para que se autoincremente
        PersonRole::create(['role_name' => 'Lead']);
        PersonRole::create(['role_name' => 'Cliente']);
        PersonRole::create(['role_name' => 'Contato']);            
        
    
    }
}

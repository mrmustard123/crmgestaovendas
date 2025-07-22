<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\UsersGroup;

class UsersGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * php artisan db:seed --class=UsersGroup
     */
    public function run(): void
    {
       // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);        
        
        
        UsersGroup::create(['user_group_id' => '1', 'group_name' => 'Administradores']);
        UsersGroup::create(['user_group_id' => '2', 'group_name' => 'Gerentes']);
        UsersGroup::create(['user_group_id' => '3', 'group_name' => 'Vendedores']);  
        UsersGroup::create(['user_group_id' => '4', 'group_name' => 'Restrito']);
    }
}

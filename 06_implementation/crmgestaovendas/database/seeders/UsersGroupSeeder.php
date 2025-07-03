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
        UsersGroup::create(['group_name' => 'Administradores']);
        UsersGroup::create(['group_name' => 'Gerentes']);
        UsersGroup::create(['group_name' => 'Vendedores']);      
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create(['fk_users_group' => '1',
            'username' => 'Admin',
            'password' => Hash::make('administrador'),//administrador
            'email' => 'admin@example.com',            
            ]);
    }
}

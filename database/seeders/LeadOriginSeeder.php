<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use \App\Models\LeadOrigin;

class LeadOriginSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LeadOrigin::create(['origin' => 'QR']);
        LeadOrigin::create(['origin' => 'Site']);
        LeadOrigin::create(['origin' => 'Manual']);         
        LeadOrigin::create(['origin' => 'Outro']);         
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\OpportunityStatus;

class OpportunityStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        OpportunityStatus::create(['status' => 'Aberto']);
        OpportunityStatus::create(['status' => 'Ganho']);
        OpportunityStatus::create(['status' => 'Perdido']);            
        OpportunityStatus::create(['status' => 'Cancelado']);            
        
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Stage;

class StageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {        
        //Stage::truncate();

        $stages = [
            ['stage_id' => 1, 'stage_name' => 'Apresentação', 'description' => '', 'stage_order' => 0, 'active' => 1, 'color_hex' => '#d1fae5'],
            ['stage_id' => 2, 'stage_name' => 'Proposta', 'description' => '', 'stage_order' => 1, 'active' => 1, 'color_hex' => '#fef3c7'],
            ['stage_id' => 3, 'stage_name' => 'Negociação', 'description' => '', 'stage_order' => 2, 'active' => 1, 'color_hex' => '#ede9fe'],
         ];

        foreach ($stages as $stage) {
            Stage::create($stage);
        }       
        
        
    }
}

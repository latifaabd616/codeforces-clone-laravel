<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LevelsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
       // \App\Models\Level::factory()->count(5)->create();
        $levels = [
            [
                'Title' => 'المبتدئ',
                'RequiredXP' => 0,
                'Icon' => 'l.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
             [
                'Title' => 'متوسط',
                'RequiredXP' => 0,
                'Icon' => 'l.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        
          
          
        ];

        DB::table('levels')->insert($levels);
    }
    
}

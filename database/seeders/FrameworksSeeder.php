<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FrameworksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
          $frameworks = [
            [
                'title' => 'Laravel',
                'Icon' => 'l.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'React',
                'Icon' => 'l.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Vue.js',
                'Icon' => 'l.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Django',
                'Icon' => 'l.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Spring',
                'Icon' => 'l.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('frameworks')->insert($frameworks);
    }
    
}

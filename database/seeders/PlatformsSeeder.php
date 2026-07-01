<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlatformsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $platforms = [
            [
                'Title' => 'Web',
                'Icon' => 'l.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'Title' => 'Mobile',
                'Icon' => 'l.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'Title' => 'Desktop',
                'Icon' => 'l.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'Title' => 'Cloud',
                'Icon' => 'l.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('platforms')->insert($platforms);
    }
    
}

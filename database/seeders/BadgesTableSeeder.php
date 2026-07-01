<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BadgesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
           DB::table('badges')->insert([
            [
                'Title' => 'المساهم النشيط',
                'Criteria' => 'الحصول على 10 نقاط في النظام',
                'Icon' => 'l.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'Title' => 'الخبير المتميز',
                'Criteria' => 'الحصول على 50 نقطة في النظام',
                'Icon' => 'l2.png',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
    
}

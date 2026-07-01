<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProgrammingLanguagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
            $languages = [
            [
                'Title' => 'JavaScript',
                'Icon' => 'l.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'Title' => 'Python',
                'Icon' => 'l.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'Title' => 'Java',
                'Icon' => 'l.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'Title' => 'PHP',
                'Icon' => 'l.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'Title' => 'C++',
                'Icon' => 'l.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // يمكنك إضافة المزيد من اللغات حسب الحاجة
        ];

        DB::table('Programming_languages')->insert($languages);
    }
 }


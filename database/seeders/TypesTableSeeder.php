<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
                $types = [
            [
                'Title' => 'Type A',
                'Criteria' => 'Criteria for Type A',
                'Icon' => 'l.png',
                'GrantDate' => '2023-01-15',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'Title' => 'Type B',
                'Criteria' => 'Criteria for Type B',
                'Icon' => 'l.png',
                'GrantDate' => '2023-02-20',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'Title' => 'Type C',
                'Criteria' => 'Criteria for Type C',
                'Icon' => 'l.png',
                'GrantDate' => '2023-03-10',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // يمكنك إضافة المزيد من السجلات حسب الحاجة
        ];

        DB::table('types')->insert($types);
    }
    
}

<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);




    /*      \App\Models\Level::create([
        'Title' => 'مبتدأ',
        'RequiredXP' => 0,
        'Icon' => 'مسار/إلى/الأيقونة',
    ]); */
        $this->call([
            LevelsTableSeeder::class,
            BadgesTableSeeder::class,
            ProgrammingLanguagesSeeder::class,
            FrameworksSeeder::class,
            PlatformsSeeder::class,
            ProjectsSeeder::class, 
            TypesTableSeeder::class,
            ProjectTechnologiesSeeder::class,
        ]);
    }

}

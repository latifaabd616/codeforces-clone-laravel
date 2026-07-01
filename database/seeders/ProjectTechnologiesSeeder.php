<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProjectTechnologiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
            $projects = DB::table('projects')->pluck('id');
        $languages = DB::table('Programming_languages')->pluck('id');
        $frameworks = DB::table('frameworks')->pluck('id');
        $platforms = DB::table('platforms')->pluck('id');

        $projectTechnologies = [
            // المشروع 1: موقع تجارة إلكترونية
            [
                'project_id' => $projects[0],
                'ProgrammingLanguage_id' => $languages[1], // Python
                'framework_id' => $frameworks[3], // Django
                'platform_id' => $platforms[0], // Web
                'ExtraXP' => 50,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'project_id' => $projects[0],
                'ProgrammingLanguage_id' => $languages[0], // JavaScript
                'framework_id' => $frameworks[1], // React
                'platform_id' => $platforms[0], // Web
                'ExtraXP' => 30,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // المشروع 2: تطبيق قائمة المهام
            [
                'project_id' => $projects[1],
                'ProgrammingLanguage_id' => $languages[0], // JavaScript
                'framework_id' => $frameworks[2], // Vue.js
                'platform_id' => $platforms[1], // Mobile
                'ExtraXP' => 20,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // المشروع 3: نظام إدارة المحتوى
            [
                'project_id' => $projects[2],
                'ProgrammingLanguage_id' => $languages[3], // PHP
                'framework_id' => $frameworks[0], // Laravel
                'platform_id' => $platforms[0], // Web
                'ExtraXP' => 100,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // المشروع 4: محول العملات
            [
                'project_id' => $projects[3],
                'ProgrammingLanguage_id' => $languages[0], // JavaScript
                'framework_id' => null, // بدون إطار عمل
                'platform_id' => $platforms[0], // Web
                'ExtraXP' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // المشروع 5: منصة مدونة
            [
                'project_id' => $projects[4],
                'ProgrammingLanguage_id' => $languages[2], // Java
                'framework_id' => $frameworks[4], // Spring
                'platform_id' => $platforms[0], // Web
                'ExtraXP' => 80,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'project_id' => $projects[4],
                'ProgrammingLanguage_id' => $languages[0], // JavaScript
                'framework_id' => $frameworks[1], // React
                'platform_id' => $platforms[0], // Web
                'ExtraXP' => 40,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('project_technologies')->insert($projectTechnologies);
    }
    
}

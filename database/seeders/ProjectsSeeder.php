<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProjectsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
            $projects = [
            [
                'Title' => 'بناء موقع تجارة إلكترونية',
                'ShortDescription' => 'تصميم وتطوير موقع أساسي للتجارة الإلكترونية',
                'LongDescription' => 'هذا المشروع يتطلب بناء موقع تجارة إلكترونية أساسي مع سلة تسوق، صفحة منتجات، ونظام دفع بسيط. ستحتاج إلى استخدام HTML, CSS, JavaScript ولغة برمجة من اختيارك للوظائف الخلفية.',
                'TimeLimit' => 180,
                'Difficulty' => 'متوسط',
                'XPReward' => 500,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'Title' => 'تطبيق قائمة المهام',
                'ShortDescription' => 'إنشاء تطبيق لإدارة المهام اليومية',
                'LongDescription' => 'قم ببناء تطبيق قائمة مهام يتضمن إضافة مهام، تعديلها، حذفها، ووضع علامة عليها كمكتملة. يمكنك استخدام أي إطار عمل أو لغة برمجة تفضلها.',
                'TimeLimit' => 60,
                'Difficulty' => 'سهل',
                'XPReward' => 200,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'Title' => 'نظام إدارة المحتوى',
                'ShortDescription' => 'تطوير نظام إدارة محتوى مخصص',
                'LongDescription' => 'هذا المشروع يتطلب بناء نظام إدارة محتوى بسيط يسمح للمستخدمين بإنشاء وتحرير وحذف المقالات. يجب أن يتضمن نظام مصادقة أساسي للمستخدمين.',
                'TimeLimit' => 240,
                'Difficulty' => 'صعب',
                'XPReward' => 800,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'Title' => 'محول العملات',
                'ShortDescription' => 'بناء أداة لتحويل العملات',
                'LongDescription' => 'قم بإنشاء تطبيق ويب يقوم بتحويل العملات باستخدام أسعار الصرف الحالية. يمكنك استخدام أي واجهة برمجة تطبيقات عامة لأسعار الصرف.',
                'TimeLimit' => 90,
                'Difficulty' => 'سهل',
                'XPReward' => 300,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'Title' => 'منصة مدونة',
                'ShortDescription' => 'تطوير منصة مدونات كاملة',
                'LongDescription' => 'بناء منصة مدونات تتضمن نظام مستخدمين، إنشاء وتحرير المقالات، نظام تعليقات، وإمكانية التصنيف. يجب أن يكون هناك واجهة إدارة للمدير.',
                'TimeLimit' => 300,
                'Difficulty' => 'متوسط',
                'XPReward' => 600,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('projects')->insert($projects);
    }
    
}

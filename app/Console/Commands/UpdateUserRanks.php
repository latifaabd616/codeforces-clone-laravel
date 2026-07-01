<?php

namespace App\Console\Commands;
use App\Models\User;

use Illuminate\Console\Command;

class UpdateUserRanks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    
      protected $signature = 'users:update-ranks';
    protected $description = 'تحديث رتب المستخدمين حسب النقاط';

  
   

    /**
     * Execute the console command.
     */
    public function handle()
    {  // ترتيب المستخدمين تنازلياً حسب TotalXP وتحديث الرتب
      /*    $users = User::orderBy('TotalXp', 'DESC')->get();
           $rank = 1;
          foreach ($users as $user) {
            $user->update(['rank' => $rank]);
            $rank++;
        }

        $this->info('User ranks updated successfully.');
        return 0;
    }
} */
        // استخدام تحديث مجمع لأداء أفضل
        $users = User::orderBy('TotalXP', 'DESC')->get();
        
        DB::transaction(function () use ($users) {
            $rank = 1;
            foreach ($users as $user) {
                // تحديث مباشر دون تشغيل الأحداث
                DB::table('users')
                    ->where('id', $user->id)
                    ->update(['rank' => $rank]);
                $rank++;
            }
        });

        $this->info('User ranks updated successfully for ' . count($users) . ' users.');
        return 0;
    }
  }

<?php

namespace App\Observers;

use App\Models\User;
use App\Models\Badge;

class UserObserver
{

    public function updated(User $user)
    {
        // إذا تم تغيير TotalXP، نقوم بتحديث الرتب
        if ($user->isDirty('TotalXP')) {
            $this->updateAllRanks();
        }
        Badge::create(['Title'=>'test user','Criteria'=>'fhdjhf','Icon'=>"dgsgf"]);
    }

    protected function updateAllRanks()
    {
        $users = User::orderBy('TotalXP', 'desc')->get();
        
        $rank = 1;
        foreach ($users as $user) {
            $user->rank = $rank;
            $user->saveQuietly(); // استخدام saveQuietly لتجنب تكرار الحدث
            $rank++;
        }
    }
    /**
     * Handle the User "created" event.
     */

    public function created(User $user): void
    {
        //
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\UserBadge;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;



class UserBadgeController extends Controller
{   //get user with badges and count of them
 
   public function getUserBadgesWithCount(Request $request,$userId)
{
    
        // تحديد اللغة من الـ header أو استخدام اللغة الافتراضية
       $locale = $request->header('Accept-Language', 'en');
        app()->setLocale($locale);

    $user = User::with('badges')->find($userId);

    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'User not found'
        ], 404);
    }

    $badgesCount = $user->badges->count();

    return response()->json([
        'success' => true,
        'user_id' => $user->id,
        'user_name' => $user->name,
        'badges_count' => $badgesCount,
        'badges' => $user->badges->map(function ($badge) {
            return [
                'id' => $badge->id,
                'title' => $badge->Title,
                'title_translations'=>$badge->title_translations,
                'criteria' => $badge->Criteria,
                'criteria_translations'=>$badge->criteria_translations,
                'criteria' => $badge->Criteria,
                'icon' => $badge->Icon,
                'award_date' => $badge->pivot->AwardDate,
                'current_language' => app()->getLocale()
            ];
        }),
        // 'current_language' => app()->getLocale()
    ]);
}
///////////////////////////////////crad by admain
 


       // عرض جميع سجلات user_badges (فقط للمسؤولين)
    public function index()
    {
        if (!Auth::user()->Is_Admin) {
            return response()->json(['error' => 'غير مصرح لك بالوصول'], 403);
        }

       // $userBadges = UserBadge::all();
          // جلب جميع المستخدمين مع الـ Badges الخاصة بهم
        $users = User::with(['badges' => function($query) {
            $query->select('badges.id', 'Title', 'Criteria', 'Icon', 'AwardDate');
        }])->get(['id', 'name', 'email', 'Avatar', 'TotalXP', 'rank']);
        return response()->json([
            'success' => true,
            'data' => $users
        ]);
       // return response()->json($userBadges);
    }







    
    // إنشاء سجل جديد (فقط للمسؤولين)
    public function store(Request $request)
    {
        if (!Auth::user()->Is_Admin) {
            return response()->json(['error' => 'غير مصرح لك بالإنشاء'], 403);
        }

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'badge_id' => 'required|exists:badges,id',
            'AwardDate' => 'required|date',
        ]);

        $userBadge = UserBadge::create($validated);
        return response()->json([
            'success' => true,
            'data' =>$userBadge
        ]);
    }

       // عرض سجل محدد (فقط للمسؤولين)
    public function show($id)
    {
        if (!Auth::user()->Is_Admin) {
            return response()->json(['error' => 'غير مصرح لك بالعرض'], 403);
        }

       /*  $userBadge = UserBadge::findOrFail($id);
        return response()->json($userBadge); */
          // جلب مستخدم معين مع الـ Badges الخاصة به
        $user = User::with(['badges' => function($query) {
            $query->select('badges.id', 'Title', 'Criteria', 'Icon', 'AwardDate');
        }])->find($id, ['id', 'name', 'email', 'Avatar', 'TotalXP', 'rank']);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }
 // تحديث سجل (فقط للمسؤولين)
    public function update(Request $request, $id)
    {
        if (!Auth::user()->Is_Admin) {
            return response()->json(['error' => 'غير مصرح لك بالتحديث'], 403);
        }

        $validated = $request->validate([
            'user_id' => 'sometimes|required|exists:users,id',
            'badge_id' => 'sometimes|required|exists:badges,id',
            'AwardDate' => 'sometimes|required|date',
        ]);

        $userBadge = UserBadge::findOrFail($id);
        $userBadge->update($validated);
          return response()->json([
            'success' => true,
            'data' => $userBadge
        ]);
    }
    


    
     // حذف سجل (فقط للمسؤولين)
    /* public function destroy($id)
    {
        if (!Auth::user()->Is_Admin) {
            return response()->json(['error' => 'غير مصرح لك بالحذف'], 403);
        }

        $userBadge = UserBadge::findOrFail($id);
        $userBadge->delete();
        return response()->json(['message' => 'تم الحذف بنجاح'], 204);
    } */


     public function create()
    {
        
        //
    }
      public function edit(User_badge $user_badge)
    {
        //
    }
  
}

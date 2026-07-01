<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;

class RankingController extends Controller
{
    // عرض أول 10 مستخدمين حسب الترتيب
    public function topUsers(Request $request)
    {
         // تحديد اللغة من الـ header أو استخدام اللغة الافتراضية
       $locale = $request->header('Accept-Language', 'en');
        app()->setLocale($locale);
        
       /*  $topUsers = User::orderBy('TotalXP', 'desc')
                        ->take(10)
                        ->get(['id', 'name', 'Avatar', 'TotalXP', 'rank']);
 */
    $topUsers = User::with(['type', 'level']) // تحميل العلاقات
                    ->orderBy('TotalXP', 'desc')
                    ->take(10)
                    ->get(['id', 'name', 'Avatar', 'TotalXP', 'rank', 'type_id', 'level_id']);

        return response()->json([
            'success' => true,
            'data' => $topUsers
        ]);
    }

    // عرض رتبة مستخدم معين
    public function userRank($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'rank' => $user->rank,
                'TotalXP' => $user->TotalXP,
                'name' => $user->name,
                'Avatar' => $user->Avatar
            ]
        ]);
    }


    ////////////
      // جلب معلومات مستخدم معين
    public function show(Request $request,$id)
    {
         // تحديد اللغة من الـ header أو استخدام اللغة الافتراضية
       $locale = $request->header('Accept-Language', 'en');
        app()->setLocale($locale);

        $user = User::with(['level', 'type','badges'])->find($id);
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $user,
            'current_language' => app()->getLocale()
        ]);
    }
}

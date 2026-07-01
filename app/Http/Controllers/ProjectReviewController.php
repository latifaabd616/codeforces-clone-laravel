<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Auth; 
use App\Models\UserProject;
use App\Models\ProjectTechnology;
use App\Models\UserPreference;
use Illuminate\Support\Facades\DB;
use App\Models\Notification; 

class ProjectReviewController extends Controller
{
// الحصول على جميع المشاريع التي تحتاج إلى مراجعة
    public function pendingReviews()
{
    if (!Auth::user()->Is_Admin) {
            return response()->json(['error' => 'غير مصرح لك '], 403);
        }
    $projects = UserProject::with(['user', 'project'])
        ->whereHas('project') // التأكد من وجود مشروع مرتبط
        ->whereNotNull('Submittedfile') // حيث يوجد ملف مقدم
        ->where('ReviewStatus', 'pending') // ولم تتم مراجعته بعد
        ->get();

    return response()->json($projects);
}
/////////////////////////////////////////////////////////////////////////
  // عرض حل مشروع معين للمراجعة
    public function showReview($id)
    {
       if (!Auth::user()->Is_Admin) {
            return response()->json(['error' => 'غير مصرح لك '], 403);
        }
    // جلب مشروع المستخدم
    $userProject = UserProject::with(['project','user'])->findOrFail($id);
    
    // جلب تقنيات المشروع الأصلية
    $projectTechnologies = ProjectTechnology::where('project_id', $userProject->Project_id)->get();
    
    // جلب تفضيلات المستخدم للتقنيات لهذا المشروع
    $userPreferences = UserPreference::where('userproject_id', $id)->get();
    $baseXP = $userProject->project->XPReward;
    $extraXP = 0;
    $totalXp=0;
   // مقارنة تفضيلات المستخدم مع تقنيات المشروع
     foreach ($userPreferences as $preference) {
          $projectTechnology = ProjectTechnology::where('project_id', $userProject->Project_id)
          ->where(['platform_id'=>$preference->Platform_id,'framework_id'=>$preference->framework_id,'ProgrammingLanguage_id'=>$preference->programminglanguage_id])
          
          ->
          first();
         // return dd($projectTechnologies);

     
         $extraXP+= $projectTechnology->ExtraXP;
         }
        $totalXp=$extraXP+$baseXP;
    

   

         return response()->json([
            'user_project' => $userProject,
            //'user' => $userProject->user->name,
            //'submitted_file' => $userProject->Submittedfile,
            //'submission_details' => $userProject,
             'total_xp' => $totalXp
        ]);

    }
/////////////////////////////////////////
       // تحديث حالة المراجعة
public function updateReview(Request $request, $id)
{
    // التحقق من أن المستخدم مسؤول
    if (!auth()->user()->Is_Admin) {
        return response()->json(['message' => 'غير مصرح بالوصول'], 403);
    }
    

      $validated =$request->validate([
   // 'status' => 'nullable|string', // أي نص مقبول
        'ReviewStatus' => 'required|string', // أي نص مقبول
       // 'UserProject_id' => 'required|exists:user_projects,id',
        'xpcategories' => 'nullable|array',
        'xpcategories.*.xpcategory_id' => 'nullable|exists:xpcategories,id',
        'xpcategories.*.xp_value' => 'nullable|integer',
        'xpcategories.*.notice' => 'nullable|string',
     ]);

    $userProject = UserProject::findOrFail($id);
  
    $user = $userProject->user; // الحصول على المستخدم المرتبط بالمشروع
    $userProject = UserProject::with(['user', 'user.level'])->findOrFail($id);
       // تحقق إضافي لحالات التحديث غير المسموحة
    if ($userProject->ReviewStatus === 'accepted' && $request->ReviewStatus === 'rejected') {
        return response()->json(['message' => 'لا يمكن رفض مشروع تمت الموافقة عليه مسبقاً'], 422);
    }
 
    // تحديث حالة المراجعة
    $userProject->ReviewStatus = $request->ReviewStatus;
    //$userProject->Status = $request->Status; // يمكن جعلها متطابقة أو مختلفة حسب منطق التطبيق
    $userProject->ReviewDate = now();
    $userProject->save();
      $totalXP = 0; // متغير لحساب المجموع الكلي

 // إضافة السجلات الجديدة
 if ($request->ReviewStatus === 'accepted'){
    foreach ($request->xpcategories as $category) {
        DB::table('user_project_xpcategories')->insert([
            'UserProject_id' => $userProject->id,
            'Xpcategory_id' => $category['xpcategory_id'],
            'XPValue' => $category['xp_value'],
            'Notice' => $category['notice'] ?? null,
           
        ]);
        $totalXP += $category['xp_value']; // جمع القيم
    }
    
}
  

        // تحديث الـ TotalXP للمستخدم فقط إذا تم قبول المشروع
    if ($request->ReviewStatus === 'accepted') {
        // طرح الـ XP القديم (إذا كان المشروع مقبولاً مسبقاً) وإضافة الجديد
        $user->TotalXP = ($user->TotalXP ) + $totalXP;
         $user->save();
           // الحصول على المستوى الحالي والمستوى التالي
        $currentLevel = $user->level;
        $nextLevel = DB::table('levels')
            ->where('RequiredXP', '>', $currentLevel->RequiredXP ?? 0)
            ->orderBy('RequiredXP')
            ->first();

        // التحقق إذا كان المستخدم مؤهلاً للترقية
        if ($nextLevel && $user->TotalXP >= $nextLevel->RequiredXP) {
            $user->level_id = $nextLevel->id;
            $user->save();
       
         /*   $level = $nextLevel->title_translations; 
           $level_data = json_decode($level, true); // تحويل إلى array
           $level_ar = $level_data['ar']; // الحصول على العربية فقط
           $level_en = $level_data['en']; */
              // التحقق من وجود الترجمات بشكل آمن
            $level_translations = json_decode($nextLevel->title_translations, true) ?? [];
            
            $level_ar = $level_translations['ar'] ?? 'المستوى'; // قيمة افتراضية إذا لم يوجد
            $level_en = $level_translations['en'] ?? 'Level'; // قيمة افتراضية إذا لم يوجد
         // إرسال إشعار ترقية المستوى
    
              Notification::create([
                'user_id' => $user->id,
                'Title' =>  'Congratulations! You have been promoted',
                'Description' => "Congratulations! You have advanced to  level .$level_en",
                'ReceiveDate' => now(),
                'Is_read' => false,
                'title_translations' => [
                    'en' => 'Congratulations! You have been promoted',
                    'ar' => 'تهانينا! لقد تم ترقيتك'
                ],
                'description_translations' => [
                    'en' => "Congratulations! You have advanced to  level .$level_en ",
                    'ar' => "مبروك! لقد تقدمت إلى المستوى .$level_ar"
                ]
            ]);

           // Notification::create($notificationData);
        }
        
        // التحقق من عدد المشاريع المقبولة للمستخدم
     $acceptedProjectsCount = UserProject::where('user_id', $user->id)
        ->where('ReviewStatus', 'accepted')
        ->count();
        // منح الشارة إذا وصل عدد المشاريع المقبولة إلى 5
    if ($acceptedProjectsCount = 5) {
        DB::table('user_badges')->insert([
            'user_id' => $user->id,
            'badge_id' => 11,
            'AwardDate' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

         Notification::create([
                'user_id' => $user->id,
                'Title' =>  'New Badge Unlocked',
                'Description' => "You have earned the 5 Projects Completed badge",
                'ReceiveDate' => now(),
                'Is_read' => false,
                'title_translations' => [
                    'en' => 'New Badge Unlocked',
                    'ar' =>'شارة جديدة'
                ],
                'description_translations' => [
                    'en' => "You have earned the 5 Projects Completed badge ",
                    'ar' => "لقد حصلت على شارة 5 مشاريع مكتملة"
                
                ]
            ]);
    
}
    }
  
    return response()->json([
        'message' => 'تم تحديث حالة المراجعة بنجاح',
        'data' => [
           
            'new_status' => $userProject->ReviewStatus,
            'user_name' => $userProject->user->name,
            'project_title' => $userProject->project->Title,
            'xpcategories_added' => count($request->xpcategories),
            'total_xp' => $totalXP, 
            'current_level' => $user->level->Title ,
      
          
        ]
    ]);
      
      

    
}
}

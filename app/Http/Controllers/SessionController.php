<?php

namespace App\Http\Controllers;

use App\Models\Session;
use App\Models\UserProject;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Project;
use App\Models\Type;
class SessionController extends Controller
{   
    public function store(Request $request)
    {
        // التحقق من أن المستخدم قد اختار مشروعًا
        $userProject = UserProject::where('user_id', Auth::id())
            ->where('Project_id', $request->project_id)
            ->first();

        if (!$userProject) {
            return "لم يتم اختيار المشروع من قبلك"; // أو يمكنك إرجاع رسالة خطأ
        }

        // إنشاء جلسة جديدة
          $session = Session::create([
            'UserProject_id' => $userProject->id,
            'StartTime' => $request->start_time,
            'EndTime' => $request->end_time ?? null,
            'SessionTime' => $request->session_time ?? 0,
            'ActiveTime' => $request->active_time ?? 0,
            'SuccessfulRuns' => $request->successful_runs ?? 0,
            'ErrorRuns' => $request->error_runs ?? 0,
            'MeanTimeToFixError' => $request->mean_time_to_fix_error ?? 0,
            'CodingPeriod' => $request->coding_period ?? 0,
        ]);
   
      $userId = Auth::id();
    
    $stats = Session::whereHas('userProject', function($query) use ($userId) {
            $query->where('user_id', $userId);
        })
        ->selectRaw('CodingPeriod, COUNT(*) as count')
        ->groupBy('CodingPeriod')
        ->get()
        ->pluck('count', 'CodingPeriod');
            // الحصول على القيم لكل فترة
    $energeticCount = $stats->get('Energetic', 0);
    $ordinaryCount = $stats->get('Ordinary', 0);
    $nightOwlCount = $stats->get('Night Owl', 0);
    $vampireCount = $stats->get('Vampire', 0);


        $periodCounts = [
        'Energetic' => $stats->get('Energetic', 0),
        'Ordinary' => $stats->get('Ordinary', 0),
        'Night Owl' => $stats->get('Night Owl', 0),
        'Vampire' => $stats->get('Vampire', 0)
    ];


 // إيجاد الفترة الأكثر تكراراً
    $maxCount = max($periodCounts);
    $mostFrequentPeriods = array_keys($periodCounts, $maxCount);

    // إذا كان هناك تعادل، نحدد أولوية معينة
    if (count($mostFrequentPeriods) > 1) {
        // أولوية الفترات (يمكن تعديل هذا الترتيب حسب احتياجك)
        $priorityOrder = ['Energetic', 'Ordinary', 'Night Owl', 'Vampire'];
        
        foreach ($priorityOrder as $priorityPeriod) {
            if (in_array($priorityPeriod, $mostFrequentPeriods)) {
                $mostFrequentPeriodName = $priorityPeriod;
                break;
            }
        }
    } else {
        $mostFrequentPeriodName = $mostFrequentPeriods[0];
    }
     // تحديث نوع المستخدم بناءً على الفترة الأكثر تكراراً
    $user = User::find($userId);
    
    if ($user) {
        // البحث عن الـ type_id المناسب بناءً على الـ Title
        $type = \App\Models\Type::where('Title', $mostFrequentPeriodName)->first();
        
        if ($type) {
            $user->type_id = $type->id;
            $user->save();
        }
    }

    return  [
        'Energetic' => $stats->get('Energetic', 0),
        'Ordinary' => $stats->get('Ordinary', 0),
        'Night Owl' => $stats->get('Night Owl', 0),
        'Vampire' => $stats->get('Vampire', 0),
        //'most_used_count' => $maxCount,
         'most_frequent_period' => $mostFrequentPeriodName,
    ]; 
//////////////////////////////////////////////
       /*      return response()->json([
            'message' => 'تم حفظ بيانات الجلسة بنجاح',
            'session_id' => $session->id,
            'start_time' => $session->StartTime,
            'end_time' => $session->EndTime
        ], 201); */
    
    }


public function getUserSessionsForProject($userId, $projectId)
{
    // التحقق من وجود المستخدم
    $user = User::find($userId);
    if (!$user) {
        return response()->json(['message' => 'المستخدم غير موجود'], 404);
    }

    // التحقق من وجود المشروع
    $project = Project::find($projectId);
    if (!$project) {
        return response()->json(['message' => 'المشروع غير موجود'], 404);
    }

    // التحقق من أن المستخدم قد اختار المشروع
    $userProject = UserProject::with(['user', 'project'])
                ->where('user_id', $userId)
                ->where('Project_id', $projectId)
                ->first();

    if (!$userProject) {
        return response()->json(['message' => 'هذا المستخدم لم يختر المشروع المحدد'], 400);
    }

    // جلب جميع معلومات الجلسات بدون استخدام map
    $sessions = Session::where('UserProject_id', $userProject->id)
                
                ->get();

    // إعداد البيانات للإرجاع
    $response = [
        'user' => [
            'id' => $user->id,
            'name' => $user->name
        ],
        'project' => [
            'id' => $project->id,
            'title' => $project->Title
        ],
        'sessions' => $sessions,
      
    ];

    return response()->json($response);

}




 


    public function index()
    {
    
    
    }

     public function show()
    {
       
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

   

    /**
     * Display the specified resource.
     */
   
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Session $session)
    {
        //
    }

   

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Session $session)
    {
        //
    }
}

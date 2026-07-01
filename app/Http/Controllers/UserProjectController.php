<?php

namespace App\Http\Controllers;

//use App\Models\User_project;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Project;
use App\Models\UserProject;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class UserProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
      // إرسال ملف للمشروع
    public function submitProjectFile(Request $request)
    {
        // التحقق من البيانات مباشرة في الـ Controller
        $validator = Validator::make($request->all(), [
            'project_id' => 'required|exists:projects,id',
            'submitted_file' => 'required|url|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = Auth::user();
        
        // البحث عن سجل user_project المناسب
        $userProject = UserProject::where('user_id', $user->id)
            ->where('project_id', $request->project_id)
            ->first();
            
        if (!$userProject) {
            return response()->json([
                'message' => 'Project not found for this user'
            ], 404);
        }
        
        // تحديث الحقل Submittedfile
    /*     $userProject->update([
            'Submittedfile' => $request->submitted_file,
            //'Status' => 'submitted', // يمكنك تغيير الحالة إذا لزم الأمر
        ]);
         */
 // تحديث بيانات المشروع
        $userProject->update([
            'Submittedfile' => $request->submitted_file,
            'Status' => 'completed',
            'ReviewStatus' => 'pending',
            'ReviewDate' => null,
            'FinishDate' => now(),
        ]);
        return response()->json([
            'message' => 'File submitted successfully',
            'data' => $userProject
        ]);
    }
    

   
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(User_project $user_project)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User_project $user_project)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User_project $user_project)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User_project $user_project)
    {
        //
    }

  public function getAcceptedProjects(Request $request,$userId)
{

        // تحديد اللغة من الـ header أو استخدام اللغة الافتراضية
       /* $locale = $request->header('Accept-Language', 'en');
        app()->setLocale($locale); */
         // تحديد اللغة من الـ header أو استخدام اللغة الافتراضية
    $locale = $request->header('Accept-Language', 'en');
    
    // التحقق من وجود اللغة المطلوبة
    $availableLocales = ['en', 'ar'];
    if (!in_array($locale, $availableLocales)) {
        $locale = 'en';
    }
    
    app()->setLocale($locale);
  


    $user = User::findOrFail($userId);
    
    // جلب المشاريع المقبولة مع معلومات إضافية
    $acceptedProjects = $user->acceptedProjects()
        ->select('projects.*', 
        'user_projects.FinishDate as completion_date',
        'user_projects.id as user_project_id')
        ->get();
    
    return response()->json([
        'success' => true,
        'data' => [
           // 'user' => $user,
            'accepted_projects' => $acceptedProjects,
             'current_language' => app()->getLocale()
        ],
        'message' => 'User data with accepted projects retrieved successfully'
    ]);
}
//تعيد المشاريع التي اختارها المستخدم
public function getUserProjects(Request $request,$userId)
{
    
        // تحديد اللغة من الـ header أو استخدام اللغة الافتراضية
       $locale = $request->header('Accept-Language', 'en');
        app()->setLocale($locale);

     $user = User::findOrFail($userId);
        
        $projects = $user->projects()->get();
        
        return response()->json([
            'success' => true,
            'data' => $projects,
            'current_language' => app()->getLocale()
        ]);
}
///////
//اشار النجمة للمشاريع المفضلة

    public function toggleFavorite(Project $project)
    {
        $user = Auth::user();
        
        // التحقق من وجود السجل أو إنشائه إذا لم يكن موجوداً
        $userProject = UserProject::firstOrCreate(
            [
                'user_id' => $user->id,
                'Project_id' => $project->id
            ],
            [
                'Status' => 'pending',
                'ReviewStatus' => 'pending',
                'StartDate' => now(),
                'favorite' => false // القيمة الافتراضية
            ]
        );
        
        // تبديل حالة المفضلة
        $userProject->favorite = !$userProject->favorite;
        $userProject->save();
        
        return response()->json([
            'success' => true,
            'message' => $userProject->favorite ? 
                         'تم إضافة المشروع إلى المفضلة' : 
                         'تم إزالة المشروع من المفضلة',
            'is_favorite' => $userProject->favorite
        ]);
    }


}



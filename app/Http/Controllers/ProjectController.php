<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserProject;
use App\Models\UserPreference;
use App\Models\ProjectTechnology;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\ProjectResource;

class ProjectController extends Controller
{
    
//get all project with  their available technologies
    public function index(Request $request)
    {
        // تحديد اللغة من الـ header أو استخدام اللغة الافتراضية
    $locale = $request->header('Accept-Language', 'en');
    
    // التحقق من أن اللغة مدعومة
    $supportedLocales = ['en', 'ar'];
    if (in_array($locale, $supportedLocales)) {
        app()->setLocale($locale);
    } else {
        app()->setLocale('en'); // اللغة الافتراضية
    }

         $projects = Project::with([
            'technologies.framework',
            'technologies.platform',
            'technologies.programmingLanguage'
        ])->get();
        
        return response()->json([
            'data' => ProjectResource::collection($projects),
             'current_language' => app()->getLocale()
        ]);
    
    
    }
//get a specific project with its available technologies
  public function show(Request $request,$id)
    {
         // تحديد اللغة من الـ header أو استخدام اللغة الافتراضية
    $locale = $request->header('Accept-Language', 'en');
    app()->setLocale($locale);

        $project = Project::with([
            'technologies.framework',
            'technologies.platform',
            'technologies.programmingLanguage'
        ])->findOrFail($id);

        return new ProjectResource($project);
    }
// اختيار مشروع جديد
    public function store(Request $request)
    {

 // التحقق من صحة بيانات المشروع
    $projectValidator = Validator::make($request->all(), [
        'project_id' => 'required|exists:projects,id',
        'framework_id' => 'nullable|exists:frameworks,id',
        'programming_language_id' => 'nullable|exists:programming_languages,id',
        'platform_id' => 'nullable|exists:platforms,id',
    ]);

     

    if ($projectValidator->fails()) {
        return response()->json($projectValidator->errors(), 422);
    }


    // إنشاء المشروع
    $userproject = UserProject::create([
        'user_id' => Auth::id(),
   
        'project_id' => $request->project_id, // تغيير Project_id إلى project_id
        'Status' => 'under development',
        'StartDate' => now(),
    ]);
      // إنشاء تفضيلات المستخدم إذا وجدت
    if ($request->filled('framework_id') || $request->filled('programming_language_id') || $request->filled('platform_id')) {
        $projectpreference = new UserPreference();
        
        if ($request->filled('framework_id')) {
            $projectpreference->framework_id = $request->framework_id;
        }
        
        if ($request->filled('programming_language_id')) {
            $projectpreference->ProgrammingLanguage_id = $request->programming_language_id;
        }
        
        if ($request->filled('platform_id')) {
            $projectpreference->platform_id = $request->platform_id;
        }
        
        $projectpreference->userproject_id = $userproject->id;
        $projectpreference->save();
    }

    return response()->json([
        'message' => 'Project and preferences added successfully',
        'user_project' => $userproject,
    ], 201);





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
  

 

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        //
    }
///////////////////////////////////////////
///////crad project by admain
        public function indexproject(Request $request)
    {
         if (!Auth::user()->Is_Admin) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // تحديد اللغة من الـ header أو استخدام اللغة الافتراضية
    $locale = $request->header('Accept-Language', 'en');
    app()->setLocale($locale);
        
        $projects = Project::all();
       // return response()->json($projects);

        return response()->json([
        'message' => ' successfully',
        'data' => $projects,
        'current_language' => app()->getLocale()
        
    ], 201);      
    }
    


        public function storeproject(Request $request)
    {
        
        if (!Auth::user()->Is_Admin) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        
   

        $validated = $request->validate([
        'Title' => 'required|string|max:100',
        'ShortDescription' => 'required|string',
        'LongDescription' => 'required|string',
        'TimeLimit' => 'required|integer',
        'Difficulty' => 'required|string|max:50',
        'XPReward' => 'required|integer',
        'ExtraXP' => 'nullable|integer|min:0',
        'framework_id' => 'sometimes|integer|exists:frameworks,id', // تغيير إلى قيمة مفردة
        'programming_language_id' => 'sometimes|integer|exists:programming_languages,id', // تغيير إلى قيمة مفردة
        'platform_id' => 'sometimes|integer|exists:platforms,id', // تغيير إلى قيمة مفردة
        'title_translations' => 'sometimes|array',
        'title_translations.en' => 'required_with:title_translations|string',
        'title_translations.ar' => 'required_with:title_translations|string',
        'short_description_translations' => 'sometimes|array',
        'short_description_translations.en' => 'required_with:short_description_translations|string',
        'short_description_translations.ar' => 'required_with:short_description_translations|string',
        'long_description_translations' => 'sometimes|array',
        'long_description_translations.en' => 'required_with:long_description_translations|string',
        'long_description_translations.ar' => 'required_with:long_description_translations|string',
        'difficulty_translations' => 'sometimes|array',
        'difficulty_translations.en' => 'required_with:difficulty_translations|string',
        'difficulty_translations.ar' => 'required_with:difficulty_translations|string',
          ]);


  //  $project = Project::create($request->only('Title','ShortDescription','LongDescription','TimeLimit','Difficulty','XPReward'));
  
     // إضافة الترجمات إذا وجدت
/*     if ($request->has('title_translations')) {
        $project['title_translations'] = $validated['title_translations'];
    } else {
        $project['title_translations'] = [
            'en' => $validated['Title'],
            'ar' => $validated['Title'] // ترجمة افتراضية
        ];
     }
    
    if ($request->has('short_description_translations')) {
        $project['short_description_translations'] = $validated['short_description_translations'];
    } else {
        $project['short_description_translations'] = [
            'en' => $validated['ShortDescription'],
            'ar' => $validated['ShortDescription'] // ترجمة افتراضية
        ];
    }
    
    if ($request->has('long_description_translations')) {
        $project['long_description_translations'] = $validated['long_description_translations'];
    } else {
        $project['long_description_translations'] = [
            'en' => $validated['LongDescription'],
            'ar' => $validated['LongDescription'] // ترجمة افتراضية
        ];
    }
    
    if ($request->has('difficulty_translations')) {
        $project['difficulty_translations'] = $validated['difficulty_translations'];
    } else {
        $project['difficulty_translations'] = [
            'en' => $validated['Difficulty'],
            'ar' => $validated['Difficulty'] // ترجمة افتراضية
        ];
    }*/
          // إنشاء مصفوفة البيانات للمشروع
    $projectData = [
        'Title' => $validated['Title'],
        'ShortDescription' => $validated['ShortDescription'],
        'LongDescription' => $validated['LongDescription'],
        'TimeLimit' => $validated['TimeLimit'],
        'Difficulty' => $validated['Difficulty'],
        'XPReward' => $validated['XPReward'],
    ];
     // إضافة الترجمات إذا وجدت
    if ($request->has('title_translations')) {
        $projectData['title_translations'] = $validated['title_translations'];
    } else {
        $projectData['title_translations'] = [
            'en' => $validated['Title'],
            'ar' => $validated['Title'] // ترجمة افتراضية
        ];
    }
    
    if ($request->has('short_description_translations')) {
        $projectData['short_description_translations'] = $validated['short_description_translations'];
    } else {
        $projectData['short_description_translations'] = [
            'en' => $validated['ShortDescription'],
            'ar' => $validated['ShortDescription'] // ترجمة افتراضية
        ];
    }
    
    if ($request->has('long_description_translations')) {
        $projectData['long_description_translations'] = $validated['long_description_translations'];
    } else {
        $projectData['long_description_translations'] = [
            'en' => $validated['LongDescription'],
            'ar' => $validated['LongDescription'] // ترجمة افتراضية
        ];
    }
    
    if ($request->has('difficulty_translations')) {
        $projectData['difficulty_translations'] = $validated['difficulty_translations'];
    } else {
        $projectData['difficulty_translations'] = [
            'en' => $validated['Difficulty'],
            'ar' => $validated['Difficulty'] // ترجمة افتراضية
        ];
    }

    // إنشاء المشروع
    $project = Project::create($projectData);

    // قيمة ExtraXP واحدة لجميع العلاقات
    $extraXP = $request->ExtraXP ?? 0;
     // إنشاء تفضيلات إذا وجدت
    if ($request->filled('framework_id') || $request->filled('programming_language_id') || $request->filled('platform_id')) {
       // $projectpreference = new UserPreference();
       $projectTechnology = new ProjectTechnology();
        
        if ($request->filled('framework_id')) {
           $projectTechnology->framework_id = $request->framework_id;
        }
        
        if ($request->filled('programming_language_id')) {
            $projectTechnology->ProgrammingLanguage_id = $request->programming_language_id;
        }
        
        if ($request->filled('platform_id')) {
           $projectTechnology->platform_id = $request->platform_id;
        }
        $projectTechnology->ExtraXP = $extraXP;
        $projectTechnology->project_id = $project->id;
        $projectTechnology->save();
    }

        
        return response()->json([
        'message' => 'Project created successfully',
        'data' => $project,
    ], 201);

    }


      public function showproject(Request $request,$id)
    {
         if (!Auth::user()->Is_Admin) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
         // تحديد اللغة من الـ header أو استخدام اللغة الافتراضية
    $locale = $request->header('Accept-Language', 'en');
    app()->setLocale($locale);

         $project = Project::findOrFail($id);
        //return response()->json($project);

         
        return response()->json([
        'message' => ' successfully',
        'data' => $project,
        'current_language' => app()->getLocale()
        
    ], 201);
    }


   public function updateProject(Request $request, $id)
{
    if (!Auth::user()->Is_Admin) {
        return response()->json(['message' => 'Unauthorized'], 403);
    }
    
    // البحث عن المشروع المطلوب تعديله
    $project = Project::find($id);
    
    if (!$project) {
        return response()->json(['message' => 'Project not found'], 404);
    }
      // التحقق من الصحة
    $validated = $request->validate([
        'Title' => 'sometimes|string|max:100',
        'ShortDescription' => 'sometimes|string',
        'LongDescription' => 'sometimes|string',
        'TimeLimit' => 'sometimes|integer',
        'Difficulty' => 'sometimes|string|max:50',
        'XPReward' => 'sometimes|integer',
        'ExtraXP' => 'nullable|integer|min:0',  // قيمة ExtraXP واحدة للمشروع
       // 'framework_id' => 'sometimes|integer',
       // 'framework_ids.*' => 'integer|exists:frameworks,id',
       'framework_ids' => 'sometimes|integer|exists:frameworks,id', // تغيير إلى قيمة مفردة
        'programming_language_ids' => 'sometimes|integer|exists:programming_languages,id', // تغيير إلى قيمة مفردة
        'platform_ids' => 'sometimes|integer|exists:platforms,id', // تغيير إلى قيمة مفردة
          'title_translations' => 'sometimes|array',
        'title_translations.en' => 'required_with:title_translations|string',
        'title_translations.ar' => 'required_with:title_translations|string',
        'short_description_translations' => 'sometimes|array',
        'short_description_translations.en' => 'required_with:short_description_translations|string',
        'short_description_translations.ar' => 'required_with:short_description_translations|string',
        'long_description_translations' => 'sometimes|array',
        'long_description_translations.en' => 'required_with:long_description_translations|string',
        'long_description_translations.ar' => 'required_with:long_description_translations|string',
        'difficulty_translations' => 'sometimes|array',
        'difficulty_translations.en' => 'required_with:difficulty_translations|string',
        'difficulty_translations.ar' => 'required_with:difficulty_translations|string',
    ]);


    // تحديث بيانات المشروع الأساسية
    $project->update($request->only('Title', 'ShortDescription', 'LongDescription', 'TimeLimit', 'Difficulty', 'XPReward'));
       // قيمة ExtraXP الجديدة
        // تحديث الترجمات إذا وجدت
    if ($request->has('title_translations')) {
        $updateData['title_translations'] = $validated['title_translations'];
    }
    
    if ($request->has('short_description_translations')) {
        $updateData['short_description_translations'] = $validated['short_description_translations'];
    }
    
    if ($request->has('long_description_translations')) {
        $updateData['long_description_translations'] = $validated['long_description_translations'];
    }
    
    if ($request->has('difficulty_translations')) {
        $updateData['difficulty_translations'] = $validated['difficulty_translations'];
    }
    $project->update($updateData);
    // قيمة ExtraXP
       // التعامل مع العلاقات التقنية
    $projectTechnology = ProjectTechnology::firstOrNew(['project_id' => $project->id]);
    
    // تحديث أو مسح framework_id
    if ($request->has('framework_ids')) {
        $projectTechnology->framework_id = $request->framework_ids;
    }
    
    // تحديث أو مسح programming_language_id
    if ($request->has('programming_language_ids')) {
        $projectTechnology->ProgrammingLanguage_id = $request->programming_language_ids;
    }
    
    // تحديث أو مسح platform_id
    if ($request->has('platform_ids')) {
        $projectTechnology->platform_id = $request->platform_ids;
    }
    
    // تحديث ExtraXP إذا تم تقديمه
    if ($request->has('ExtraXP')) {
        $projectTechnology->ExtraXP = $request->ExtraXP ?? 0;
    }
    
    $projectTechnology->save();

    return response()->json([
        'message' => 'Project updated successfully',
        'data' => $project->load('technologies'),
    ], 200);

    }
//////////////////
//show category details for a specific user"s project
 public function getUserProjectDetails($userProjectId)
    {
       // جلب بيانات المشروع مع العلاقات
        $userProject = UserProject::with([
            'project',
            'xpCategories'
        ])->find($userProjectId);

        // إذا لم يتم العثور على المشروع
        if (!$userProject) {
            return response()->json([
                'success' => false,
                'message' => 'المشروع غير موجود'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $userProject
        ]);
    }
    

   
}





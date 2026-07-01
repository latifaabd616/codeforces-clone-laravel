<?php

namespace App\Http\Controllers;

use App\Models\Project_language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ProjectTechnology;
use App\Models\Project;

class ProjectTechnologyController extends Controller
{
    ///////crad ProjectTechnology by admain
       // عرض جميع السجلات
    public function index()
    {
       /*  if (!Auth::user()->Is_Admin) {
            return response()->json(['message' => 'Unauthorized'], 403);
        } */

       // $projectTechnology = ProjectTechnoloy::all();
       $projectTechnology = Project::with([
            'platforms',
            'frameworks',
            'programmingLanguages'
        ])->get();
        //return response()->json( $projectTechnology );

          return response()->json([
        'message' => ' successfully',
        'data' =>$projectTechnology ,
        
    ], 201);



       
        
        
       
    }

       // إنشاء سجل جديد
    public function store(Request $request)
    {
        if (!Auth::user()->Is_Admin) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'project_id' => 'required|exists:Projects,id',
            'ProgrammingLanguage_id' => 'nullable|exists:Programming_languages,id',
            'framework_id' => 'nullable|exists:frameworks,id',
            'platform_id' => 'nullable|exists:platforms,id',
            'ExtraXP' => 'integer|min:0',
        ]);

        $projectTechnology = ProjectTechnology::create($validated);
        //return response()->json($projectTechnology, 201);

            return response()->json([
        'message' => ' successfully',
        'data' =>$projectTechnology ,
        
    ], 201);
        
    }

   // عرض سجل محدد
    public function show($id)
    {
        /* if (!Auth::user()->Is_Admin) {
            return response()->json(['message' => 'Unauthorized'], 403);
        } */
       $projectTechnology = Project::with([
            'platforms',
            'frameworks',
            'programmingLanguages'
        ])->findOrFail($id);

        //return response()->json($projectTechnology);

            return response()->json([
        'message' => ' successfully',
        'data' =>$projectTechnology ,
        
    ], 201);





       
    }

      
    // تحديث سجل
    public function update(Request $request, $id)
    {    \Log::info('Update request received', $request->all());
        if (!Auth::user()->Is_Admin) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

                    // العثور على المستوى المطلوب أو إرجاع 404
     $projectTechnology  = ProjectTechnology::findOrFail($id);
    \Log::info('Before update',  $projectTechnology  ->toArray());

        $validated = $request->validate([
            'project_id' => 'sometimes|required|exists:Projects,id',
            'ProgrammingLanguage_id' => 'nullable|exists:Programming_languages,id',
            'framework_id' => 'nullable|exists:frameworks,id',
            'platform_id' => 'nullable|exists:platforms,id',
            'ExtraXP' => 'sometimes|integer|min:0',
        ]);

        $projectTechnology->update($validated);
       // return response()->json($projectTechnology);

            return response()->json([
        'message' => ' successfully',
        'data' =>$projectTechnology ,
        
    ], 201);
    }


 
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(project_technology $project_technology)
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
     * Show the form for editing the specified resource.
     */
    public function edit(project_technology $project_technology)
    {
        //
    }
}
